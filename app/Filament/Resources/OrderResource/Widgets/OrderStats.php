<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
	protected function getStats(): array
	{
		return [
			Stat::make('Users', User::query()
			->count()),
			Stat::make('New Orders', Order::query()
			->where('status', 'new')->count()),
			Stat::make('Order Processing', Order::query()
			->where('status', 'processing')->count()),
			Stat::make('Order Shipped', Order::query()
			->where('status', 'shipped')->count()),
			Stat::make('Order Delivered', Order::query()
			->where('status', 'delivered')->count()),
			Stat::make('Average Price',
				Number::currency(Order::query()
					->avg('grand_total') ?? 0, 'USD')
			),
		];
	}
}
