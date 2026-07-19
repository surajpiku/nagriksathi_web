<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpportunityCategoryResource\Pages;
use App\Models\OpportunityCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OpportunityCategoryResource extends Resource
{
    protected static ?string $model = OpportunityCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Sarkari Awasar';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('hindi_name')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('icon')->required()->placeholder('emoji e.g. 🏦'),
            Forms\Components\Textarea::make('description')->nullable(),
            Forms\Components\TextInput::make('display_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')->label(''),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('hindi_name'),
                Tables\Columns\TextColumn::make('slug')->badge(),
                Tables\Columns\TextColumn::make('display_order')->sortable()->label('Order'),
                Tables\Columns\TextColumn::make('opportunities_count')
                    ->counts('opportunities')->label('Opportunities')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->reorderable('display_order')
            ->defaultSort('display_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOpportunityCategories::route('/'),
            'create' => Pages\CreateOpportunityCategory::route('/create'),
            'edit'   => Pages\EditOpportunityCategory::route('/{record}/edit'),
        ];
    }
}