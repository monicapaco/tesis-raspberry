<?php

use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SaleController;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return app(CategoryController::class)->create();
    }
    return view('auth/login');
});

Route::resource('almacen/categoria',CategoryController::class);
Route::resource('almacen/articulo',ItemController::class);

Route::patch('almacen/categoria/{id}/activate', [CategoryController::class, 'activate'])
    ->name('categoria.activate');

Route::resource('compras/proveedor',ProviderController::class);
Route::patch('compras/proveedor/{id}/activate', [ProviderController::class, 'activate'])
    ->name('proveedor.activate');
Route::resource('compras/ingreso',IncomeController::class);

Route::resource('ventas/cliente',ClientController::class);
Route::patch('ventas/cliente/{id}/activate',
    [ClientController::class, 'activate'])
    ->name('cliente.activate');
Route::resource('ventas/transporte',CarrierController::class);
Route::patch('ventas/transporte/{id}/activate', [CarrierController::class, 'activate'])
    ->name('transporte.activate');

Route::resource('ventas/venta',SaleController::class);
Route::patch('venta/{id}/mark-paid', [SaleController::class, 'markPaid'])
    ->name('venta.markPaid');

Route::get("/venta/imprimir/{id}", [SaleController::class, "imprimir"])->name(
    "venta.imprimir",
);

Route::get("/articulo/imprimir", [ItemController::class, "imprimir"])->name(
    "articulo.imprimir",
);
Auth::routes();
