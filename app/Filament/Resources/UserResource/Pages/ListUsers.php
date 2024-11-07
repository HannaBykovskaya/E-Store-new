<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TestModeWarningWidget;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

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
