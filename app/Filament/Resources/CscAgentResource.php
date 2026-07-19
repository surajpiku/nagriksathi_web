<?php
namespace App\Filament\Resources;
use App\Filament\Resources\CscAgentResource\Pages;
use App\Models\CscAgent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CscAgentResource extends Resource
{
    protected static ?string $model = CscAgent::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Seva Mitra Network';
    protected static ?string $navigationLabel = 'Seva Mitras';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Agent Info')->schema([
                Forms\Components\TextInput::make('centre_name')->maxLength(255),
                Forms\Components\TextInput::make('csc_id')->label('CSC ID')->maxLength(255),
                Forms\Components\Select::make('agent_type')
                    ->options([
                        'official_vle'  => 'ðŸŸ¢ Official VLE',
                        'sathi_partner' => 'ðŸ”µ Sathi Partner',
                        'partner_agent' => 'ðŸŸ¡ Partner Agent',
                    ])->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'verified'  => 'Verified',
                        'suspended' => 'Suspended',
                        'rejected'  => 'Rejected',
                    ])->required(),
                Forms\Components\Textarea::make('rejection_reason')->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Location')->schema([
                Forms\Components\TextInput::make('state')->required(),
                Forms\Components\TextInput::make('state_code')->required()->maxLength(5),
                Forms\Components\TextInput::make('district')->required(),
                Forms\Components\TextInput::make('block')->nullable(),
                Forms\Components\TextInput::make('pincode')->required()->maxLength(6),
                Forms\Components\TextInput::make('address')->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Payment')->schema([
                Forms\Components\TextInput::make('bank_account')->nullable(),
                Forms\Components\TextInput::make('ifsc_code')->nullable(),
                Forms\Components\TextInput::make('upi_id')->nullable(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')->searchable(),
                Tables\Columns\TextColumn::make('centre_name')
                    ->searchable()->limit(25),
                Tables\Columns\TextColumn::make('agent_type')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'official_vle'  => 'success',
                        'sathi_partner' => 'info',
                        'partner_agent' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('district')->searchable(),
                Tables\Columns\TextColumn::make('state_code')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'verified'  => 'success',
                        'pending'   => 'warning',
                        'suspended' => 'danger',
                        'rejected'  => 'danger',
                    }),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric(2)->sortable(),
                Tables\Columns\TextColumn::make('tasks_completed')
                    ->numeric()->sortable()->label('Tasks'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'verified'  => 'Verified',
                        'suspended' => 'Suspended',
                        'rejected'  => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('agent_type')
                    ->options([
                        'official_vle'  => 'Official VLE',
                        'sathi_partner' => 'Sathi Partner',
                        'partner_agent' => 'Partner Agent',
                    ]),
            ])
            ->actions([
                // Approve Action
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(function (CscAgent $record) {
                        $record->update([
                            'status'      => 'verified',
                            'verified_at' => now(),
                        ]);
                        // Assign csc_agent role
                        $record->user->assignRole(
                            \Spatie\Permission\Models\Role::findByName('seva_mitra', 'sanctum')
                        );
                        Notification::make()
                            ->title('Agent Approved!')
                            ->success()
                            ->send();
                    }),

                // Reject Action
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function (CscAgent $record, array $data) {
                        $record->update([
                            'status'           => 'rejected',
                            'rejection_reason' => $data['reason'],
                        ]);
                        Notification::make()
                            ->title('Agent Rejected')
                            ->danger()
                            ->send();
                    }),

                // Suspend Action
                Tables\Actions\Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->visible(fn($record) => $record->status === 'verified')
                    ->requiresConfirmation()
                    ->action(function (CscAgent $record) {
                        $record->update(['status' => 'suspended']);
                        $record->user->removeRole('seva_mitra');
                        Notification::make()
                            ->title('Agent Suspended')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

   public static function getPages(): array
{
    return [
        'index'  => Pages\ListCscAgents::route('/'),
        'create' => Pages\CreateCscAgent::route('/create'),
        'edit'   => Pages\EditCscAgent::route('/{record}/edit'),
    ];
}
    public static function getNavigationBadge(): ?string
    {
        return (string) CscAgent::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}

