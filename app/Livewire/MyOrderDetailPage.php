<?php

namespace App\Livewire;

use Livewire\Component;


use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Livewire\WithPagination;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;



#[Title('Order Detail')]
class MyOrderDetailPage extends Component
{
	public $order_id;

	public function mount($order_id){
		$this->order_id = $order_id;
	}

	public function render()
	{
		$order_items = OrderItem::with('product')
			->where('order_id', $this->order_id)
			->get();

		$address = Address::where('order_id', $this->order_id)
		->first();

		$order = Order::where('id', $this->order_id)
		->first();

		return view('livewire.my-order-detail-page',
			compact('order_items', 'address', 'order'));
	}
}
