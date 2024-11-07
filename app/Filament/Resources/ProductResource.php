<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;


class ProductResource extends Resource
{
	protected static ?string $model = Product::class;

	protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Group::make()
					->schema([
						Section::make('Product Information')
							->schema([

								TextInput::make('name')
								->disabled(!auth()->user()->is_admin)
								->required()
								->maxLength(255)
								->live(onBlur:true)
								->afterStateUpdated(fn(string $operation,
									$state, Set $set) => $operation
								=== 'create' ? $set('slug', Str::slug($state)): null),

								TextInput::make('slug')
								->required()
								->maxLength(255)
								->disabled()
								->dehydrated()
								->unique(Product::class, 'slug',
									ignoreRecord: true),


								MarkdownEditor::make('description')
								->disabled(!auth()->user()->is_admin)
								->columnSpanFull()
								->fileAttachmentsDirectory('products')
							])->columns(2),

							Section::make('Images')
							->disabled(!auth()->user()->is_admin)
								->schema([
									FileUpload::make('images')
										->multiple()
										->directory('products')
										->maxFiles(5)
										->reorderable(),
								])

					])->columnSpan(2),

					Group::make()->schema([
						Section::make('Price')
						->disabled(!auth()->user()->is_admin)
								->schema([
									TextInput::make('price')
									->required()
									->numeric()
									->prefix('$'),
								]),

						Section::make('Associations')
						->disabled(!auth()->user()->is_admin)
							->schema([
								Select::make('category_id')
									->required()
									->searchable()
									->preload()
									->relationship('category', 'name'),

								Select::make('brand_id')
									->required()
									->searchable()
									->preload()
									->relationship('brand', 'name'),
								]),

						Section::make('Status')
						->disabled(!auth()->user()->is_admin)
							->schema([
								Toggle::make('in_stock')
								->required()
								->default(true),

								Toggle::make('is_active')
								->required()
								->default(true),

								Toggle::make('is_featured')
								->required(),

								Toggle::make('on_sale')
								->required(),
							]),
						])->columnSpan(1)
			])->columns(3);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				TextColumn::make('name')
					->searchable(),

				ImageColumn::make('image')
				->disabled(!auth()->user()->is_admin)
					->getStateUsing(function ($record) {
						// Проверяем, существует ли первый элемент массива images и возвращаем его URL
						return isset($record->images[0]) ? asset('storage/' . $record->images[0]) : asset('storage/products/default-image.jpg');
					}),

				TextColumn::make('category.name')
					->sortable(),

				TextColumn::make('brand.name')
					->sortable(),

				TextColumn::make('price')
					->money('USD')
					->sortable(),

				IconColumn::make('is_featured')
				->boolean(),

				IconColumn::make('is_active')
					->boolean(),

				IconColumn::make('in_stock')
					->boolean(),

				IconColumn::make('on_sale')
					->boolean(),

				TextColumn::make('created_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),

				TextColumn::make('updated_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
				SelectFilter::make('category')
				->relationship('category', 'name'),

				SelectFilter::make('brand')
				->relationship('brand', 'name'),
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
			//
		];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListProducts::route('/'),
			'create' => Pages\CreateProduct::route('/create'),
			'edit' => Pages\EditProduct::route('/{record}/edit'),
		];
	}
}
