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
/*
Route::get('prueba',function(){
    
    $categoria =Category::all();
    return $categoria;

});*/
Route::resource('almacen/categoria',CategoryController::class);
Route::resource('almacen/articulo',ItemController::class);
Route::resource('ventas/cliente',ClientController::class);
Route::resource('compras/proveedor',ProviderController::class);
Route::resource('compras/ingreso',IncomeController::class);
Route::resource('ventas/transporte',CarrierController::class);
Route::resource('ventas/venta',SaleController::class);
Route::get("/venta/imprimir/{id}", [SaleController::class, "imprimir"])->name(
    "venta.imprimir",
);
Route::get("/articulo/imprimir", [ItemController::class, "imprimir"])->name(
    "articulo.imprimir",
);
Auth::routes();



#Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
