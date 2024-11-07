<div>
	{{-- Hero setion start --}}
	<div class="start-page
	w-full  py-10
	px-4 sm:px-6 lg:px-8 mx-auto"
	style="background-image: linear-gradient(rgba(0, 0, 0, 0.062),rgba(0, 0, 0, 0.329), rgba(0, 0, 0, 0.795)),
	url('/images/slide1.jpg');
		height: 80vh;
	 background-size: cover;
	background-repeat: no-repeat;
	color: white;
	display: flex;
	align-items: center;
	text-align: left;

	"
	>
		<div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
		  <!-- Grid -->
		  <div class="grid md:grid-cols-2 gap-4 md:gap-8 xl:gap-20 md:items-center">
			<div>
			  <h1 class="block text-3xl font-bold text-white
			   sm:text-4xl lg:text-6xl lg:leading-tight dark:text-white">
				All what You need is
				<br>
				<span class="text-blue-600 bg-white px-2 rounded-lg">
					E-Store
				</span>
			</h1>
			  <p class="mt-3 text-lg  text-white dark:text-gray-400">
				Purchase wide varities of electronics products like Smartphones,
				 Laptops, Smartwatches, Television and many more.
				</p>

			  <!-- Buttons -->
			  <div class="mt-7 grid gap-3 w-full sm:inline-flex">
				<a class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm
				 font-semibold rounded-lg border border-transparent bg-blue-600 text-white
				  hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none
				   dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
				    href="/products">
				  Get started
				  <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m9 18 6-6-6-6" />
				  </svg>
				</a>
			  </div>
			  <!-- End Buttons -->


			</div>
			<!-- End Col -->

			<!-- End Col -->
		  </div>
		  <!-- End Grid -->
		</div>
	  </div>
{{-- Hero setion end --}}



{{-- Brand Section start --}}
	<section class="py-20">
		<div class="max-w-lg mx-auto">
			<div class="text-center ">
				<div class="relative flex flex-col items-center">
					<h1 class="text-4xl font-bold dark:text-gray-200">
						Browse Popular
						<span class="text-blue-500"> Brands
						</span>
					</h1>
				<div class="flex w-40 mt-2 mb-6 overflow-hidden rounded">
					<div class="flex-1 h-2 bg-blue-200">
					</div>
					<div class="flex-1 h-2 bg-blue-400">
					</div>
					<div class="flex-1 h-2 bg-blue-600">
					</div>
				</div>
			</div>
			</div>
		</div>

		<div class="justify-center max-w-6xl px-4 py-4 mx-auto lg:py-0">
			<div class="grid grid-cols-1 gap-6 lg:grid-cols-4 md:grid-cols-2">
				@foreach($brands as $brand)
				<div class="bg-white rounded-lg shadow-md dark:bg-gray-800 h-40
				flex justify-center items-center"
				wire:key="{{$brand->id}}">
						<div class="flex justify-center items-center">
							<a href="/products?selected_brands[0]={{$brand->id}}">
								<img src="{{ url('storage', $brand->image)}}"
									alt="{{$brand->name}}"
									class="object-contain rounded-t-lg
									h-[8.375rem] w-[8.375rem]">
							</a>
						</div>
				</div>
				@endforeach

			</div>
		</div>
	  </section>
{{-- Brand Section end --}}

{{-- Categories Section start --}}
<div class="bg-white py-20">
	<div class="max-w-xl mx-auto">
	  <div class="text-center ">
		<div class="relative flex flex-col items-center">
		  <h1 class="text-4xl font-bold dark:text-gray-200">
			Browse <span class="text-blue-500">
				Categories
			</span> </h1>
		  <div class="flex w-40 mt-2 mb-6 overflow-hidden rounded">
			<div class="flex-1 h-2 bg-blue-200">
			</div>
			<div class="flex-1 h-2 bg-blue-400">
			</div>
			<div class="flex-1 h-2 bg-blue-600">
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<div class="max-w-[100rem] px-4 sm:px-6 lg:px-8 mx-auto">
		<div class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6">

			@foreach($categories as $category)
			<a class="group flex flex-col bg-white
			border shadow-sm rounded-xl
				hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800
				ark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
				href="products?selected_categories[0]={{$category->id}}"
				wire:key="{{$category->id}}">
				<div class="p-4 md:p-5">
					<div class="flex justify-between items-center">
					<div class="flex items-center">
						<img class="h-[10.375rem] w-full"
						src="{{ url('storage', $category->image)}}" alt="{{ $category->name}}">
						<div class="ms-3">
							<h3 class="group-hover:text-blue-600
							 font-semibold text-gray-800 text-lg
							  dark:group-hover:text-gray-400
							   dark:text-gray-200">
								{{ $category->name}}
							</h3>
						</div>
					</div>
					<div class="ps-3">
						<svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="m9 18 6-6-6-6" />
						</svg>
					</div>
					</div>
				</div>
			</a>
			@endforeach
		</div>
	</div>
</div>
{{-- Categories Section end --}}


</div>
