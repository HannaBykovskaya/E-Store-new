<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;


#[Title('My Cart')]
class CartPage extends Component
{
	use LivewireAlert;

	public $cart_items = [];
	public $grand_total;


	public function mount()
	{
		$cart_data = CartManagement::getCartItemsFromCookies();
		$this->cart_items = $cart_data['items'];
		$this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);

		// Отправляем общее количество товаров в навбар
		$this->dispatch('update-cart-count', total_count: $cart_data['total_count']);
	}

	//public function mount()
	//{
	//	$this->cart_items = CartManagement::getCartItemsFromCookies();
	//	$this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
	//	return view('livewire.cart-page');
	//}

	//add product to Cart
	public function addToCart($product_id) {
		// Добавляем продукт в корзину и получаем новое общее количество
		$total_count = CartManagement::addItemToCart($product_id);

		$this->dispatch('update-cart-count', total_count: $total_count)
		->to(Navbar::class);

		$this->alert('success', 'added to cart successfully!', [
			'position' => 'bottom-end',
			'timer' => 3000,
			'toast' => true,
			'showCloseButton' => true,
		]);

	}


	public function removeItem($product_id)
	{
		$this->cart_items = CartManagement::removeCartItem($product_id);
		$this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
		$this->dispatch('update-cart-count', total_count: count($this->cart_items))
			->to(Navbar::class);
	}




// Увеличение количества товара

	public function decreaseQty($product_id){
		$this->cart_items = CartManagement::decrementCartItemQuantity($product_id);
		$this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
	}


	public function increaseQty($product_id){
		$this->cart_items = CartManagement::incrementCartItemQuantity($product_id);
		$this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
	}

	public function render()
	{
		return view('livewire.cart-page');
	}
}
