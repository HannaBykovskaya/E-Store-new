<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DateTimePicker;

use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;


class UserResource extends Resource
{
	protected static ?string $model = User::class;

	protected static ?string $navigationIcon = 'heroicon-o-user-group';

	protected static ?string $recordTitleAttribute = 'name';


	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Forms\Components\TextInput::make('name')
				->disabled(!auth()->user()->is_admin)
				->required(),

				Forms\Components\TextInput::make('email')
				->disabled(!auth()->user()->is_admin)
				->label('Email address')
				->email()
				->maxlength(255)
				->unique(ignoreRecord: true)
				->required(),

				DateTimePicker::make('email_verified_at')
				->disabled(!auth()->user()->is_admin)
				->label('Email verified at')
				->default(now())
				->required()
				->native(false),


				Forms\Components\TextInput::make('password')
				->disabled(!auth()->user()->is_admin)
				->label('Password')
				->dehydrated(fn($state) => filled($state))
				->required(fn($livewire):bool => $livewire instanceof Pages\CreateUser),

			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\TextColumn::make('name')
				->searchable(),

				Tables\Columns\TextColumn::make('email')
				->searchable(),

				Tables\Columns\TextColumn::make('email_verified_at')
				->dateTime()
				->sortable(),


				Tables\Columns\TextColumn::make('created_at')
				->dateTime()
				->sortable(),

			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\ActionGroup::make([
					Tables\Actions\ViewAction::make()
					->visible(fn () => true)
					->action(fn ($record) => $record->view()),

					 // Ограничим доступ к редактированию и удалению
					 Tables\Actions\EditAction::make()
					 ->visible(fn () => auth()->user()->is_admin)
					 ->disabled(fn () => !auth()->user()->is_admin),

				 Tables\Actions\DeleteAction::make()
					 ->visible(fn () => auth()->user()->is_admin) // Доступно только админам
					 ->disabled(fn () => !auth()->user()->is_admin), // Отключено для не-админов
				])

			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make()
					->visible(fn () => auth()->user()->is_admin) // Только для админов
					->disabled(fn () => !auth()->user()->is_admin),
				]),
			]);
	}

	public static function getRelations(): array
	{
		return [
			OrdersRelationManager::class
		];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListUsers::route('/'),
			'create' => Pages\CreateUser::route('/create'),
			'edit' => Pages\EditUser::route('/{record}/edit'),
		];
	}
}
