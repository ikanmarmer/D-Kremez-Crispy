<?php

namespace App\Filament\Karyawan\Resources\LaporanPenjualans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanPenjualansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('nama karyawan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_laporan')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_produk_terjual')
                    ->searchable(),
                TextColumn::make('omzet')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status'),

                TextColumn::make('dikirim_pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('disetujui_pada')
                    ->dateTime()
                    ->sortable(),
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
