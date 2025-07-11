@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0">➕ Créer un nouveau produit</h4>
            </div>
            <div class="card-body">
                <div id="alert-container"></div>

                <form id="productForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du produit *</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Prix (Dh) *</label>
                        <input type="number" class="form-control" id="price" step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="imageFile" class="form-label">Image (optionnel)</label>
                        <input type="file" class="form-control" id="imageFile" accept="image/*">
                        <div class="form-text">Formats acceptés : JPG, PNG, GIF (max 2MB)</div>
                        
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="" alt="Aperçu" style="max-width: 200px; height: auto;" class="border rounded">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="categories" class="form-label">Catégories (optionnel)</label>
                        <select class="form-select" id="categories" multiple>
                        </select>
                        <div class="form-text">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs catégories</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/products" class="btn btn-secondary">
                            ⬅️ Retour à la liste
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            ✅ Créer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let categories = [];

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});

document.getElementById('imageFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

function loadCategories() {
    fetch('/api/categories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                categories = data.data;
                populateCategories();
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des catégories:', error);
        });
}

function populateCategories() {
    const select = document.getElementById('categories');
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createProduct();
});

function createProduct() {
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Création en cours...';
    
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('price', document.getElementById('price').value);
    
    const imageFile = document.getElementById('imageFile').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const selectedCategories = Array.from(document.getElementById('categories').selectedOptions)
        .map(option => option.value);
    selectedCategories.forEach(categoryId => {
        formData.append('category_ids[]', categoryId);
    });
    
    fetch('/api/products', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '✅ Produit créé avec succès !');
            document.getElementById('productForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            setTimeout(() => {
                window.location.href = '/products';
            }, 2000);
        } else {
            showAlert('danger', '❌ Erreur lors de la création du produit.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', '❌ Erreur de connexion. Veuillez réessayer.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}
</script>
@endsection