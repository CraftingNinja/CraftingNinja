<?php

use App\Http\Controllers\Api\ItemsController;
use App\Http\Controllers\Api\RecipesController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function() {
    Route::post('items/search', [ItemsController::class, 'search'])->name('items.search');
    Route::get('items/{id}', [ItemsController::class, 'show'])->name('items.show');
    Route::post('items', [ItemsController::class, 'many'])->name('items.many');

    // Route::get('notebooks', [NotebookdivisionController::class, 'index']);

    // Route::post('recipes/search', [RecipesController::class, 'search'])->name('recipes.search');
    // Route::get('recipes/{classes}/notebooks/{notebookIds}', [RecipeController::class, 'byNotebook']);
    // Route::get('recipes/tree/{recipeIds}', [RecipeController::class, 'tree']);

    // Route::get('category/{id}', [CategoryController::class, 'show']);
    // Route::get('recipe/{id}', [RecipeController::class, 'show']);
    // Route::get('job/types/{type}', [JobController::class, 'types'])->name('job.types');

    // Route::post('leve/search', [LeveController::class, 'search']);
});
