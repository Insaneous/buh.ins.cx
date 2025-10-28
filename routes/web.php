<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
  if (Auth::check()) {
      return redirect()->route('transactions');
  }

  $demoTransactions = [
      ['id' => 1, 'type' => 'income', 'category' => ['name' => 'Заработная плата'], 'amount' => 80000, 'date' => '2025-10-01', 'comment' => 'Основная работа'],
      ['id' => 2, 'type' => 'expense', 'category' => ['name' => 'Продукты питания'], 'amount' => 12000, 'date' => '2025-10-03', 'comment' => 'Еженедельные покупки'],
      ['id' => 3, 'type' => 'expense', 'category' => ['name' => 'Транспорт'], 'amount' => 2500, 'date' => '2025-10-05', 'comment' => 'Проездной'],
      ['id' => 4, 'type' => 'income', 'category' => ['name' => 'Иные доходы'], 'amount' => 5000, 'date' => '2025-10-08', 'comment' => 'Фриланс проект'],
      ['id' => 5, 'type' => 'expense', 'category' => ['name' => 'Развлечения'], 'amount' => 3000, 'date' => '2025-10-10', 'comment' => 'Кино и кафе'],
  ];

  return Inertia::render('Welcome', [
      'demoTransactions' => $demoTransactions,
  ]);
})->name('welcome');

Route::get('/transactions', fn () => Inertia::render('Transactions'))
    ->middleware(['auth', 'verified'])
    ->name('transactions');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.users.index'))->name('admin');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('categories', CategoryController::class)->names('admin.categories');
});

require __DIR__.'/auth.php';
