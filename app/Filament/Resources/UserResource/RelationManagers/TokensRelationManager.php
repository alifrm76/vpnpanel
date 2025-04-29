<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\{CreateAction, DeleteAction};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Token Name'),
                TextColumn::make('last_used_at')->since(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Token')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Token name')
                            ->required(),
                    ])
                    ->using(function (array $data, Model $user) {
                        // Sanctum
                        $plain = $user->createToken($data['name'])->plainTextToken;

                        Notification::make()
                            ->title('Token created')
                            ->body("**Copy now:** `{$plain}`")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
