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
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('last_used_at')->since(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Token')
                    ->using(function (array $data, Model $user) {
                        $plain = $user->createToken($data['name'] ?? 'token')
                                      ->plainTextToken;
                        Notification::make()
                            ->title("Token: $plain")
                            ->info()
                            ->send();
                    }),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
