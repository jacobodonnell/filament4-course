<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatusEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                         ->required()
                         ->unique(),
                TextInput::make('price')
                         ->prefix('$')
                         ->required(),
                Radio::make('status')
                     ->options(ProductStatusEnum::class),
                Select::make('category_id')
                      ->relationship('category', 'name')
            ]);
    }
}
