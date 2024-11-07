<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
	protected static string $resource = BrandResource::class;



 // Сделаем метод совместимым с сигнатурой Filament
// public static function canAccess(array $parameters = []): bool
// {
//	 // Проверяем, является ли пользователь администратором
//	 return auth()->user()->is_admin;  // Верните true, если пользователь администратор
// }


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

