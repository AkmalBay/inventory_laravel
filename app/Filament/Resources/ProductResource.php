<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('stock_alert')
                    ->label('Min Stock Alert')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('unit_type_small')
                    ->label('Unit Small')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_type_large')
                    ->label('Unit Large')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('conversion_rate')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('selling_price_pcs')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('selling_price_box')
                    ->label('Selling Price Large')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_alert')
                    ->label('Min Alert')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_type_small')
                    ->label('Unit Small'),
                Tables\Columns\TextColumn::make('unit_type_large')
                    ->label('Unit Large'),
                Tables\Columns\TextColumn::make('conversion_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price_pcs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price_box')
                    ->label('Price Large')
                    ->numeric()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
