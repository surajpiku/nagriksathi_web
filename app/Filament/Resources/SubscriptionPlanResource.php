<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $navigationLabel = 'Plans';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Basic Info')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Sathi Plus'),

                    Forms\Components\TextInput::make('hindi_name')
                        ->label('Hindi Name')
                        ->maxLength(100)
                        ->placeholder('à¤¸à¤¾à¤¥à¥€ à¤ªà¥à¤²à¤¸'),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->placeholder('sathi-plus')
                        ->helperText('Lowercase, hyphens only. Used as subscription_tier value.'),

                    Forms\Components\Select::make('type')
                        ->required()
                        ->options([
                            'citizen'   => 'Citizen (Nagrik)',
                            'csc_agent' => 'Seva Mitra (CSC Agent)',
                        ]),
                ]),
            ]),

            Section::make('Pricing')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('price_monthly')
                        ->label('Monthly Price (â‚¹)')
                        ->numeric()
                        ->default(0)
                        ->prefix('â‚¹')
                        ->required(),

                    Forms\Components\TextInput::make('price_yearly')
                        ->label('Yearly Price (â‚¹)')
                        ->numeric()
                        ->default(0)
                        ->prefix('â‚¹')
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('razorpay_plan_monthly')
                        ->label('Razorpay Plan ID (Monthly)')
                        ->placeholder('plan_xxxxxxxxxxxx')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('razorpay_plan_yearly')
                        ->label('Razorpay Plan ID (Yearly)')
                        ->placeholder('plan_xxxxxxxxxxxx')
                        ->maxLength(100),
                ]),
            ]),

            Section::make('Limits (JSON)')->schema([
                Forms\Components\Textarea::make('limits_json')
                    ->label('Limits Configuration (JSON)')
                    ->rows(12)
                    ->helperText('Use -1 for unlimited. Keys: ai_messages_per_month, document_vault, family_members, ocr_per_month, form_filling_per_month, doc_generation_per_month, human_sathi_sessions, daily_customer_queue, ai_toolkit (true/false)')
                    ->placeholder('{
    "ai_messages_per_month": 200,
    "document_vault": 30,
    "family_members": 5,
    "ocr_per_month": 15,
    "form_filling_per_month": 10,
    "doc_generation_per_month": 10,
    "human_sathi_sessions": 2,
    "daily_customer_queue": -1,
    "ai_toolkit": false
}')
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state),
            ]),

            Section::make('Features (JSON)')->schema([
                Forms\Components\Textarea::make('features_json')
                    ->label('Features List (JSON Array)')
                    ->rows(10)
                    ->helperText('Array of feature strings shown to users on plans page.')
                    ->placeholder('["Sathi AI chat â€” 200 messages/month", "Document vault â€” 30 documents"]')
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state),
            ]),

            Section::make('Display Settings')->schema([
                Grid::make(3)->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Display Order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Forms\Components\Toggle::make('is_popular')
                        ->label('Show "Most Popular" Badge')
                        ->default(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'citizen',
                        'success' => 'csc_agent',
                    ]),

                Tables\Columns\TextColumn::make('price_monthly')
                    ->label('Monthly')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_yearly')
                    ->label('Yearly')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug / Tier')
                    ->copyable()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Popular')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('Active Subs')
                    ->counts('subscriptions')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'citizen'   => 'Citizen',
                        'csc_agent' => 'Seva Mitra',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit'   => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
