<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationBadgeTooltip = 'New inquiries';

    public static function getNavigationLabel(): string
    {
        return __('Inquiries');
    }

    public static function getModelLabel(): string
    {
        return __('Inquiry');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Inquiries');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'new')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Contact Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->disabled(),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->disabled(),

                        Forms\Components\Textarea::make('message')
                            ->label(__('Message'))
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('Property'))
                    ->schema([
                        Forms\Components\Select::make('unit_id')
                            ->label(__('Unit'))
                            ->relationship('unit', 'title')
                            ->disabled(),
                    ]),

                Forms\Components\Section::make(__('Status & Notes'))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'new' => __('New'),
                                'contacted' => __('Contacted'),
                                'closed' => __('Closed'),
                            ])
                            ->required(),

                        Forms\Components\DateTimePicker::make('contacted_at')
                            ->label(__('Contacted At')),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label(__('Admin Notes'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit.title')
                    ->label(__('Unit'))
                    ->searchable()
                    ->limit(30)
                    ->url(fn ($record) => route('units.show', $record->unit->slug))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('Phone number copied')),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => __('New'),
                        'contacted' => __('Contacted'),
                        'closed' => __('Closed'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Received'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'new' => __('New'),
                        'contacted' => __('Contacted'),
                        'closed' => __('Closed'),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markContacted')
                    ->label(__('Mark Contacted'))
                    ->icon('heroicon-o-phone')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Inquiry $record) {
                        $record->update([
                            'status' => 'contacted',
                            'contacted_at' => now(),
                        ]);
                        Notification::make()
                            ->title(__('Marked as contacted'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Inquiry $record) => $record->status === 'new'),

                Tables\Actions\Action::make('markClosed')
                    ->label(__('Close'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Inquiry $record) {
                        $record->update(['status' => 'closed']);
                        Notification::make()
                            ->title(__('Inquiry closed'))
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Inquiry $record) => $record->status !== 'closed'),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markContacted')
                        ->label(__('Mark as Contacted'))
                        ->icon('heroicon-o-phone')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($record) => $record->update([
                                'status' => 'contacted',
                                'contacted_at' => now(),
                            ]));
                            Notification::make()
                                ->title(__('Inquiries marked as contacted'))
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListInquiries::route('/'),
            'view' => Pages\ViewInquiry::route('/{record}'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
