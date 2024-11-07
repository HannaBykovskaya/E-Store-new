<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Helpers\CartManagement;
use Livewire\Attributes\On;


class Navbar extends Component
{
	public $total_count = 0;


	public function mount()
	{
		// Получаем общее количество всех товаров
		$this->total_count = CartManagement::getCartItemsFromCookies()['total_count'];
	}

	//public function mount(){
	//	$this->total_count = count(CartManagement::getCartItemsFromCookies()['items']);
	//}


	#[On('update-cart-count')]
	public function updateCartCount($total_count) {
		$this->total_count = $total_count;
	}


	public function render()
	{
	return view('livewire.partials.navbar');
	}
}
