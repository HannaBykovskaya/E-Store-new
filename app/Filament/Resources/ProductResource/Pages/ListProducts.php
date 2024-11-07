<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TestModeWarningWidget;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

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
}
