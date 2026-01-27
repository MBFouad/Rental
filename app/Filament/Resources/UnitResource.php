<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Area;
use App\Models\City;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Concerns\Translatable;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class UnitResource extends Resource
{
    use Translatable;

    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('Unit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Units');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'rental' => __('Rental'),
                                'sale' => __('Sale'),
                                'under_construction' => __('Under Construction'),
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('status', 'available')),

                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'available' => __('Available'),
                                'reserved' => __('Reserved'),
                                'sold' => __('Sold'),
                                'rented' => __('Rented'),
                            ])
                            ->default('available')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label(__('Featured'))
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('Description'))
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('Location'))
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('City'))
                            ->options(City::active()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('area_id', null)),

                        Forms\Components\Select::make('area_id')
                            ->label(__('Area'))
                            ->options(fn (Get $get) =>
                                $get('city_id')
                                    ? Area::where('city_id', $get('city_id'))
                                        ->active()
                                        ->ordered()
                                        ->pluck('name', 'id')
                                    : []
                            )
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get): bool => !$get('city_id')),

                        Forms\Components\TextInput::make('location')
                            ->label(__('Additional Location Details'))
                            ->maxLength(255)
                            ->placeholder(__('e.g., Near Metro Station'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('Property Details'))
                    ->schema([
                        Forms\Components\TextInput::make('bedrooms')
                            ->label(__('Bedrooms'))
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('bathrooms')
                            ->label(__('Bathrooms'))
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('area')
                            ->label(__('Area (sqm)'))
                            ->numeric()
                            ->minValue(0)
                            ->suffix(__('sqm')),
                    ])
                    ->columns(3),

                // Rental Details Section
                Forms\Components\Section::make(__('Rental Details'))
                    ->schema([
                        Forms\Components\TextInput::make('rentalDetail.monthly_rent')
                            ->label(__('Monthly Rent'))
                            ->numeric()
                            ->required()
                            ->prefix(__('SAR')),

                        Forms\Components\TextInput::make('rentalDetail.insurance_amount')
                            ->label(__('Insurance Amount'))
                            ->numeric()
                            ->prefix(__('SAR')),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'rental'),

                // Sale Details Section
                Forms\Components\Section::make(__('Sale Details'))
                    ->schema([
                        Forms\Components\TextInput::make('saleDetail.sale_price')
                            ->label(__('Sale Price'))
                            ->numeric()
                            ->required()
                            ->prefix(__('SAR')),

                        Forms\Components\Toggle::make('saleDetail.is_negotiable')
                            ->label(__('Negotiable'))
                            ->default(false),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'sale'),

                // Construction Details Section
                Forms\Components\Section::make(__('Construction Details'))
                    ->schema([
                        Forms\Components\TextInput::make('constructionDetail.total_price')
                            ->label(__('Total Price'))
                            ->numeric()
                            ->required()
                            ->prefix(__('SAR')),

                        Forms\Components\TextInput::make('constructionDetail.down_payment_amount')
                            ->label(__('Down Payment Amount'))
                            ->numeric()
                            ->prefix(__('SAR')),

                        Forms\Components\TextInput::make('constructionDetail.down_payment_percentage')
                            ->label(__('Down Payment %'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),

                        Forms\Components\DatePicker::make('constructionDetail.expected_completion')
                            ->label(__('Expected Completion')),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'under_construction'),

                // Payment Plans Section
                Forms\Components\Section::make(__('Payment Plans'))
                    ->schema([
                        Forms\Components\Repeater::make('constructionDetail.paymentPlans')
                            ->label(__('Payment Plans'))
                            ->schema([
                                Forms\Components\Select::make('duration_years')
                                    ->label(__('Duration (Years)'))
                                    ->options([
                                        3 => __('3 Years'),
                                        5 => __('5 Years'),
                                        10 => __('10 Years'),
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('monthly_installment')
                                    ->label(__('Monthly Installment'))
                                    ->numeric()
                                    ->required()
                                    ->prefix(__('SAR')),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false),
                    ])
                    ->visible(fn (Get $get): bool => $get('type') === 'under_construction'),

                // Media Section
                Forms\Components\Section::make(__('Media'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label(__('Images'))
                            ->collection('images')
                            ->multiple()
                            ->maxFiles(10)
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->reorderable(),

                        SpatieMediaLibraryFileUpload::make('videos')
                            ->label(__('Videos'))
                            ->collection('videos')
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('Image'))
                    ->collection('images')
                    ->conversion('thumb')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
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

                Tables\Columns\TextColumn::make('status')
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

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unitArea.name')
                    ->label(__('Area'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bedrooms')
                    ->label(__('Beds'))
                    ->numeric()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('area')
                    ->label(__('Area'))
                    ->suffix(' ' . __('sqm'))
                    ->numeric()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'rental' => __('Rental'),
                        'sale' => __('Sale'),
                        'under_construction' => __('Under Construction'),
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'available' => __('Available'),
                        'reserved' => __('Reserved'),
                        'sold' => __('Sold'),
                        'rented' => __('Rented'),
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('Featured')),

                Tables\Filters\SelectFilter::make('city_id')
                    ->label(__('City'))
                    ->options(City::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('area_id')
                    ->label(__('Area'))
                    ->options(Area::pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
