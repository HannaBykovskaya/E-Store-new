<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
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
use Filament\Forms\Get;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Number;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\SelectColumn;


class OrderResource extends Resource
{
	protected static ?string $model = Order::class;

	protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Group::make()
				->schema([
					Section::make('Order Information')
						->schema([

							Select::make('user_id')
							->disabled(!auth()->user()->is_admin)
							->label('Customer')
							->relationship('user', 'name')
							->searchable()
							->preload()
							->required(),

							Select::make('payment_method')
							->disabled(!auth()->user()->is_admin)
								->options([
									'stripe' => 'Stripe',
									'cod' => 'Cash on Delivery'
								])
								->required(),


							Select::make('payment_status')
							->disabled(!auth()->user()->is_admin)
								->options([
									'pending' => 'Pending',
									'paid' => 'Paid',
									'failed' => 'Failed'
								])
								->default('pending')
								->required(),

							ToggleButtons::make('status')
							->disabled(!auth()->user()->is_admin)
								->inline()
								->options([
									'new' => 'New',
									'processing' => 'Processing',
									'shipped' => 'Shipped',
									'delivered' => 'Delivered',
									'cancelled' => 'Cancelled'
								])
								->colors([
									'new' => 'info',
									'processing' => 'warning',
									'shipped' => 'info',
									'delivered' => 'success',
									'cancelled' => 'danger'
								])
								->icons([
									'new' => 'heroicon-m-sparkles',
									'processing' => 'heroicon-m-arrow-path',
									'shipped' => 'heroicon-m-truck',
									'delivered' => 'heroicon-m-check-badge',
									'cancelled' => 'heroicon-m-x-circle'
								])
								->default('new')
								->required(),

							Select::make('currency')
							->disabled(!auth()->user()->is_admin)
								->options([
									'usd' => 'USD',
									'byn' => 'BYN',
									'eur' => 'EUR'
								])
								->default('usd')
								->required(),

							Select::make('shipping_method')
							->disabled(!auth()->user()->is_admin)
								->options([
									'fedex' => 'Fedex',
									'ups' => 'UPS',
									'dhl' => 'DHL'
								])
								->dehydrated()
								->required(),

							Textarea::make('notes')
							->disabled(!auth()->user()->is_admin)
								->columnSpanFull(),

						])->columns(2),

						Section::make('Order Items')
						->schema([

							Repeater::make('items')
							->relationship()
							->schema([

								Select::make('product_id')
								->disabled(!auth()->user()->is_admin)
									->relationship('product', 'name')
									->searchable()
									->preload()
									->required()
									->distinct()
									->disableOptionsWhenSelectedInSiblingRepeaterItems()
									->columnSpan(4)
									->reactive()
									->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount',
									Product::find($state)?->price ?? 0))
									->afterStateUpdated(fn ($state, Set $set) => $set('total_amount',
									Product::find($state)?->price ?? 0)),



								TextInput::make('quantity')
								->disabled(!auth()->user()->is_admin)
									->numeric()
									->required()
									->default(1)
									->minValue(1)
									->columnSpan(2)
									->reactive()
									->afterStateUpdated(
										fn ($state, Set $set, Get $get) => $set('total_amount',
										$state*$get('unit_amount')
									)),

								TextInput::make('unit_amount')
								->disabled(!auth()->user()->is_admin)
									->numeric()
									->required()
									->disabled()
									->dehydrated()
									->columnSpan(3),

								TextInput::make('total_amount')
								->disabled(!auth()->user()->is_admin)
									->numeric()
									->required()
									->dehydrated()
									->columnSpan(3),


							])->columns(12),

							Placeholder::make('grand_total_placeholder')
							->disabled(!auth()->user()->is_admin)
								->label('Grand Total')
								->content(function(Get $get, Set $set){
										$total = 0;
										if(!$repeaters = $get('items')){
											return $total;
										}

										foreach($repeaters as $key => $repeater){
											$total += $get("items.{$key}.total_amount");
										}
										$set('grand_total', $total);
										return Number::currency($total, 'USD');
									}),

							Hidden::make('grand_total')
							->disabled(!auth()->user()->is_admin)
								->default(0),
						])

					])->columnSpanFull(),

				]);

	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				TextColumn::make('user.name')
				->disabled(!auth()->user()->is_admin)
					->label('Customer')
					->sortable()
					->searchable(),

				TextColumn::make('grand_total')
				->disabled(!auth()->user()->is_admin)
					->numeric()
					->sortable()
					->money('USD'),

				TextColumn::make('payment_method')
				->disabled(!auth()->user()->is_admin)
					->searchable()
					->sortable(),

				TextColumn::make('payment_status')
				->disabled(!auth()->user()->is_admin)
					->searchable()
					->sortable(),


				TextColumn::make('currency')
				->disabled(!auth()->user()->is_admin)
					->searchable()
					->sortable(),

				TextColumn::make('shipping_method')
				->disabled(!auth()->user()->is_admin)
					->sortable()
					->searchable(),

				SelectColumn::make('status')
				->disabled(!auth()->user()->is_admin)
					->options([
						'new' => 'New',
						'processing' => 'Processing',
						'shipped' => 'Shipped',
						'delivered' => 'Delivered',
						'cancelled' => 'Cancelled'
					])
					->searchable()
					->sortable(),

				TextColumn::make('created_at')
				->disabled(!auth()->user()->is_admin)
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),


				TextColumn::make('updated_at')
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
					Tables\Actions\ViewAction::make(),
					// Ограничим доступ к редактированию и удалению
					Tables\Actions\EditAction::make()
						->visible(fn () => auth()->user()->is_admin) // Доступно только админам
						->disabled(fn () => !auth()->user()->is_admin), // Отключено для не-админов
					Tables\Actions\DeleteAction::make()
						->visible(fn () => auth()->user()->is_admin) // Доступно только админам
						->disabled(fn () => !auth()->user()->is_admin), // Отключено для не-админов
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
			AddressRelationManager::class
		];
	}

	public static function getNavigationBadge(): ?string
	{
		return static::getModel()::count();
	}

	public static function getNavigationBadgeColor(): string|array|null
	{
		return static::getModel()::count() > 10 ? 'danger' : 'success';
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListOrders::route('/'),
			'create' => Pages\CreateOrder::route('/create'),
			'edit' => Pages\EditOrder::route('/{record}/edit'),

		];
	}
}
