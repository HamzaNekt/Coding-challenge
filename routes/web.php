<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('products.index');
});

Route::get('/products', function () {
    return view('products.index');
});

Route::get('/products/create', function () {
    return view('products.create');
})->name('products.create');