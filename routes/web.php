<?php

use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;

use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;


use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;
use App\Livewire\CategoryDetailPage;

use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);


Route::get('/categories', CategoriesPage::class);
Route::get('/category/{slug}', CategoryDetailPage::class);

//Products
Route::get('/products', ProductsPage::class);
Route::get('/product/{slug}', ProductDetailPage::class);


Route::get('/cart', CartPage::class);



Route::middleware('guest')->group(function() {
	//Auth
	Route::get('/login', LoginPage::class)->name('login');
	Route::get('/register', RegisterPage::class);
	Route::get('/forgot', ForgotPasswordPage::class)
	->name('password.request');
	Route::get('/reset/{token}', ResetPasswordPage::class)
	->name('password.reset');

});


Route::middleware('auth')->group(function() {
	//Cart
	Route::get('/logout', function() {
		auth()->logout();
		return redirect('/');
	});
	Route::get('/my-orders', MyOrdersPage::class);
	Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)
	->name('my-orders.show');
	Route::get('/checkout', CheckoutPage::class);

	Route::get('/success', SuccessPage::class)
	->name('success');
	Route::get('/cancel', CancelPage::class)
	->name('cancel');

});
