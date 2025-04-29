<?php

     namespace App\Filament\Resources;

     use App\Filament\Resources\UserResource\Pages;
     use App\Models\User;
     use Filament\Forms;
     use Filament\Forms\Form;
     use Filament\Resources\Resource;
     use Filament\Tables;
     use Filament\Tables\Table;
     use Illuminate\Support\Facades\Hash;

     class UserResource extends Resource
     {
         protected static ?string $model = User::class;

         protected static ?string $navigationIcon = 'heroicon-o-users';

         protected static ?string $navigationLabel = 'Users';

         // فقط برای سوپر ادمین قابل مشاهده باشه
         public static function canViewAny(): bool
         {
             return auth()->user()->isSuperAdmin();
         }

         public static function form(Form $form): Form
         {
             return $form
                 ->schema([
                     Forms\Components\TextInput::make('name')
                         ->label('Name')
                         ->required()
                         ->maxLength(255),
                     Forms\Components\TextInput::make('email')
                         ->label('Email')
                         ->email()
                         ->required()
                         ->maxLength(255)
                         ->unique(User::class, 'email', ignoreRecord: true),
                     Forms\Components\TextInput::make('password')
                         ->label('Password')
                         ->password()
                         ->required()
                         ->minLength(8)
                         ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                         ->dehydrated(fn (?string $state): bool => filled($state))
                         ->visible(fn ($livewire) => $livewire instanceof Pages\CreateUser),
                     Forms\Components\Toggle::make('is_super_admin')
                         ->label('Super Admin')
                         ->default(false),
                 ]);
         }

         public static function table(Table $table): Table
         {
             return $table
                 ->columns([
                     Tables\Columns\TextColumn::make('name')
                         ->label('Name')
                         ->searchable(),
                     Tables\Columns\TextColumn::make('email')
                         ->label('Email')
                         ->searchable(),
                     Tables\Columns\BooleanColumn::make('is_super_admin')
                         ->label('Super Admin'),
                     Tables\Columns\TextColumn::make('created_at')
                         ->label('Created At')
                         ->dateTime('Y-m-d H:i')
                         ->sortable(),
                 ])
                 ->defaultSort('created_at', 'desc')
                 ->actions([
                     Tables\Actions\EditAction::make(),
                     Tables\Actions\DeleteAction::make(),
                 ])
                 ->bulkActions([
                     Tables\Actions\DeleteBulkAction::make(),
                 ]);
         }

         public static function getPages(): array
         {
             return [
                 'index' => Pages\ListUsers::route('/'),
                 'create' => Pages\CreateUser::route('/create'),
                 'edit' => Pages\EditUser::route('/{record}/edit'),
             ];
         }
     }