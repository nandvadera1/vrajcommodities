<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HtmlController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'can:admin']], function () {
    /* For Admin */
    Route::get('/admin/dataTable', [AdminController::class, 'getDataTable']);
    Route::resource('admin', AdminController::class);

    /* For Users */
    Route::get('/users/dataTable', [UserController::class, 'getDataTable']);
    Route::resource('users', UserController::class);

    /* For Categories */
    Route::get('/category/dataTable', [CategoryController::class, 'getDataTable']);
    Route::resource('category', CategoryController::class);

    /* For Items */
    Route::get('item', [ItemController::class, 'index'])->name('item.index');
    Route::get('item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('item', [ItemController::class, 'store'])->name('item.store');
    Route::get('item/{id}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('item/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::delete('item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
    Route::get('item/deleteOld', [ItemController::class, 'deleteItems']);
});


Route::get('privacy-policy', [HtmlController::class, 'privacyPolicy']);
Route::get('contact-us', [HtmlController::class, 'contactUs']);
