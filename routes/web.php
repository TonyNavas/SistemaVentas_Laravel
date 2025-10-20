<?php

use App\Livewire\Home\Inicio;
use App\Livewire\Sale\SaleList;
use App\Livewire\Sale\SaleShow;
use App\Livewire\User\UserComponent;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\RoleComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\Mesas\MesasComponent;
use App\Http\Controllers\PdfController;
use App\Livewire\User\UserShowComponent;
use App\Livewire\Kitchen\KitchenComponent;
use App\Livewire\Mesas\MesasShowComponent;
use App\Livewire\Product\ProductComponent;
use App\Livewire\Category\CategoryComponent;
use App\Livewire\Product\ProductShowComponent;
use App\Livewire\Category\CategoryShowComponent;
use App\Livewire\Client\MesaClientComponent;
use App\Livewire\Kitchen\OrdersReady;
use Spatie\Permission\Middleware\PermissionMiddleware;

Auth::routes(['register' => false]);

Route::get('/mesacliente/{token}', MesaClientComponent::class)->name('mesa.cliente');

Route::group(['middleware' => ['auth']], function () {
    // Inicio

    Route::middleware([PermissionMiddleware::class . ':ver-inicio'])->group(function () {
        Route::get('/', Inicio::class)->name('inicio');
    });

    // Categorias
    Route::middleware([PermissionMiddleware::class . ':ver-categorias'])->group(function () {
        Route::get('/categorias', CategoryComponent::class)->name('category.index');
        Route::get('/categorias/{category}', CategoryShowComponent::class)->name('category.show');
    });
    // Ventas
    Route::middleware([PermissionMiddleware::class . ':ver-ventas'])->group(function () {
        Route::get('/ventas', SaleList::class)->name('sales.list');
        Route::get('/ventas/{sale}', SaleShow::class)->name('sales.show');
    });
    // productos
    Route::middleware([PermissionMiddleware::class . ':ver-productos'])->group(function () {
        Route::get('/productos', ProductComponent::class)->name('product.index');
        Route::get('/productos/{product}', ProductShowComponent::class)->name('product.show');
    });
    // usuarios
    Route::middleware([PermissionMiddleware::class . ':ver-usuarios'])->group(function () {
        Route::get('/usuarios', UserComponent::class)->name('user.index');
        Route::get('/usuarios/{user}', UserShowComponent::class)->name('user.show');
    });
    // roles
    Route::middleware([PermissionMiddleware::class . ':ver-roles'])->group(function () {
        Route::get('/roles', RoleComponent::class)->name('admin.roles.index');
    });
    // mesas
    Route::middleware([PermissionMiddleware::class . ':ver-mesa'])->group(function () {
        Route::get('/mesas', MesasComponent::class)->name('tables.index');
        Route::get('/mesa/{table}', MesasShowComponent::class)->name('mesas.show');
    });
    // facturas
    Route::middleware([PermissionMiddleware::class . ':imprimir-ticket'])->group(function () {
        Route::get('/ventas/invoice/{sale}', [PdfController::class, 'invoice'])->name('sales.invoice');
    });
    // cocina
    Route::middleware([PermissionMiddleware::class . ':ver-cocina'])->group(function () {
        Route::get('/cocina', KitchenComponent::class)->name('kitchen.index');
    });
});
Route::get('/ordenes-listas', OrdersReady::class)->name('ordersready.list');
