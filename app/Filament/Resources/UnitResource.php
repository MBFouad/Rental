<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages\CreateUnit;
use App\Filament\Resources\UnitResource\Pages\EditUnit;
use App\Filament\Resources\UnitResource\Pages\ListUnits;
use App\Filament\Resources\UnitResource\Pages\ViewUnit;
use App\Models\Area;
use App\Models\City;
use App\Models\Unit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class UnitResource extends Resource
{
    use Translatable;

    protected static ?string $model = Unit::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('Unit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Units');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Basic Information'))
                    ->schema([
                        Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'rental' => __('Rental'),
                                'sale' => __('Sale'),
                                'under_construction' => __('Under Construction'),
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('status', 'available')),

                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))
                            ),

                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'available' => __('Available'),
                                'reserved' => __('Reserved'),
                                'sold' => __('Sold'),
                                'rented' => __('Rented'),
                            ])
                            ->default('available')
                            ->required(),

                        Toggle::make('is_featured')
                            ->label(__('Featured'))
                            ->default(false),
                    ])
                    ->columns(2),

                Section::make(__('Description'))
                    ->schema([
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Location'))
                    ->schema([
                        Select::make('city_id')
                            ->label(__('City'))
                            ->options(City::active()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('area_id', null)),

                        Select::make('area_id')
                            ->label(__('District'))
                            ->options(fn (Get $get) => $get('city_id')
                                    ? Area::where('city_id', $get('city_id'))
                                        ->active()
                                        ->ordered()
                                        ->pluck('name', 'id')
                                    : []
                            )
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get): bool => ! $get('city_id')),

                        TextInput::make('location')
                            ->label(__('Additional Location Details'))
                            ->maxLength(255)
                            ->placeholder(__('e.g., Near Metro Station'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Property Details'))
                    ->schema([
                        TextInput::make('bedrooms')
                            ->label(__('Bedrooms'))
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('bathrooms')
                            ->label(__('Bathrooms'))
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('area')
                            ->label(__('Area (sqm)'))
                            ->numeric()
                            ->minValue(0)
                            ->suffix(__('sqm')),
                    ])
                    ->columns(3),

                // Rental Details Section
                Section::make(__('Rental Details'))
                    ->schema([
                        TextInput::make('rentalDetail.monthly_rent')
                            ->label(__('Monthly Rent'))
                            ->numeric()
                            ->required()
                            ->prefix(currency_symbol()),

                        TextInput::make('rentalDetail.insurance_amount')
                            ->label(__('Insurance Amount'))
                            ->numeric()
                            ->prefix(currency_symbol()),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'rental'),

                // Sale Details Section
                Section::make(__('Sale Details'))
                    ->schema([
                        TextInput::make('saleDetail.sale_price')
                            ->label(__('Sale Price'))
                            ->numeric()
                            ->required()
                            ->prefix(currency_symbol()),

                        Toggle::make('saleDetail.is_negotiable')
                            ->label(__('Negotiable'))
                            ->default(false),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'sale'),

                // Construction Details Section
                Section::make(__('Construction Details'))
                    ->schema([
                        TextInput::make('constructionDetail.total_price')
                            ->label(__('Total Price'))
                            ->numeric()
                            ->required()
                            ->prefix(currency_symbol()),

                        TextInput::make('constructionDetail.down_payment_amount')
                            ->label(__('Down Payment Amount'))
                            ->numeric()
                            ->prefix(currency_symbol()),

                        TextInput::make('constructionDetail.down_payment_percentage')
                            ->label(__('Down Payment %'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),

                        DatePicker::make('constructionDetail.expected_completion')
                            ->label(__('Expected Completion')),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'under_construction'),

                // Payment Plans Section
                Section::make(__('Payment Plans'))
                    ->schema([
                        Repeater::make('constructionDetail.paymentPlans')
                            ->label(__('Payment Plans'))
                            ->schema([
                                Select::make('duration_years')
                                    ->label(__('Duration (Years)'))
                                    ->options([
                                        3 => __('3 Years'),
                                        5 => __('5 Years'),
                                        10 => __('10 Years'),
                                    ])
                                    ->required(),

                                TextInput::make('monthly_installment')
                                    ->label(__('Monthly Installment'))
                                    ->numeric()
                                    ->required()
                                    ->prefix(currency_symbol()),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false),
                    ])
                    ->visible(fn (Get $get): bool => $get('type') === 'under_construction'),

                // Media Section
                Section::make(__('Media'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label(__('Images'))
                            ->collection('images')
                            ->disk('public')
                            ->multiple()
                            ->maxFiles(10)
                            ->image()
                            ->conversion('card')
                            ->reorderable(),

                        SpatieMediaLibraryFileUpload::make('videos')
                            ->label(__('Videos'))
                            ->collection('videos')
                            ->disk('public')
                            ->multiple()
                            ->maxFiles(3)
                            ->acceptedFileTypes(['video/mp4', 'video/webm']),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('Image'))
                    ->collection('images')
                    ->conversion('thumb')
                    ->circular(),

                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rental' => __('Rental'),
                        'sale' => __('Sale'),
                        'under_construction' => __('Under Construction'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'rental' => 'info',
                        'sale' => 'success',
                        'under_construction' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => __('Available'),
                        'reserved' => __('Reserved'),
                        'sold' => __('Sold'),
                        'rented' => __('Rented'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'reserved' => 'warning',
                        'sold' => 'danger',
                        'rented' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('city.name')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('unitArea.name')
                    ->label(__('District'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('bedrooms')
                    ->label(__('Beds'))
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('area')
                    ->label(__('Area'))
                    ->suffix(' '.__('sqm'))
                    ->numeric()
                    ->toggleable(),

                IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'rental' => __('Rental'),
                        'sale' => __('Sale'),
                        'under_construction' => __('Under Construction'),
                    ]),

                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'available' => __('Available'),
                        'reserved' => __('Reserved'),
                        'sold' => __('Sold'),
                        'rented' => __('Rented'),
                    ]),

                TernaryFilter::make('is_featured')
                    ->label(__('Featured')),

                SelectFilter::make('city_id')
                    ->label(__('City'))
                    ->options(City::pluck('name', 'id')),

                SelectFilter::make('area_id')
                    ->label(__('District'))
                    ->options(Area::pluck('name', 'id')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnits::route('/'),
            'create' => CreateUnit::route('/create'),
            'view' => ViewUnit::route('/{record}'),
            'edit' => EditUnit::route('/{record}/edit'),
        ];
    }
}
