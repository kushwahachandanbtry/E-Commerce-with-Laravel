<?php

use App\Livewire\Authentication\ForgotPasswordPage;
use App\Livewire\Authentication\LoginPage;
use App\Livewire\Authentication\RegisterPage;
use App\Livewire\Authentication\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePages;
use App\Livewire\MyOrdersDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailsPage;
use App\Livewire\ProductPage;
use App\Livewire\SuccessPage;
use Filament\Notifications\Auth\ResetPassword;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\OrderController;

Route::get('/success', [OrderController::class, 'success'])->name('success');
Route::get('/failure', [OrderController::class, 'failure'])->name('failure');
// Pages
Route::get('/', HomePages::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailsPage::class);


// Route::get('/success', MyOrder)


//for guest users
Route::middleware('guest')->group(function() {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class);
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
});

//for logion users
Route::middleware('auth')->group(function() {
    Route::get('/logout', function() {
        auth()->logout();
        return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order}', MyOrdersDetailPage::class)->name('my-order.show');
    Route::get('/success', SuccessPage::class);
    Route::get('/cancel', CancelPage::class);   
});
