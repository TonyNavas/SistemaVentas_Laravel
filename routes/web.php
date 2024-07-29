<?php

use App\Livewire\Home\Inicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Category\CategoryComponent;
use App\Livewire\Category\CategoryShowComponent;
use App\Livewire\Mesas\MesasComponent;
use App\Livewire\Mesas\MesasShowComponent;
use App\Livewire\Product\ProductComponent;
use App\Livewire\Product\ProductShowComponent;
use App\Livewire\User\UserComponent;
use App\Livewire\User\UserShowComponent;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes(['register' => false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function () {
    // Inicio
    Route::get('/', Inicio::class)->name('inicio');

    // Categorias
    Route::get('categorias', CategoryComponent::class)->name('category.index');
    Route::get('categorias/{category}', CategoryShowComponent::class)->name('category.show');

    // Productos
    Route::get('productos', ProductComponent::class)->name('product.index');
    Route::get('productos/{product}', ProductShowComponent::class)->name('product.show');

    // Usuarios
    Route::get('usuarios', UserComponent::class)->name('user.index');
    Route::get('usuarios/{user}', UserShowComponent::class)->name('user.show');

    Route::get('mesas', MesasComponent::class)->name('tables.index');
    Route::get('mesa/{table}', MesasShowComponent::class)->name('mesas.show');
});


