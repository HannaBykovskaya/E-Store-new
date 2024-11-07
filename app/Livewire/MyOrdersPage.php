<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

use App\Models\Product;
use App\Models\Order;
use Livewire\WithPagination;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;



#[Title('My Orders')]
class MyOrdersPage extends Component
{

	use WithPagination;


	public function render()
	{
		$my_orders = Order::where('user_id', auth()->id())
			->latest()
			->paginate(5);
		return view('livewire.my-orders-page', [
			'orders' => $my_orders,
		]);
	}
}
