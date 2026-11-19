<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReportController;

Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::post('/catalog/fertilizers', [CatalogController::class, 'storeFertilizer'])->name('catalog.fertilizers.store');
Route::post('/catalog/plots', [CatalogController::class, 'storePlot'])->name('catalog.plots.store');
Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
