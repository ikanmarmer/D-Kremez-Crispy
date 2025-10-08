<?php

namespace App\Filament\Karyawan\Resources\RekapHarians;

use App\Filament\Karyawan\Resources\RekapHarians\Pages\CreateRekapHarian;
use App\Filament\Karyawan\Resources\RekapHarians\Pages\EditRekapHarian;
use App\Filament\Karyawan\Resources\RekapHarians\Pages\ListRekapHarians;
use App\Filament\Karyawan\Resources\RekapHarians\Pages\ViewRekapHarian;
use App\Filament\Karyawan\Resources\RekapHarians\Schemas\RekapHarianForm;
use App\Filament\Karyawan\Resources\RekapHarians\Schemas\RekapHarianInfolist;
use App\Filament\Karyawan\Resources\RekapHarians\Tables\RekapHariansTable;
use App\Models\RekapHarian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;

class RekapHarianResource extends Resource
{
    protected static ?string $model = RekapHarian::class;

    protected static ?string $slug = 'rekap-harian';
    protected static ?string $navigationLabel = 'Rekap Harian';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar; // contoh icon
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Calendar; // contoh icon

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Tanggal' => $record->tanggal->format('d M Y'),
            'Total Omzet' => 'Rp ' . number_format($record->total_omzet, 0, ',', '.'),
            'Jumlah Pelanggan' => $record->jumlah_pelanggan,
            'Catatan' => $record->catatan,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['user', 'produks', 'ProdukRekapHarian']);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')        // ikon untuk view
                ->url(static::getUrl('view', ['record' => $record]))
                ->iconButton(),                // render sebagai tombol ikon
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')     // ikon pencil untuk edit
                ->url(static::getUrl('edit', ['record' => $record]))
                ->iconButton(),
        ];
    }

    protected static int $globalSearchResultsLimit = 10;

    public static function form(Schema $schema): Schema
    {
        return RekapHarianForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RekapHarianInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekapHariansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProdukRekapHarianRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRekapHarians::route('/'),
            'create' => CreateRekapHarian::route('/create'),
            'view' => ViewRekapHarian::route('/{record}'),
            'edit' => EditRekapHarian::route('/{record}/edit'),
        ];
    }
}
