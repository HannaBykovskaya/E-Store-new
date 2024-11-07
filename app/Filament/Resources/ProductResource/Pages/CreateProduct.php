<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

		// Сделаем метод совместимым с сигнатурой Filament
		public static function canAccess(array $parameters = []): bool
		{
			// Проверяем, является ли пользователь администратором
			return auth()->user()->is_admin;  // Верните true, если пользователь администратор
		}

}
