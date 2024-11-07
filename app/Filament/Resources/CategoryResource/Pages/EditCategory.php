<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;


	protected function getHeaderActions(): array
	{

		$actions = parent::getHeaderActions();


		// Если пользователь не администратор, отключаем кнопки "Сохранить" и "Удалить"
		if (!auth()->user()->is_admin) {
			foreach ($actions as $action) {
				// Если кнопка "Save", делаем её неактивной
				if ($action instanceof Actions\SaveAction) {
					$action->disabled(); // Отключаем кнопку
				}

				// Если кнопка "Delete", делаем её неактивной
				if ($action instanceof Actions\DeleteAction) {
					$action->disabled(); // Отключаем кнопку
				}
			}
		}

		return $actions;
	}
}
