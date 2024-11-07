<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use App\Filament\Widgets\TestModeWarningWidget;



class BrandResource extends Resource
{
	protected static ?string $model = Brand::class;

	protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

	protected static ?string $recordTitleAttribute = 'name';


	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Section::make([
					Grid::make()
						->schema([
							TextInput::make('name')
								->required()
								->disabled(!auth()->user()->is_admin)
								->maxlength(255)
								->live(onBlur:true)
								->afterStateUpdated(fn(string $operation,
									$state, Set $set) => $operation
								=== 'create' ? $set('slug', Str::slug($state)): null),

							TextInput::make('slug')
								->required()
								->disabled()
								->maxlength(255)
								->dehydrated()
								->unique(Brand::class, 'slug',
									ignoreRecord: true),
						]),

						FileUpload::make('image')
							->disabled(!auth()->user()->is_admin)
							->image()
							->directory('brands'),

						Toggle::make('is_active')
							->disabled(!auth()->user()->is_admin)
							->required()
							->default(true),
					]),

			]);

	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\TextColumn::make('name')
					->searchable(),

				Tables\Columns\ImageColumn::make('image')
				->disabled(!auth()->user()->is_admin),

				Tables\Columns\TextColumn::make('slug')
					->searchable(),

				Tables\Columns\IconColumn::make('is_active')
				->disabled(!auth()->user()->is_admin)
					->boolean(),

				Tables\Columns\TextColumn::make('created_at')
				->disabled(!auth()->user()->is_admin)
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),

				Tables\Columns\TextColumn::make('updated_at')
				->disabled(!auth()->user()->is_admin)
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
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

					//Tables\Actions\EditAction::make(),
					//Tables\Actions\DeleteAction::make(),
				]),
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
			//
		];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListBrands::route('/'),
			'create' => Pages\CreateBrand::route('/create'),
			'edit' => Pages\EditBrand::route('/{record}/edit'),
		];
	}
}
