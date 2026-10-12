<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Pengaduan Warga';

    protected static ?string $pluralModelLabel = 'Pengaduan Warga';

    protected static ?string $modelLabel = 'Pengaduan';

    protected static ?string $navigationGroup = 'Layanan & Laporan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelapor')
                    ->schema([
                        Forms\Components\TextInput::make('nik_pelapor')
                            ->label('NIK Pelapor')
                            ->required()
                            ->length(16)
                            ->placeholder('16 Digit NIK')
                            ->extraInputAttributes(['pattern' => '[0-9]*']),
                        Forms\Components\TextInput::make('nama_pelapor')
                            ->label('Nama Lengkap Pelapor')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Isi Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('kategori')
                            ->label('Kategori Pengaduan')
                            ->required()
                            ->placeholder('Contoh: Infrastruktur, Lingkungan, Pelayanan')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status Pengaduan')
                            ->options([
                                'masuk' => 'Masuk (Baru)',
                                'ditinjau' => 'Ditinjau',
                                'selesai' => 'Selesai',
                            ])
                            ->required()
                            ->default('masuk'),
                        Forms\Components\Textarea::make('isi_laporan')
                            ->label('Detail Isi Laporan')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('foto_bukti')
                            ->label('Foto Bukti')
                            ->image()
                            ->directory('pengaduan')
                            ->maxSize(2048)
                            ->columnSpanFull()
                            ->helperText('Format gambar (JPG, PNG, JPEG) max 2MB.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik_pelapor')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pelapor')
                    ->label('Nama Pelapor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto_bukti')
                    ->label('Foto Bukti')
                    ->circular(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'warning',
                        'ditinjau' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'masuk' => 'Masuk',
                        'ditinjau' => 'Ditinjau',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dilaporkan Pada')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'masuk' => 'Masuk',
                        'ditinjau' => 'Ditinjau',
                        'selesai' => 'Selesai',
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
            'index' => Pages\ListPengaduans::route('/'),
            'create' => Pages\CreatePengaduan::route('/create'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
}
