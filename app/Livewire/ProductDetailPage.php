<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;

use Jantinnerezo\LivewireAlert\LivewireAlert;



class ProductDetailPage extends Component
{
	use LivewireAlert;

	public $slug;
	public $quantity = 1;




	public function mount($slug)
	{
		$this->slug = $slug;
	}

	public function increaseQty()
	{
		$this->quantity++;
	}


	public function decreaseQty()
	{
		if ($this->quantity > 1) {
			$this->quantity--;
		}
	}

	//add product to Cart
	public function addToCart($product_id, $qty = 1) {
		// Добавляем продукт в корзину и получаем новое общее количество
		// Передаём $qty в хелпер, чтобы добавить товар с нужным количеством
		$total_count = CartManagement::addItemToCartWithQty($product_id, $qty);

		$this->dispatch('update-cart-count', total_count: $total_count)
			->to(Navbar::class);

		$this->alert('success', 'added to cart successfully!', [
			'position' => 'bottom-end',
			'timer' => 3000,
			'toast' => true,
			'showCloseButton' => true,
		]);

	}



	public function render()
	{
		$product = Product::where('slug', $this->slug)
			->firstOrFail();

		// Создаем заголовок
		$title = ucwords(str_replace('-', ' ', $this->slug));

		// Делаем заголовок доступным глобально
		view()->share('title', $title);

		return view('livewire.product-detail-page', compact('product'));
	}


}
