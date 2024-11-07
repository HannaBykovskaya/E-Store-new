<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TestModeWarningWidget;


class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;


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
