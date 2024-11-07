<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use App\Filament\Widgets\TestModeWarningWidget;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

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
