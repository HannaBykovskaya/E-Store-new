<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

		// Сделаем метод совместимым с сигнатурой Filament
		public static function canAccess(array $parameters = []): bool
		{
			// Проверяем, является ли пользователь администратором
			return auth()->user()->is_admin;  // Верните true, если пользователь администратор
		}

}
