<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RekapHariansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_produk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_rekap')
                    ->date()
                    ->sortable(),
                TextColumn::make('jumlah_produk_terjual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_omzet')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('produk')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
