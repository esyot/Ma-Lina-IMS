<?php


use App\Http\Controllers\BorrowingItemsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StockController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/inventory', [InventoryController::class, 'index'])
    ->name('inventory');

Route::post('/categories/category-store', [CategoryController::class, 'store'])->name('categories.store');

Route::post('/items/item-store', [ItemController::class, 'store'])->name('items.store');

Route::get('/stocks', [StockController::class, 'index'])
    ->name('stocks');

Route::get('/stocks/add-stock-record', [StockController::class, 'addStockRecord'])
    ->name('stocks.record-add');

Route::post('/stocks/stock-store', [StockController::class, 'store'])
    ->name('stocks.store');


Route::get('/borrowing-items', [BorrowingItemsController::class, 'index'])->name('borrowing-items.index');

Route::get('items-search', [ItemController::class, 'search'])
    ->name('items.search');


Route::post('/borrowing-items/submit-slip', [BorrowingItemsController::class, 'submit'])->name('borrowing-items.create');

Route::patch('/borrowing-items/mark-as-returned', [BorrowingItemsController::class, 'markAsReturned'])
    ->name('borrowing-items.mark-as-returned');

Route::get('/stocks/stock-record/{date}', [StockController::class, 'stockRecord'])->name('stocks.record');

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');
