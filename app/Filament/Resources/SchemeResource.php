<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchemeResource\Pages;
use App\Models\Scheme;
use App\Models\SchemeCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class SchemeResource extends Resource
{
    protected static ?string $model           = Scheme::class;
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Schemes';
    protected static ?string $navigationLabel = 'All Schemes';
    protected static ?int    $navigationSort  = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Basic Information')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', \Illuminate\Support\Str::slug($state))),

                    Forms\Components\TextInput::make('hindi_name')
                        ->label('Hindi Name (हिंदी नाम)')
                        ->maxLength(255),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(SchemeCategory::pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                ]),

                Forms\Components\TextInput::make('ministry')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Level & Location')->schema([
                Grid::make(3)->schema([
                    Forms\Components\Toggle::make('is_central')
                        ->label('Central Government Scheme')
                        ->default(true)
                        ->live(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active / Published')
                        ->default(true),

                    Forms\Components\DatePicker::make('deadline')
                        ->label('Application Deadline'),
                ]),

                Grid::make(2)->schema([
                    Forms\Components\Select::make('state')
                        ->label('State')
                        ->options([
                            'central' => 'Central (All India)',
                            'BR' => 'Bihar',
                            'UP' => 'Uttar Pradesh',
                            'RJ' => 'Rajasthan',
                            'MP' => 'Madhya Pradesh',
                            'MH' => 'Maharashtra',
                            'GJ' => 'Gujarat',
                            'KA' => 'Karnataka',
                            'TN' => 'Tamil Nadu',
                            'WB' => 'West Bengal',
                            'PB' => 'Punjab',
                            'HR' => 'Haryana',
                            'AP' => 'Andhra Pradesh',
                            'TS' => 'Telangana',
                            'OR' => 'Odisha',
                            'JH' => 'Jharkhand',
                            'CG' => 'Chhattisgarh',
                            'AS' => 'Assam',
                            'KL' => 'Kerala',
                            'UK' => 'Uttarakhand',
                            'HP' => 'Himachal Pradesh',
                            'DL' => 'Delhi',
                            'JK' => 'Jammu & Kashmir',
                            'GA' => 'Goa',
                        ])
                        ->default('central')
                        ->searchable(),

                    Forms\Components\TextInput::make('district')
                        ->label('District (if district-level scheme)')
                        ->maxLength(100),
                ]),
            ]),

            Section::make('Benefit Details')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('benefit_value')
                        ->label('Benefit Value (₹)')
                        ->numeric()
                        ->prefix('₹'),

                    Forms\Components\Select::make('benefit_type')
                        ->label('Benefit Type')
                        ->options([
                            'cash'         => '💰 Cash Transfer',
                            'loan'         => '🏦 Loan',
                            'insurance'    => '🛡️ Insurance',
                            'subsidy'      => '📉 Subsidy',
                            'scholarship'  => '🎓 Scholarship',
                            'pension'      => '👴 Pension',
                            'grant'        => '🎁 Grant',
                            'service'      => '⚙️ Service',
                            'discount'     => '🏷️ Discount',
                            'training'     => '📚 Training',
                            'goods'        => '📦 Goods',
                            'document'     => '📄 Document',
                            'registration' => '✅ Registration',
                            'mixed'        => '🔀 Mixed',
                        ]),
                ]),
            ]),

            Section::make('Eligibility Rules (JSON)')->schema([
                Forms\Components\Textarea::make('eligibility_rules_json')
                    ->label('Eligibility Rules')
                    ->rows(8)
                    ->helperText('JSON format. Keys: min_age, max_age, gender, caste_category, occupation, max_income, bpl_only, state etc.')
                    ->placeholder('{
  "min_age": 18,
  "max_age": 60,
  "gender": "female",
  "caste_category": ["SC", "ST", "OBC"],
  "occupation": "farmer",
  "max_annual_income": 200000,
  "bpl_only": false,
  "state": "BR"
}')
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                    ->columnSpanFull(),
            ]),

            Section::make('Documents Required (JSON)')->schema([
                Forms\Components\Textarea::make('documents_required_json')
                    ->label('Documents Required')
                    ->rows(5)
                    ->helperText('JSON array of required documents.')
                    ->placeholder('["Aadhaar Card", "Bank Passbook", "Income Certificate", "Caste Certificate", "Photo"]')
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                    ->columnSpanFull(),
            ]),

            Section::make('Links & Contact')->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('portal_url')
                        ->label('Portal URL')
                        ->url()
                        ->prefix('https://'),

                    Forms\Components\TextInput::make('form_url')
                        ->label('Application Form URL')
                        ->url()
                        ->prefix('https://'),

                    Forms\Components\TextInput::make('status_url')
                        ->label('Status Check URL')
                        ->url()
                        ->prefix('https://'),

                    Forms\Components\TextInput::make('helpline')
                        ->label('Helpline Number'),

                    Forms\Components\TextInput::make('whatsapp')
                        ->label('WhatsApp Number'),
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
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->name),

                Tables\Columns\TextColumn::make('hindi_name')
                    ->label('Hindi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('state')
                    ->badge()
                    ->color(fn ($state) => $state === 'central' ? 'primary' : 'warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ministry')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('benefit_value')
                    ->label('Benefit')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('benefit_type')
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'scholarship',
                        'warning' => 'loan',
                        'danger'  => 'insurance',
                    ]),

                Tables\Columns\IconColumn::make('is_central')
                    ->label('Central')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->deadline && $record->deadline < now() ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('state')
                    ->options([
                        'central' => 'Central',
                        'BR' => 'Bihar',
                        'UP' => 'Uttar Pradesh',
                        'RJ' => 'Rajasthan',
                        'MP' => 'Madhya Pradesh',
                        'MH' => 'Maharashtra',
                        'GJ' => 'Gujarat',
                        'KA' => 'Karnataka',
                        'TN' => 'Tamil Nadu',
                        'WB' => 'West Bengal',
                        'PB' => 'Punjab',
                        'HR' => 'Haryana',
                        'JH' => 'Jharkhand',
                        'OR' => 'Odisha',
                        'DL' => 'Delhi',
                    ]),

                Tables\Filters\SelectFilter::make('benefit_type')
                    ->options([
                        'cash'        => 'Cash',
                        'scholarship' => 'Scholarship',
                        'loan'        => 'Loan',
                        'insurance'   => 'Insurance',
                        'pension'     => 'Pension',
                        'subsidy'     => 'Subsidy',
                        'service'     => 'Service',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_central')
                    ->label('Central / State'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn ($record) => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->action(fn ($record) => $record->update(['is_active' => !$record->is_active]))
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),

                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSchemes::route('/'),
            'create' => Pages\CreateScheme::route('/create'),
            'edit'   => Pages\EditScheme::route('/{record}/edit'),
        ];
    }
}