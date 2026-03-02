<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages\EditInquiry;
use App\Filament\Resources\InquiryResource\Pages\ListInquiries;
use App\Filament\Resources\InquiryResource\Pages\ViewInquiry;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 4;

    protected static \Illuminate\Contracts\Support\Htmlable|string|null $navigationBadgeTooltip = 'New inquiries';

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Contact Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->disabled(),

                        TextInput::make('phone')
                            ->label(__('Phone'))
                            ->disabled(),

                        TextInput::make('email')
                            ->label(__('Email'))
                            ->disabled(),

                        Textarea::make('message')
                            ->label(__('Message'))
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Property'))
                    ->schema([
                        Select::make('unit_id')
                            ->label(__('Unit'))
                            ->relationship('unit', 'title')
                            ->disabled(),
                    ]),

                Section::make(__('Status & Notes'))
                    ->schema([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'new' => __('New'),
                                'contacted' => __('Contacted'),
                                'closed' => __('Closed'),
                            ])
                            ->required(),

                        DateTimePicker::make('contacted_at')
                            ->label(__('Contacted At')),

                        Textarea::make('admin_notes')
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
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('unit.title')
                    ->label(__('Unit'))
                    ->searchable()
                    ->limit(30)
                    ->url(fn ($record) => route('units.show', $record->unit->slug))
                    ->openUrlInNewTab(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('Phone number copied')),

                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
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

                TextColumn::make('created_at')
                    ->label(__('Received'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'new' => __('New'),
                        'contacted' => __('Contacted'),
                        'closed' => __('Closed'),
                    ]),
            ])
            ->recordActions([
                Action::make('markContacted')
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

                Action::make('markClosed')
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

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markContacted')
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
            'index' => ListInquiries::route('/'),
            'view' => ViewInquiry::route('/{record}'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
