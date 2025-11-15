<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Configuraciones';

    protected static ?string $modelLabel = 'Configuración';

    protected static ?string $pluralModelLabel = 'Configuraciones';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Clave')
                            ->required()
                            ->maxLength(255)
                            ->unique(Setting::class, 'key', ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Identificador único de la configuración'),

                        Forms\Components\Select::make('group')
                            ->label('Grupo')
                            ->options([
                                'general' => 'General',
                                'whatsapp' => 'WhatsApp',
                                'shipping' => 'Envíos',
                                'payment' => 'Pagos',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'text' => 'Texto',
                                'number' => 'Número',
                                'boolean' => 'Verdadero/Falso',
                                'json' => 'JSON',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('value')
                            ->label('Valor')
                            ->visible(fn (callable $get) => in_array($get('type'), ['text', 'number']))
                            ->maxLength(255),

                        Forms\Components\Toggle::make('value')
                            ->label('Valor')
                            ->visible(fn (callable $get) => $get('type') === 'boolean')
                            ->formatStateUsing(fn ($state) => filter_var($state, FILTER_VALIDATE_BOOLEAN))
                            ->dehydrateStateUsing(fn ($state) => $state ? 'true' : 'false'),

                        Forms\Components\Textarea::make('value')
                            ->label('Valor (JSON)')
                            ->visible(fn (callable $get) => $get('type') === 'json')
                            ->rows(5)
                            ->helperText('Ingrese un JSON válido'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Grupo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'whatsapp' => 'success',
                        'shipping' => 'info',
                        'payment' => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Clave')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 50) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grupo')
                    ->options([
                        'general' => 'General',
                        'whatsapp' => 'WhatsApp',
                        'shipping' => 'Envíos',
                        'payment' => 'Pagos',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'text' => 'Texto',
                        'number' => 'Número',
                        'boolean' => 'Verdadero/Falso',
                        'json' => 'JSON',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
