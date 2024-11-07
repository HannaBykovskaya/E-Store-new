<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Resources\Components\Tab;
use App\Filament\Widgets\TestModeWarningWidget;

class ListOrders extends ListRecords
{
	protected static string $resource = OrderResource::class;

	protected function getHeaderWidgets(): array
	{
		return [
			TestModeWarningWidget::class,
		];
	}

    protected function getHeaderActions(): array
    {
		if (auth()->user()->is_admin) {
			return [
				Actions\CreateAction::make(),
			];
		}

		return [];
    }



	public function getTabs(): array
	{
		return [
			null => Tab::make('All'),
			'new' => Tab::make()
			->query(fn($query) => $query->where('status', 'new')),
			'processing' => Tab::make()
			->query(fn($query) => $query->where('status', 'processing')),
			'shipped' => Tab::make()
			->query(fn($query) => $query->where('status', 'shipped')),
			'delivered' => Tab::make()
			->query(fn($query) => $query->where('status', 'delivered')),
			'cancelled' => Tab::make()
			->query(fn($query) => $query->where('status', 'cancelled')),
		];
	}

	//protected function getFooterWidgets(): array
	//{
	//	return [
	//		OrderStats::class,
	//	];
	//}
}
