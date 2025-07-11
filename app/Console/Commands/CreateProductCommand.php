<?php

namespace App\Console\Commands;

use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Console\Command;
use InvalidArgumentException;

class CreateProductCommand extends Command
{
    protected $signature = 'product:create 
                            {--name= : Product name (required for creation)}
                            {--description= : Product description (required for creation)}
                            {--price= : Product price (required for creation)}
                            {--image= : Product image path (optional)}
                            {--categories= : Category IDs comma-separated (optional)}
                            {--list : List all existing products}
                            {--category-filter= : Filter products by category ID (use with --list)}
                            {--sort-price= : Sort by price: asc or desc (use with --list)}';

    protected $description = 'Create a new product or list existing products via command line';

    private ProductServiceInterface $productService;
    private CategoryServiceInterface $categoryService;

    public function __construct(
        ProductServiceInterface $productService,
        CategoryServiceInterface $categoryService
    ) {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function handle(): int
    {
        try {
            if ($this->option('list')) {
                return $this->handleListMode();
            }

            return $this->handleCreateMode();

        } catch (InvalidArgumentException $e) {
            $this->error(" Erreur: {$e->getMessage()}");
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error(" Erreur inattendue: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    private function handleListMode(): int
    {
        $this->info(' Liste des Produits');
        $this->line('');

        $filters = [];
        
        if ($this->option('category-filter')) {
            $filters['category_id'] = (int) $this->option('category-filter');
        }
        
        if ($this->option('sort-price')) {
            $sortOrder = strtolower($this->option('sort-price'));
            if (in_array($sortOrder, ['asc', 'desc'])) {
                $filters['sort_by_price'] = $sortOrder;
            }
        }

        $products = $this->productService->getAllProducts($filters);

        if ($products->isEmpty()) {
            $this->warn('  Aucun produit trouvé.');
            return Command::SUCCESS;
        }

        $this->displayProductsList($products);

        return Command::SUCCESS;
    }

    private function handleCreateMode(): int
    {
        $this->validateRequiredOptions();

        $productData = $this->buildProductData();

        $product = $this->productService->createProduct($productData);

        $this->displaySuccess($product);

        return Command::SUCCESS;
    }

    private function validateRequiredOptions(): void
    {
        $required = ['name', 'description', 'price'];
        $missing = [];

        foreach ($required as $option) {
            if (empty($this->option($option))) {
                $missing[] = "--{$option}";
            }
        }

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                'Options requises manquantes: ' . implode(', ', $missing)
            );
        }

        if (!is_numeric($this->option('price')) || $this->option('price') <= 0) {
            throw new InvalidArgumentException('Le prix doit être un nombre positif.');
        }
    }

    private function buildProductData(): array
    {
        $productData = [
            'name' => $this->option('name'),
            'description' => $this->option('description'),
            'price' => (float) $this->option('price'),
        ];

        if ($this->option('image')) {
            $productData['image'] = $this->option('image');
        }

        if ($this->option('categories')) {
            $categoryIds = array_map('intval', array_filter(explode(',', $this->option('categories'))));
            if (!empty($categoryIds)) {
                $productData['category_ids'] = $categoryIds;
            }
        }

        return $productData;
    }

    private function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' Dh';
    }

    private function displayProductsList($products): void
    {
        $tableData = [];
        
        foreach ($products as $product) {
            $tableData[] = [
                $product->id,
                $product->name,
                $this->truncateText($product->description, 40),
                $this->formatPrice($product->price),
                $product->categories->pluck('name')->join(', ') ?: 'Aucune',
                $product->created_at->format('d/m/Y'),
            ];
        }

        $this->table(
            ['ID', 'Nom', 'Description', 'Prix', 'Catégories', 'Créé le'],
            $tableData
        );
    }

    private function displaySuccess($product): void
    {
        $this->info('✅ Produit créé avec succès!');
        $this->line('');
        
        $this->table(
            ['Champ', 'Valeur'],
            [
                ['ID', $product->id],
                ['Nom', $product->name],
                ['Description', $product->description],
                ['Prix', $this->formatPrice($product->price)],
                ['Image', $product->image ?: 'Aucune'],
                ['Catégories', $product->categories->pluck('name')->join(', ') ?: 'Aucune'],
                ['Créé le', $product->created_at->format('d/m/Y H:i:s')],
            ]
        );
    }

    private function truncateText(string $text, int $length): string
    {
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }
}