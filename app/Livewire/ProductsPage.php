<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\WithPagination;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;


use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('Buy a new electronic gadgets, tv, laptops, headphones, smartwatches')]
class ProductsPage extends Component
{
	use WithPagination;

	use LivewireAlert;

	#[URL]
	public $selected_categories = [];

	#[URL]
	public $selected_brands = [];

	#[URL]
	public $featured;


	#[URL]
	public $on_sale;


	#[URL]
	public $price_range = 2100;

	#[URL]
	public $sort = 'latest';



	//add product to Cart
	public function addToCart($product_id)
{
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


	//public function addToCart($product_id) {

	//	$total_count = CartManagement::addItemToCart($product_id);


	//	$this->dispatch('update-cart-count', total_count: $total_count)
	//	->to(Navbar::class);

	//	$this->alert('success', 'added to cart successfully!', [
	//		'position' => 'bottom-end',
	//		'timer' => 3000,
	//		'toast' => true,
	//		'showCloseButton' => true,
	//	]);

	//}

	public function render()
	{
		$productsQuery = Product::query()->where('is_active', 1);

		$brands = Brand::query()
			->where('is_active', 1)
			->get();

		$categories = category::query()
			->where('is_active', 1)
			->get();

			// Фильтрация по категориям
			if (!empty($this->selected_categories)) {
				$productsQuery->whereIn('category_id', $this->selected_categories);
			}

			// Фильтрация по брендам
			if (!empty($this->selected_brands)) {
				$productsQuery->whereIn('brand_id', $this->selected_brands);
			}

			// Фильтрация по признаку "рекомендуемые товары"
			if ($this->featured) {
				$productsQuery->where('is_featured', 1);
			}

			// Фильтрация по признаку "товары со скидкой"
			if ($this->on_sale) {
				$productsQuery->where('on_sale', 1);
			}

			// Фильтрация по диапазону цены
			if ($this->price_range) {
				$productsQuery->where('price', '<=', $this->price_range);
			}


			if ($this->sort == 'latest') {
				$productsQuery->latest();
			}


			if ($this->sort == 'price') {
				$productsQuery->orderBy('price');
			}


			// Выполняем пагинацию по результатам фильтрации
			$products = $productsQuery->paginate(9);

			// Получение брендов и категорий
			$brands = Brand::query()->where('is_active', 1)->get();
			$categories = Category::query()->where('is_active', 1)->get();

		return view('livewire.products-page',
					compact('products', 'brands', 'categories'));
	}
}
