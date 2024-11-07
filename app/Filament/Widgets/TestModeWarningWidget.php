<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TestModeWarningWidget extends Widget
{
	protected static string $view = 'filament.widgets.test-mode-warning-widget';

	// Указываем, что виджет не будет "картой", а просто отображением текста
	protected static ?int $sort = -1; // Высший приоритет, чтобы отображался наверху

	protected function shouldRender(): bool
	{
		// Проверяем роль пользователя, чтобы показать виджет только не-администраторам
		return auth()->user() && !auth()->user()->is_admin;
	}
}
