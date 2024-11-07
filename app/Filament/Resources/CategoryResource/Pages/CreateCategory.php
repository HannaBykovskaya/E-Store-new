<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

		// Сделаем метод совместимым с сигнатурой Filament
		public static function canAccess(array $parameters = []): bool
		{
			// Проверяем, является ли пользователь администратором
			return auth()->user()->is_admin;  // Верните true, если пользователь администратор
		}

}
