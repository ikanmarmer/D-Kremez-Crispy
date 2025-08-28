<?php

namespace App\Filament\Resources\PergerakanStoks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PergerakanStoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_produk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jenis_referensi'),
                TextColumn::make('id_referensi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_pergerakan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('jumlah_perubahan')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
