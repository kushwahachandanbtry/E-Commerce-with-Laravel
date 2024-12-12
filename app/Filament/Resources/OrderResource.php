<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Products;
use Faker\Core\Number;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number as SupportNumber;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                        ->label('Customers')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                        Select::make('payment_method')
                        ->options([
                            "esewa" => "Esewa",
                            "khalti" => "Khalti",
                            "cod" => "Cash On Delivery"
                        ])
                        ->default('esewa')
                        ->required(),

                        Select::make('payment_status')
                        ->options([
                            "pending" => "Pending",
                            "paid" => "Paid",
                            "failed" => "Failed"
                        ])
                        ->default('pending')
                        ->required(),

                        ToggleButtons::make('status')
                        ->inline()
                        ->required()
                        ->default('new')
                        ->options([
                            "new" => "New",
                            "processing" => "Processing",
                            "shipped" => "Shipped",
                            "delivered" => "Delivered",
                            "cancelled" => "Cancelled"
                        ])
                        ->colors([
                            "new" => "info",
                            "processing" => "warning",
                            "shipped" => "success",
                            "delivered" => "success",
                            "cancelled" => "danger"
                        ])
                        ->icons([
                            "new" => "heroicon-m-sparkles",
                            "processing" => "heroicon-m-arrow-path",
                            "shipped" => "heroicon-m-truck",
                            "delivered" => "heroicon-m-check-badge",
                            "cancelled" => "heroicon-m-x-circle"
                        ]),

                        Select::make('currency')
                            ->options([
                                "rs" => "RS",
                                "inr" => "INR",
                                "usd" => "USD",
                                "eur" => "EUR"
                            ])
                            ->default('rs')
                            ->required(),

                            Select::make('shipping_method')
                            ->options([
                                "fedex" => "FedEx",
                                "ups" => "UPS",
                                "dhl" => "DHL",
                                "usps" => "USPS"
                            ])
                            ->default('fedex')
                            ->required(),

                        Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(fn( $state, Set $set ) => $set( 'unit_amount', Products::find($state) ?->price ?? 0))
                                    ->afterStateUpdated(fn( $state, Set $set ) => $set( 'total_amount', Products::find($state) ?->price ?? 0)),

                                TextInput::make('quantity')
                                    ->required()
                                    ->default(1)
                                    ->numeric()
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn( $state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                                TextInput::make('unit_amount')
                                    ->required()
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),

                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(3)
                            ])->columns(12),

                            Placeholder::make('grand_total_placeholder')
                                ->label('Grand Total')
                                // ->dehydrated()
                                ->content(function( Get $get, Set $set ) {
                                    $total = 0;
                                    if( !$repeaters = $get('items') ) {
                                        return $total;
                                    }

                                    foreach( $repeaters as $key => $repeater ) {
                                        $total += $get("items.{$key}.total_amount");
                                    }
                                    $set('grand_total', $total);
                                    $formateTotal = "RS " . number_format( $total, 2 );
                                    return $formateTotal;
                                }),

                                Hidden::make('grand_total')
                                    ->default(0)
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer'),

                TextColumn::make('grand_total')
                    ->numeric()
                    // ->money('rs')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('currency')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->options([
                        "new" => "New",
                        "processing" => "Processing",
                        "shipped" => "Shipped",
                        "delivered" => "Delivered",
                        "cancelled" => "Cancelled"
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('notes')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault:true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
