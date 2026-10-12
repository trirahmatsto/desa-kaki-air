<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSuratResource\Pages;
use App\Models\JenisSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JenisSuratResource extends Resource
{
    protected static ?string $model = JenisSurat::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationLabel = 'Jenis Layanan Surat';
    
    protected static ?string $pluralModelLabel = 'Jenis Layanan Surat';
    
    protected static ?string $modelLabel = 'Jenis Layanan Surat';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jenis Surat')
                    ->description('Tentukan nama dan kode jenis layanan surat desa.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_surat')
                            ->label('Nama Surat')
                            ->placeholder('Contoh: Surat Keterangan Tidak Mampu')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kode_surat')
                            ->label('Kode Surat')
                            ->placeholder('Contoh: SKTM')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_surat')
                    ->label('Nama Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_surat')
                    ->label('Kode Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_aktif')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d F Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_aktif')
                    ->label('Status Aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisSurats::route('/'),
            'create' => Pages\CreateJenisSurat::route('/create'),
            'edit' => Pages\EditJenisSurat::route('/{record}/edit'),
        ];
    }
}
