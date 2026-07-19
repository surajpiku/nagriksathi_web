<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SathiTaskResource\Pages;
use App\Filament\Resources\SathiTaskResource\RelationManagers;
use App\Models\SathiTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SathiTaskResource extends Resource
{
    protected static ?string $model = SathiTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('agent_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('task_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('resolution')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('priority')
                    ->required(),
                Forms\Components\TextInput::make('channel')
                    ->required(),
                Forms\Components\DateTimePicker::make('completed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('channel'),
                Tables\Columns\TextColumn::make('completed_at')
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
            'index' => Pages\ListSathiTasks::route('/'),
            'create' => Pages\CreateSathiTask::route('/create'),
            'edit' => Pages\EditSathiTask::route('/{record}/edit'),
        ];
    }
}
