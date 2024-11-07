<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use App\Models\Product;


class CartManagement {

	//add item to cart
	static public function addItemToCart($product_id)
	{
		// Извлекаем только список товаров из результата getCartItemsFromCookies
		$cart_data = self::getCartItemsFromCookies();
		$cart_items = $cart_data['items'];  // Теперь это будет только список товаров

		$qty = 1;
		$existing_item = null;

		// Проверка наличия товара в корзине
		foreach ($cart_items as $key => $item) {
			// Проверяем, чтобы ключ 'product_id' существовал, чтобы избежать ошибки
			if (isset($item['product_id']) && $item['product_id'] == $product_id) {
				$existing_item = $key;
				break;
			}
		}

		// Если товар уже есть в корзине, увеличиваем его количество
		if ($existing_item !== null) {
			$cart_items[$existing_item]['quantity'] += $qty;
			$cart_items[$existing_item]['total_amount'] =
				$cart_items[$existing_item]['quantity'] *
				$cart_items[$existing_item]['unit_amount'];
		} else {
			// Если товара нет в корзине, добавляем его с количеством 1
			$product = Product::where('id', $product_id)
				->first(['id', 'name', 'price', 'images']);

			if ($product) {
				$cart_items[] = [
					'product_id' => $product_id,
					'name' => $product->name,
					'image' => $product->images[0],
					'quantity' => $qty,
					'unit_amount' => $product->price,
					'total_amount' => $product->price,
				];
			}
		}

		// Сохраняем обновлённую корзину в cookies
		self::addCartItemsToCookies($cart_items);

		// Подсчитываем общее количество всех товаров в корзине
		$total_count = array_sum(array_column($cart_items, 'quantity'));

		return $total_count;
	}












	//static public function addItemToCart($product_id)
	//{
	//	$cart_items = self::getCartItemsFromCookies();

	//	$qty=1;

	//	$existing_item = null;

	//	foreach($cart_items as $key => $item) {
	//		if( $item['product_id'] == $product_id) {
	//			$existing_item = $key;
	//			break;
	//		}
	//	}

	//	if($existing_item !== null){
	//		$cart_items[$existing_item]['quantity']++;
	//		$cart_items[$existing_item]['total_amount'] =
	//		 $cart_items[$existing_item]['quantity'] *
	//		 $cart_items[$existing_item]['unit_amount'];
	//	} else {
	//		$product = Product::where('id', $product_id)
	//		->first(['id', 'name', 'price', 'images']);
	//		if ($product) {
	//			$cart_items[] = [
	//				'product_id' => $product_id,
	//				'name' => $product->name,
	//				'image' => $product->images[0],
	//				'quantity' => $qty,
	//				'unit_amount' => $product->price,
	//				'total_amount' => $product->price,
	//			];
	//		}
	//	}

	//	self::addCartItemsToCookies($cart_items);

	//	return count($cart_items);
	//}


	//add item to cart with qty

// Add item to cart with specific quantity
static public function addItemToCartWithQty($product_id, $qty = 1)
{
    $cart_data = self::getCartItemsFromCookies();
    $cart_items = $cart_data['items'];

    $existing_item = null;

    foreach ($cart_items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $existing_item = $key;
            break;
        }
    }

    if ($existing_item !== null) {
        // Увеличиваем количество, если товар уже в корзине
        $cart_items[$existing_item]['quantity'] += $qty;
        $cart_items[$existing_item]['total_amount'] =
            $cart_items[$existing_item]['quantity'] *
            $cart_items[$existing_item]['unit_amount'];
    } else {
        // Если товар не в корзине, добавляем его
        $product = Product::where('id', $product_id)
            ->first(['id', 'name', 'price', 'images']);
        if ($product) {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'image' => $product->images[0],
                'quantity' => $qty,
                'unit_amount' => $product->price,
                'total_amount' => $product->price * $qty,
            ];
        }
    }

    // Сохраняем обновлённую корзину в cookies
    self::addCartItemsToCookies($cart_items);

    // Возвращаем общее количество всех товаров в корзине
    $total_count = array_sum(array_column($cart_items, 'quantity'));

    return $total_count;
}


//	static public function addItemToCartWithQty($product_id, $qty=1)
//	{
//	$cart_items = self::getCartItemsFromCookies()['items'];

//	$existing_item = null;

//	foreach($cart_items as $key => $item) {
//		if( $item['product_id'] == $product_id) {
//			$existing_item = $key;
//			break;
//		}
//	}

//	if($existing_item !== null){
//		$cart_items[$existing_item]['quantity'] = $qty;
//		$cart_items[$existing_item]['total_amount'] =
//		 $cart_items[$existing_item]['quantity'] *
//		 $cart_items[$existing_item]['unit_amount'];
//	} else {
//		$product = Product::where('id', $product_id)
//		->first(['id', 'name', 'price', 'images']);
//		if ($product) {
//			$cart_items[] = [
//				'product_id' => $product_id,
//				'name' => $product->name,
//				'image' => $product->images[0],
//				'quantity' => $qty,
//				'unit_amount' => $product->price,
//				'total_amount' => $product->price,
//			];
//		}
//	}

//	self::addCartItemsToCookies($cart_items);

//	return count($cart_items);
//}


	//remove item from cart
// Удаление одного экземпляра товара из корзины
static public function removeCartItem($product_id)
{
	$cart_data = self::getCartItemsFromCookies();
	$cart_items = $cart_data['items'];

	foreach ($cart_items as $key => $item) {
		if ($item['product_id'] == $product_id) {
			// Проверяем количество товара
			if ($item['quantity'] > 1) {
				// Уменьшаем количество на 1
				$cart_items[$key]['quantity']--;
				// Пересчитываем общую сумму для этого товара
				$cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
			} else {
				// Удаляем товар, если его количество стало 0
				unset($cart_items[$key]);
			}
			break;
		}
	}

	// Обновляем корзину в cookies
	self::addCartItemsToCookies($cart_items);

	return $cart_items;
}


	//static public function removeCartItem($product_id)
	//{
	//	$cart_items = self::getCartItemsFromCookies()['items'];

	//	foreach($cart_items as $key => $item) {
	//		if ($item['product_id'] == $product_id) {
	//			unset($cart_items[$key]);
	//		}
	//	}

	//	self::addCartItemsToCookies($cart_items);

	//	return $cart_items;
	//}


	//add cart items to cookie
	static public function addCartItemsToCookies($cart_items)
	{
		Cookie::queue('cart_items', json_encode($cart_items),
		60*24*30);
	}

	//clear cart items from cookie
	static public function clearCartItemsFromCookies()
	{
		Cookie::queue(Cookie::forget('cart_items'));
	}


	//get all cart items from cookie
	static public function getCartItemsFromCookies()
	{
		$cart_items = json_decode(Cookie::get('cart_items'), true);

		// Проверка на случай, если cookie пустой
		if (!$cart_items) {
			$cart_items = [];
		}

		// Подсчет общего количества всех товаров в корзине
		$total_count = array_sum(array_column($cart_items, 'quantity'));

		return [
			'items' => $cart_items,
			'total_count' => $total_count,
		];
	}
	//static public function getCartItemsFromCookies()
	//{

	//	$cart_items = json_decode(Cookie::get('cart_items'), true);
	//	if(!$cart_items) {
	//		$cart_items = [];
	//	}
	//	return $cart_items;
	//}


	//increment item quantity
	static public function incrementCartItemQuantity($product_id)
	{
		$cart_items = self::getCartItemsFromCookies()['items'];

		foreach($cart_items as $key => $item) {
			if($item['product_id'] == $product_id) {
				$cart_items[$key]['quantity']++;
				$cart_items[$key]['total_amount'] =
					$cart_items[$key]['quantity'] *
					$cart_items[$key]['unit_amount'];
			}
		}
		self::addCartItemsToCookies($cart_items);

		return $cart_items;
	}


	//decrement item quantity
	static public function decrementCartItemQuantity($product_id)
	{
		$cart_items = self::getCartItemsFromCookies()['items'];

		foreach($cart_items as $key => $item) {
			if($item['product_id'] == $product_id) {
				if($cart_items[$key]['quantity'] > 1) {
					$cart_items[$key]['quantity']--;
					$cart_items[$key]['total_amount'] =
					$cart_items[$key]['quantity'] *
					$cart_items[$key]['unit_amount'];
				}
			}
		}
		self::addCartItemsToCookies($cart_items);

		return $cart_items;
	}

	//calculate grand total
	static public function calculateGrandTotal($items)
	{
		return array_sum(array_column($items, 'total_amount'));
	}



}
