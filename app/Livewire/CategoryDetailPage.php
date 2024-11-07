<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\WithPagination;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;

use Jantinnerezo\LivewireAlert\LivewireAlert;


class CategoryDetailPage extends Component
{
	use LivewireAlert;
	use WithPagination;

	public $slug;
	public $quantity = 1;

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

	public function mount($slug)
	{
		$this->slug = $slug;
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

		$brands = Brand::query()
			->where('is_active', 1)
			->get();

		$category = Category::query()
			->where('slug', $this->slug)
			->firstOrFail();

			$productsQuery = Product::query()
			->where('is_active', 1)
			->where('category_id', $category->id);

			if (!empty($this->selected_brands)) {
				$productsQuery->whereIn('brand_id', $this->selected_brands);
			}

			if ($this->featured) {
				$productsQuery->where('is_featured', 1);
			}


			if ($this->on_sale) {
				$productsQuery->where('on_sale', 1);
			}

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


			$title = $category->name;
			// Делаем заголовок доступным глобально
			view()->share('title', $title);
		return view('livewire.category-detail-page', compact('products', 'category', 'brands'));
	}


}
