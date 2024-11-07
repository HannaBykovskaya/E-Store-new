<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

		// Сделаем метод совместимым с сигнатурой Filament
		public static function canAccess(array $parameters = []): bool
		{
			// Проверяем, является ли пользователь администратором
			return auth()->user()->is_admin;  // Верните true, если пользователь администратор
		}

}
