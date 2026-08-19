<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])
    ->name('catalog.index');
Route::get('/groups/{group}', [CatalogController::class, 'showGroup'])
    ->name('groups.show');

Route::get('/products/{product}', [CatalogController::class, 'showProduct'])
    ->name('products.show');
