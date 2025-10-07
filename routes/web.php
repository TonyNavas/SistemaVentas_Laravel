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
use App\Livewire\Admin\PermissionComponent;
use App\Livewire\Category\CategoryComponent;
use App\Livewire\Product\ProductShowComponent;
use App\Livewire\Category\CategoryShowComponent;
use App\Livewire\Client\MesaClientComponent;
use Spatie\Permission\Middleware\PermissionMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes(['register' => false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function () {
    // Inicio
    Route::get('/', Inicio::class)->name('inicio');

    // Categorias

    Route::middleware([PermissionMiddleware::class.':read-categories'])->group(function () {
        Route::get('/categorias', CategoryComponent::class)->name('category.index');
        Route::get('/categorias/{category}', CategoryShowComponent::class)->name('category.show');
    });

    // Ventas
    Route::get('/ventas', SaleList::class)->name('sales.list');
    Route::get('/ventas/{sale}', SaleShow::class)->name('sales.show');

    // Productos
    Route::get('/productos', ProductComponent::class)->name('product.index');
    Route::get('/productos/{product}', ProductShowComponent::class)->name('product.show');

    // Usuarios
    Route::get('/usuarios', UserComponent::class)->name('user.index');
    Route::get('/usuarios/{user}', UserShowComponent::class)->name('user.show');

    // Roles
    Route::get('/roles', RoleComponent::class)->name('admin.roles.index');

    // Cabañas/Mesas
    Route::get('/mesas', MesasComponent::class)->name('tables.index');
    Route::get('/mesa/{table}', MesasShowComponent::class)->name('mesas.show');

    // Facturas/Tikets
    Route::get('/ventas/invoice/{sale}', [PdfController::class, 'invoice'])->name('sales.invoice');

    // Cocina
    Route::get('/cocina', KitchenComponent::class)->name('kitchen.index');
});

Route::get('/mesacliente/{token}', MesaClientComponent::class)->name('mesa.cliente');

