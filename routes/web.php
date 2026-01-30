<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminStockController;
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

// ==================== ГЛАВНАЯ СТРАНИЦА ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================== АУТЕНТИФИКАЦИЯ ====================
Route::middleware('guest')->group(function () {
    // Вход
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Регистрация
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Восстановление пароля
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    
    // Сброс пароля
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Выход (только для авторизованных)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== ПУБЛИЧНЫЕ МАРШРУТЫ ====================
// Каталог и товары
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
//Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{id}/reviews', [ProductController::class, 'reviews'])->name('product.reviews');

// Категории
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/category/{code}', [CategoryController::class, 'show'])->name('category.show');

// Статические страницы
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/delivery', function () {
    return view('pages.delivery');
})->name('delivery');

Route::get('/payment', function () {
    return view('pages.payment');
})->name('payment');

Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

// ==================== КОРЗИНА ====================
// Основные маршруты корзины
Route::get('/cart', [CartController::class, 'index'])->name('basket');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('basket-add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ==================== АВТОРИЗОВАННЫЕ ПОЛЬЗОВАТЕЛИ ====================
Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Адреса доставки
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::post('/profile/addresses', [ProfileController::class, 'addAddress'])->name('profile.addresses.add');
    Route::put('/profile/addresses/{index}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{index}', [ProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
    
    // Отзывы
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');
    
    // Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    
    // Оформление заказа
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/order', [CheckoutController::class, 'store'])->name('order.store'); // ИЗМЕНИЛОСЬ
});

// ==================== AJAX API МАРШРУТЫ ====================
Route::prefix('api')->group(function () {
    // Поиск товаров
    Route::get('/products/search', [ProductController::class, 'search'])->name('api.products.search');
    
    // Корзина AJAX
    Route::post('/cart/add', [CartController::class, 'addAjax'])->name('api.cart.add');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('api.cart.count');
    Route::put('/cart/update/{product}', [CartController::class, 'updateAjax'])->name('api.cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'removeAjax'])->name('api.cart.remove');
    
    // Проверка наличия товара
    Route::get('/product/{id}/stock', [ProductController::class, 'stock'])->name('api.product.stock');
    
    // Список категорий для фильтров
    Route::get('/categories/list', [CategoryController::class, 'list'])->name('api.categories.list');
});

// ==================== АДМИН-ПАНЕЛЬ ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Дашборд
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Товары
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Категории
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Заказы
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Пользователи
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show'); // ← ДОБАВЬТЕ ЭТУ СТРОКУ
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // После маршрутов пользователей добавьте:
Route::put('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
Route::put('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');

    // Отзывы
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::put('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::put('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    
    // Склад
    Route::get('/stocks', [AdminStockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/low', [AdminStockController::class, 'lowStock'])->name('stocks.low');
    Route::get('/stocks/{product}/edit', [AdminStockController::class, 'edit'])->name('stocks.edit');
    Route::put('/stocks/{product}', [AdminStockController::class, 'update'])->name('stocks.update');
    Route::post('/stocks/bulk-update', [AdminStockController::class, 'bulkUpdate'])->name('stocks.bulk-update');
});

// ==================== FALLBACK ====================
Route::fallback(function () {
    return view('errors.404');
});