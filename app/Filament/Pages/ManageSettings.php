<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.manage-settings';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string
    {
        return __('Settings');
    }

    public function mount(): void
    {
        $this->form->fill([
            'website_name_ar' => Setting::get('website_name')['ar'] ?? '',
            'website_name_en' => Setting::get('website_name')['en'] ?? '',
            'website_logo' => Setting::get('website_logo'),
            'phone_numbers' => array_map(fn ($p) => ['phone' => $p], Setting::getArray('phone_numbers')),
            'emails' => array_map(fn ($e) => ['email' => $e], Setting::getArray('emails')),
            'whatsapp_numbers' => array_map(fn ($w) => ['whatsapp' => $w], Setting::getArray('whatsapp_numbers')),
            'admin_email' => Setting::get('admin_email'),
            'facebook_url' => Setting::get('facebook_url'),
            'instagram_url' => Setting::get('instagram_url'),
            'twitter_url' => Setting::get('twitter_url'),
            'currency' => Setting::get('currency', 'EGP'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make(__('Branding'))
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make(__('Website Identity'))
                                    ->schema([
                                        TextInput::make('website_name_ar')
                                            ->label(__('Website Name (Arabic)'))
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('website_name_en')
                                            ->label(__('Website Name (English)'))
                                            ->required()
                                            ->maxLength(255),

                                        FileUpload::make('website_logo')
                                            ->label(__('Website Logo'))
                                            ->image()
                                            ->directory('settings')
                                            ->disk('public')
                                            ->imageResizeMode('contain')
                                            ->imageCropAspectRatio(null)
                                            ->maxSize(2048),

                                        Select::make('currency')
                                            ->label(__('Currency'))
                                            ->options([
                                                'EGP' => 'ج.م (EGP)',
                                                'SAR' => 'ر.س (SAR)',
                                                'USD' => '$ (USD)',
                                            ])
                                            ->default('EGP')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make(__('Contact Information'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make(__('Phone Numbers'))
                                    ->schema([
                                        Repeater::make('phone_numbers')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('phone')
                                                    ->label(__('Phone Number'))
                                                    ->tel()
                                                    ->required(),
                                            ])
                                            ->defaultItems(1)
                                            ->addActionLabel(__('Add Phone Number'))
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),

                                Section::make(__('Email Addresses'))
                                    ->schema([
                                        Repeater::make('emails')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('email')
                                                    ->label(__('Email Address'))
                                                    ->email()
                                                    ->required(),
                                            ])
                                            ->defaultItems(1)
                                            ->addActionLabel(__('Add Email'))
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),

                                Section::make(__('WhatsApp Numbers'))
                                    ->schema([
                                        Repeater::make('whatsapp_numbers')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('whatsapp')
                                                    ->label(__('WhatsApp Number'))
                                                    ->tel()
                                                    ->required(),
                                            ])
                                            ->defaultItems(1)
                                            ->addActionLabel(__('Add WhatsApp Number'))
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),
                            ]),

                        Tab::make(__('Admin Settings'))
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Section::make(__('Notifications'))
                                    ->schema([
                                        TextInput::make('admin_email')
                                            ->label(__('Admin Email'))
                                            ->helperText(__('Email address to receive inquiry notifications'))
                                            ->email()
                                            ->required(),
                                    ]),
                            ]),

                        Tab::make(__('Social Media'))
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make(__('Social Links'))
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label(__('Facebook URL'))
                                            ->url()
                                            ->prefix('https://'),

                                        TextInput::make('instagram_url')
                                            ->label(__('Instagram URL'))
                                            ->url()
                                            ->prefix('https://'),

                                        TextInput::make('twitter_url')
                                            ->label(__('Twitter/X URL'))
                                            ->url()
                                            ->prefix('https://'),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save website name as JSON
        Setting::set('website_name', [
            'ar' => $data['website_name_ar'],
            'en' => $data['website_name_en'],
        ], 'json');

        // Save logo
        Setting::set('website_logo', $data['website_logo'], 'file');

        // Save phone numbers as array
        $phones = array_map(fn ($item) => $item['phone'], $data['phone_numbers'] ?? []);
        Setting::set('phone_numbers', $phones, 'array');

        // Save emails as array
        $emails = array_map(fn ($item) => $item['email'], $data['emails'] ?? []);
        Setting::set('emails', $emails, 'array');

        // Save WhatsApp numbers as array
        $whatsapps = array_map(fn ($item) => $item['whatsapp'], $data['whatsapp_numbers'] ?? []);
        Setting::set('whatsapp_numbers', $whatsapps, 'array');

        // Save admin email
        Setting::set('admin_email', $data['admin_email']);

        // Save social media URLs
        Setting::set('facebook_url', $data['facebook_url']);
        Setting::set('instagram_url', $data['instagram_url']);
        Setting::set('twitter_url', $data['twitter_url']);

        // Save currency
        Setting::set('currency', $data['currency']);

        Notification::make()
            ->title(__('Settings saved successfully'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->submit('save'),
        ];
    }
}
