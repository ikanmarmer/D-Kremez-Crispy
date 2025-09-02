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
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_laporan')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_omzet')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status'),
                TextColumn::make('dikirim_pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('disetujui_pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('dibuat_pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('diperbarui_pada')
                    ->dateTime()
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
