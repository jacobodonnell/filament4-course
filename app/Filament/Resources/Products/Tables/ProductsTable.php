<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatusEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                          ->sortable()
                          ->searchable(),
                TextColumn::make('price')
                          ->sortable()
                          ->money(divideBy: 100),
                TextColumn::make('status'),
                TextColumn::make('category.name'),
                TextColumn::make('tags.name')
            ])
            ->defaultSort('price', 'desc')
            ->filters([
                SelectFilter::make('status')
                            ->options(ProductStatusEnum::class),
                SelectFilter::make('category')
                            ->relationship('category', 'name')
                            ->multiple(),
                Filter::make('created_at')
                      ->schema([
                          DatePicker::make('created_from'),
                          DatePicker::make('created_until'),
                      ])
                      ->query(function (Builder $query, array $data): Builder {
                          return $query
                              ->when(
                                  $data['created_from'],
                                  fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                              )
                              ->when(
                                  $data['created_until'],
                                  fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                              );
                      })
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
