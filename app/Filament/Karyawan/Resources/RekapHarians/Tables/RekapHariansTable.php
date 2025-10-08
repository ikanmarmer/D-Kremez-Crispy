<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Columns\Column as ExcelColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class RekapHariansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y') // or your preferred format
                    ->sortable(),
                TextColumn::make('total_omzet')
                    ->numeric()
                    ->sortable()
                    ->money('idr', true),
                TextColumn::make('jumlah_pelanggan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_pengeluaran')
                    ->numeric()
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
                BulkActionGroup::make(actions: [
                    ExportBulkAction::make()
                        ->deselectRecordsAfterCompletion()
                        ->color('secondary'), // opsional: agar pilihan dibersihkan setelah export
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
                ExportAction::make()
                    ->tooltip('Ekspor seluruh rekap  ke Excel')  // <— ini tooltip
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->except([
                                'created_at',
                                'updated_at',
                            ])
                            ->withColumns([
                                ExcelColumn::make('user.name')->heading('Karyawan'),
                                ExcelColumn::make('tanggal')->heading('Tanggal'),
                                ExcelColumn::make('total_omzet')->heading('Total Omzet')
                                    ->format('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)')
                                    ->width(15),
                                ExcelColumn::make('jumlah_pelanggan')->heading('Jumlah Pelanggan'),
                                ExcelColumn::make('total_pengeluaran')->heading('Total Pengeluaran'),
                                ExcelColumn::make('catatan')->heading('Catatan'),
                            ])
                            // kamu bisa pilih kolom / exclude jika ingin:
                        // ->except(['updated_at'])
                            ->withFilename(fn() => 'rekap_harian_' . now()->format('Ymd_His')),
                    ]),
            ])
            ->selectCurrentPageOnly();;
    }
}
