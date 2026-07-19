<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpportunityResource\Pages;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OpportunityResource extends Resource
{
    protected static ?string $model = Opportunity::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Sarkari Awasar';
    protected static ?string $navigationLabel = 'Opportunities';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Basic Information')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255)->columnSpan(2),
                Forms\Components\TextInput::make('hindi_name')
                    ->maxLength(255)->columnSpan(2),
                Forms\Components\TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)->columnSpan(2),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(OpportunityCategory::pluck('name', 'id'))
                    ->required()->searchable(),
                Forms\Components\TextInput::make('conducting_body')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('post_name')
                    ->required()->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Level & Location')->schema([
                Forms\Components\Select::make('level')
                    ->options([
                        'central' => '🏛️ Central Government',
                        'state'   => '🏢 State Government',
                    ])->required(),
                Forms\Components\Select::make('state_code')
                    ->label('State')
                    ->options([
                        'BR' => 'Bihar', 'UP' => 'Uttar Pradesh',
                        'RJ' => 'Rajasthan', 'MP' => 'Madhya Pradesh',
                        'MH' => 'Maharashtra', 'WB' => 'West Bengal',
                        'TN' => 'Tamil Nadu', 'KA' => 'Karnataka',
                        'GJ' => 'Gujarat', 'PB' => 'Punjab',
                        'DL' => 'Delhi', 'HR' => 'Haryana',
                    ])->searchable()->nullable(),
                Forms\Components\TextInput::make('district')
                    ->label('District (for district level jobs)')
                    ->nullable(),
                Forms\Components\Select::make('local_level')
                    ->label('Local Level')
                    ->options([
                        'gram_panchayat' => '🏡 Gram Panchayat',
                        'block'          => '🏗️ Block Level',
                        'tehsil'         => '📋 Tehsil Level',
                        'municipality'   => '🏙️ Municipality',
                        'corporation'    => '🏢 Municipal Corporation',
                        'cantonment'     => '⚔️ Cantonment Board',
                    ])->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Job Details')->schema([
                Forms\Components\TextInput::make('vacancy_count')
                    ->numeric()->nullable(),
                Forms\Components\TextInput::make('salary_range')
                    ->nullable()->placeholder('e.g. ₹35,000 – ₹63,840/month'),
                Forms\Components\TextInput::make('job_location')
                    ->nullable()->placeholder('e.g. All India'),
                Forms\Components\TextInput::make('helpline')
                    ->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Important Dates')->schema([
                Forms\Components\DatePicker::make('apply_start')->nullable(),
                Forms\Components\DatePicker::make('apply_end')->nullable(),
                Forms\Components\DatePicker::make('exam_date')->nullable(),
                Forms\Components\DatePicker::make('admit_card_date')->nullable(),
                Forms\Components\DatePicker::make('result_date')->nullable(),
            ])->columns(3),

            Forms\Components\Section::make('Links')->schema([
                Forms\Components\TextInput::make('apply_url')
                    ->url()->nullable()->columnSpan(2),
                Forms\Components\TextInput::make('notification_url')
                    ->url()->nullable()->columnSpan(2),
                Forms\Components\TextInput::make('syllabus_url')
                    ->url()->nullable()->columnSpan(2),
                Forms\Components\TextInput::make('official_site')
                    ->url()->nullable()->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Eligibility & Documents')->schema([
                Forms\Components\KeyValue::make('eligibility_rules_json')
                    ->label('Eligibility Rules')
                    ->columnSpanFull(),
                Forms\Components\TagsInput::make('documents_required_json')
                    ->label('Documents Required')
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Toggle::make('is_featured')->default(false),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable()->limit(35)->weight('bold'),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()->sortable(),
                Tables\Columns\TextColumn::make('conducting_body')
                    ->searchable()->limit(20),
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn($state) => $state === 'central' ? 'info' : 'warning'),
                Tables\Columns\TextColumn::make('state_code')
    ->label('State')->badge()->placeholder('-'),
Tables\Columns\TextColumn::make('local_level')
    ->label('Local')->badge()->placeholder('-'),
Tables\Columns\TextColumn::make('vacancy_count')
    ->label('Vacancies')
    ->numeric()->sortable()->placeholder('-'),
Tables\Columns\TextColumn::make('apply_end')
    ->label('Last Date')
    ->date('d M Y')->sortable()->color('danger')->placeholder('No deadline'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()->label('Featured'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('level')
                    ->options(['central' => 'Central', 'state' => 'State']),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_featured'),  
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('renew')
    ->label('Renew')
    ->icon('heroicon-o-arrow-path')
    ->color('warning')
    ->visible(fn($record) => !$record->is_active)
    ->action(function ($record) {
        $nextYear = now()->year + 1;
        $record->update([
            'name'       => preg_replace('/\d{4}/', $nextYear, $record->name),
            'slug'       => preg_replace('/\d{4}/', $nextYear, $record->slug),
            'is_active'  => true,
            'apply_end'  => null,
            'exam_date'  => null,
            'result_date'=> null,
        ]);
        \Filament\Notifications\Notification::make()
            ->title('Renewed for ' . $nextYear)
            ->success()
            ->send();
    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('apply_end', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOpportunities::route('/'),
            'create' => Pages\CreateOpportunity::route('/create'),
            'edit'   => Pages\EditOpportunity::route('/{record}/edit'),
        ];
    }
}