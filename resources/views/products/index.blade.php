@extends('layouts.app')

@section('content')
<h1> Liste des Produits</h1>

<div class="card mb-4 shadow ">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 ">
                <label for="categoryFilter" class="form-label">Catégorie :</label>
                <select id="categoryFilter" class="form-select" onchange="filterProducts()">
                    <option value="">Toutes les catégories</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="priceSort" class="form-label">Trier par prix :</label>
                <select id="priceSort" class="form-select" onchange="filterProducts()">
                    <option value="">Sans tri</option>
                    <option value="asc">Prix croissant</option>
                    <option value="desc">Prix décroissant</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button onclick="resetFilters()" class="btn btn-secondary"> Réinitialiser</button>
                <a href="/products/create" class="btn border border-2 border-dark ms-3 ">
                    ➕ Nouveau Produit
                </a>
            </div>
        </div>
    </div>
</div>

<div id="products-container">
    <p>Chargement...</p>
</div>

<script>
let allCategories = [];

fetch('/api/categories')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            allCategories = data.data;
            populateCategories();
        }
    });

loadProducts();

function populateCategories() {
    const select = document.getElementById('categoryFilter');
    allCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

function loadProducts() {
    const categoryId = document.getElementById('categoryFilter').value;
    const sortPrice = document.getElementById('priceSort').value;
    
    let url = '/api/products?';
    if (categoryId) url += `category_id=${categoryId}&`;
    if (sortPrice) url += `sort_by_price=${sortPrice}&`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProducts(data.data);
            } else {
                document.getElementById('products-container').innerHTML = '<p class="text-danger">Erreur lors du chargement</p>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('products-container').innerHTML = '<p class="text-danger">Impossible de charger les produits</p>';
        });
}

function filterProducts() {
    loadProducts();
}

function resetFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('priceSort').value = '';
    loadProducts();
}

function displayProducts(products) {
    let html = '<div class="row">';
    
    if (products.length === 0) {
        html = '<div class="alert alert-info"> Aucun produit trouvé avec ces filtres</div>';
    } else {
        products.forEach(product => {
            const categories = product.categories.map(cat => `<span class="badge bg-secondary me-1">${cat.name}</span>`).join('');
            
           
            const imageHtml = product.image 
                ? `<img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">`
                : `<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                     <span class="text-muted">🖼️ Pas d'image</span>
                   </div>`;
            
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow">
                        ${imageHtml}
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">${product.description}</p>
                            <p class="text-success fw-bold fs-5">${product.price} Dh</p>
                            ${categories ? `<div class="mb-2">${categories}</div>` : ''}
                            <small class="text-muted">Créé le ${new Date(product.created_at).toLocaleDateString('fr-FR')}</small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    html += '</div>';
    
    const countHtml = `<div class="alert alert-primary"> ${products.length} produit(s) trouvé(s)</div>`;
    
    document.getElementById('products-container').innerHTML = countHtml + html;
}
</script>
@endsection