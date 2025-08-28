<?php

namespace App\Filament\Resources\PengaturanTampilans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengaturanTampilansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_tema')
                    ->searchable(),
                TextColumn::make('path_logo')
                    ->searchable(),
                TextColumn::make('warna_utama')
                    ->searchable(),
                IconColumn::make('aktif')
                    ->boolean(),
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
