<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Activa')
                                    ->default(true),
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Precio e Inventario')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Precio')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Auto-ocultar si stock = 0
                                if ($state <= 0) {
                                    $set('is_visible', false);
                                }
                            }),

                        Forms\Components\TextInput::make('size')
                            ->label('Talle')
                            ->maxLength(255)
                            ->placeholder('Ej: S, M, L, XL'),

                        Forms\Components\TextInput::make('color')
                            ->label('Color')
                            ->maxLength(255)
                            ->placeholder('Ej: Rojo, Azul, Negro'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Visibilidad')
                    ->schema([
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible en el catálogo')
                            ->default(true)
                            ->helperText('Se oculta automáticamente cuando stock = 0'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Producto destacado')
                            ->default(false)
                            ->helperText('Aparecerá en la página principal'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Imágenes')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->label('Imágenes del producto')
                            ->relationship('images')
                            ->schema([
                                Forms\Components\FileUpload::make('path')
                                    ->label('Imagen')
                                    ->image()
                                    ->directory('products')
                                    ->imageEditor()
                                    ->required(),

                                Forms\Components\TextInput::make('order')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Forms\Components\Toggle::make('is_primary')
                                    ->label('Imagen principal')
                                    ->default(false),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Agregar imagen')
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.path')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibilidad')
                    ->boolean()
                    ->trueLabel('Solo visibles')
                    ->falseLabel('Solo ocultos')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Destacados')
                    ->boolean()
                    ->trueLabel('Solo destacados')
                    ->falseLabel('Solo no destacados')
                    ->native(false),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock bajo')
                    ->query(fn (Builder $query) => $query->where('stock', '<=', 5)->where('stock', '>', 0)),

                Tables\Filters\Filter::make('out_of_stock')
                    ->label('Sin stock')
                    ->query(fn (Builder $query) => $query->where('stock', 0)),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
