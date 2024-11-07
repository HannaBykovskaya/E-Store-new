<div>
	<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
		<div class="container mx-auto px-4">
		  <h1 class="text-4xl font-semibold px-4 pb-10 sm:px-6 lg:px-8 mx-auto">
			Shopping Cart
		</h1>
		  <div class="flex flex-col md:flex-row gap-4 px-4 sm:px-6 lg:px-8  mx-auto">
			<div class="md:w-3/4">
			  <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
				<table class="w-full border-collapse">
					<thead>
						<tr>
							<th colspan="2" class="text-left font-semibold border-b-2 border-gray-300 py-2">Product</th>
							<th class="text-left font-semibold border-b-2 border-gray-300 py-2">Price</th>
							<th class="text-left font-semibold border-b-2 border-gray-300 py-2">Quantity</th>
							<th class="text-left font-semibold border-b-2 border-gray-300 py-2">Total</th>
							<th class="text-left font-semibold border-b-2 border-gray-300 py-2">Remove</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($cart_items as $item)
						<tr wire:key='{{ $item['product_id'] }}'>
							<td colspan="2" class="py-4">
								<div class="flex items-center">
									<div class="image-container h-16 w-16 overflow-hidden pr-4">
										<img
											src="{{ url('storage', $item['image']) }}"
											alt="{{ $item['name'] }}"
											class="h-full w-full object-contain mr-2">
									</div>
									<span class="font-semibold px-2">{{ $item['name'] }}</span>
								</div>
							</td>
							<td class="py-4">{{ Number::currency($item['unit_amount']) }}</td>
							<td class="py-4">
								<div class="flex items-center">
									<button  wire:click='decreaseQty({{$item['product_id']}})'
									class="border rounded-md py-2 px-4 mr-2">
										-
									</button>
									<span class="text-center w-8">
										{{$item['quantity']}}
									</span>
									<button wire:click='increaseQty({{$item['product_id']}})'
									class="border rounded-md py-2 px-4 ml-2">
										+
									</button>
								</div>
							</td>
							<td class="py-4">{{ Number::currency($item['total_amount']) }}</td>
							<td>
								<button wire:click='removeItem({{$item['product_id']}})'

								class="bg-slate-300  border-slate-400 rounded-lg
									px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">
									Remove
								</button>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6" class="py-4 text-center font-semibold text-gray-500">
								Your cart is empty
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			  </div>
			</div>
			<div class="md:w-1/4">
			  <div class="bg-white rounded-lg shadow-md p-6">
				<h2 class="text-lg font-semibold mb-4">Summary</h2>
				<div class="flex justify-between mb-2">
				  <span>Subtotal</span>
					<span>
					{{ Number::currency($grand_total)}}
					</span>
				</div>
				<div class="flex justify-between mb-2">
				  <span>Taxes</span>
				  <span>{{ Number::currency(0)}}</span>
				</div>
				<div class="flex justify-between mb-2">
				  <span>Shipping</span>
				  <span>{{ Number::currency(0)}}</span>
				</div>
				<hr class="my-2">
				<div class="flex justify-between mb-2">
				  <span class="font-semibold">Grand total</span>
				  <span class="font-semibold">{{ Number::currency($grand_total)}}</span>
				</div>
				@if ($cart_items)
				<a href="/checkout" class="bg-blue-500 block
				text-center text-white py-2 px-4 rounded-lg mt-4 w-full">
					Checkout
				</a>
				@endif
			  </div>
			</div>
		  </div>
		</div>
	  </div>
</div>
