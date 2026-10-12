<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtikelGaleriResource\Pages;
use App\Models\ArtikelGaleri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArtikelGaleriResource extends Resource
{
    protected static ?string $model = ArtikelGaleri::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Berita & Galeri';

    protected static ?string $pluralModelLabel = 'Berita & Galeri';

    protected static ?string $modelLabel = 'Artikel/Galeri';

    protected static ?string $navigationGroup = 'Informasi Publik';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Artikel / Dokumentasi Kegiatan')
                    ->description('Tulis berita desa atau unggah galeri dokumentasi kegiatan di sini.')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Artikel/Galeri')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'berita' => 'Berita (Artikel)',
                                'galeri' => 'Galeri (Dokumentasi Kegiatan)',
                            ])
                            ->required()
                            ->default('berita'),
                        Forms\Components\FileUpload::make('foto_sampul')
                            ->label('Foto Sampul / Gambar Dokumentasi')
                            ->image()
                            ->directory('artikel_galeri')
                            ->maxSize(2048)
                            ->helperText('Format gambar (JPG, PNG, JPEG) max 2MB.'),
                        Forms\Components\RichEditor::make('konten')
                            ->label('Konten / Detail Laporan')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('artikel_attachments'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\ImageColumn::make('foto_sampul')
                    ->label('Foto Sampul'),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'berita' => 'success',
                        'galeri' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'berita' => 'Berita',
                        'galeri' => 'Galeri Kegiatan',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'berita' => 'Berita',
                        'galeri' => 'Galeri Kegiatan',
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
            'index' => Pages\ListArtikelGaleris::route('/'),
            'create' => Pages\CreateArtikelGaleri::route('/create'),
            'edit' => Pages\EditArtikelGaleri::route('/{record}/edit'),
        ];
    }
}
