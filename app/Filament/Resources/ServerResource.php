<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Models\{Server, User};
use Filament\Forms\Components\{Hidden, Select, TextInput, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\{DeleteAction, DeleteBulkAction, EditAction};
use Filament\Tables\Columns\{BooleanColumn, TextColumn};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ServerResource extends Resource
{
    protected static ?string $model           = Server::class;
    protected static ?string $navigationLabel = 'Servers';
    protected static ?string $navigationIcon  = 'heroicon-o-server';

    /* -----------------------------------------------------------------
     |  منو برای همه قابل مشاهده است
     | -----------------------------------------------------------------
     */
    public static function canViewAny(): bool
    {
        return true;
    }

    /* -----------------------------------------------------------------
     |  Flag helpers
     | -----------------------------------------------------------------
     */
    public static function getFlagEmoji(string $code): string
    {
        $code = strtoupper($code);
        return strlen($code) === 2
            ? mb_chr(0x1F1E6 + (ord($code[0]) - 65))
            . mb_chr(0x1F1E6 + (ord($code[1]) - 65))
            : '';
    }

    protected static function getFlagUrl(string $code): string
    {
        return "https://flagcdn.com/24x18/{$code}.png";
    }

    protected static function formatCountry(string $state): string
    {
        $code = strtolower($state);
        return "<div class='flex items-center'>"
            . self::getFlagEmoji($code)
            . "<img src='" . self::getFlagUrl($code) . "' class='h-5 w-5 ml-2' alt='flag' /></div>";
    }

    /* -----------------------------------------------------------------
     |  Form
     | -----------------------------------------------------------------
     */
    public static function form(Form $form): Form
    {
        $schema = [
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('link')
                ->required()
                ->maxLength(255),

            Select::make('country')
                ->options(fn () => Cache::remember('country_opts', now()->addDay(), function () {
                    return collect(countries())->mapWithKeys(fn ($c) => [
                        strtolower($c['iso_3166_1_alpha2']) =>
                            "<div class='flex items-center'>
                                <img src='" . self::getFlagUrl(strtolower($c['iso_3166_1_alpha2'])) . "' class='h-5 w-5 ml-2' />
                                <span class='ml-2'>{$c['name']}</span>
                             </div>",
                    ])->toArray();
                }))
                ->searchable()
                ->allowHtml()
                ->required(),

            TextInput::make('ip')
                ->ip()
                ->required(),

            Toggle::make('active')
                ->default(false),
        ];

        // ست‌کردن ستون user_id
        if (optional(auth()->user())->isSuperAdmin()) {
            $schema[] = Select::make('user_id')
                ->label('User')
                ->options(User::pluck('name', 'id'))
                ->required();
        } else {
            $schema[] = Hidden::make('user_id')
                ->default(auth()->id())
                ->dehydrated();
        }

        return $form->schema($schema);
    }

    /* -----------------------------------------------------------------
     |  Table
     | -----------------------------------------------------------------
     */
    public static function table(Table $table): Table
    {
        return $table
            // رکوردهای قابل مشاهده برای کاربر عادی
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if (! optional($user)->isSuperAdmin()) {
                    $query->where('user_id', $user?->id);
                }
            })
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('name')->searchable(),

                TextColumn::make('link')
                    ->formatStateUsing(fn (string $state) => Str::limit($state, 30))
                    ->copyable()
                    ->copyMessage('Copied!'),

                TextColumn::make('country')
                    ->formatStateUsing(fn (string $state) => self::formatCountry($state))
                    ->html(),

                TextColumn::make('ip')->searchable(),

                BooleanColumn::make('active'),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Filter::make('active'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    /* -----------------------------------------------------------------
     |  Pages
     | -----------------------------------------------------------------
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit'   => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
