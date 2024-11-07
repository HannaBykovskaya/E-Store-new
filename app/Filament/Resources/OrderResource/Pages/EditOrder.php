<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

	public static function canAccess(array $parameters = []): bool
	{
		// Проверяем, является ли пользователь администратором
		return auth()->user()->is_admin;  // Верните true, если пользователь администратор
	}

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


		//return [
        //    Actions\DeleteAction::make(),
        //];
    }
}
