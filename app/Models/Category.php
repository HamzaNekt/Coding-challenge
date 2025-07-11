<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

 
    
    protected $fillable = [
        'name',
        'parent_id',
    ];

 
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }


    public function hasParent(): bool
    {
        return !is_null($this->parent_id);
    }

 
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

 
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

  
    public function scopeWithProductsCount($query)
    {
        return $query->withCount('products');
    }
} 