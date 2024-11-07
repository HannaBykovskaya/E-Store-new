<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{


	protected static string $resource = BrandResource::class;

	// Сделаем метод совместимым с сигнатурой Filament
	public static function canAccess(array $parameters = []): bool
	{
		// Проверяем, является ли пользователь администратором
		return auth()->user()->is_admin;  // Верните true, если пользователь администратор
	}

}
