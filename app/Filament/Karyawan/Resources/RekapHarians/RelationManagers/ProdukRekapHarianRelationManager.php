<?php

namespace App\Filament\Karyawan\Resources\RekapHarians\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class ProdukRekapHarianRelationManager extends RelationManager
{
    protected static string $relationship = 'ProdukRekapHarian';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->required(),
                TextInput::make('stok_awal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_terjual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sisa_stok')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subtotal_omzet')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('produk.nama')
            ->columns([
                TextColumn::make('produk_id')
                    ->label('Produk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stok_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_terjual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sisa_stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subtotal_omzet')
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
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
