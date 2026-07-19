<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GrievanceResource\Pages;
use App\Filament\Resources\GrievanceResource\RelationManagers;
use App\Models\Grievance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GrievanceResource extends Resource
{
    protected static ?string $model = Grievance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('application_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cpgrams_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('rti_reference')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('rti_text')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('filed'),
                Forms\Components\DateTimePicker::make('filed_at'),
                Forms\Components\DateTimePicker::make('resolved_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('application_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpgrams_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rti_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('filed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resolved_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrievances::route('/'),
            'create' => Pages\CreateGrievance::route('/create'),
            'edit' => Pages\EditGrievance::route('/{record}/edit'),
        ];
    }
}
