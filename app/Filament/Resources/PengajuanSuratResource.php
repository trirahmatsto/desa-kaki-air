<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSuratResource\Pages;
use App\Models\PengajuanSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengajuanSuratResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Pengajuan Surat';

    protected static ?string $pluralModelLabel = 'Pengajuan Surat';

    protected static ?string $modelLabel = 'Pengajuan Surat';

    protected static ?string $navigationGroup = 'Layanan & Laporan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pemohon & Layanan')
                    ->description('Data yang dimasukkan secara manual oleh pemohon.')
                    ->schema([
                        Forms\Components\TextInput::make('nik_pemohon')
                            ->label('NIK Pemohon')
                            ->disabled() // Defaultnya disabled demi integritas data awal pemohon
                            ->dehydrated() // Tetap dikirim ke database
                            ->required()
                            ->length(16),
                        Forms\Components\TextInput::make('nama_pemohon')
                            ->label('Nama Lengkap Pemohon')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_whatsapp')
                            ->label('No. WhatsApp Aktif')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\Select::make('jenis_surat_id')
                            ->label('Jenis Surat')
                            ->relationship('jenisSurat', 'nama_surat')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Textarea::make('keperluan')
                            ->label('Keperluan / Peruntukan Surat')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Validasi & Berkas Pendukung')
                    ->description('Silakan verifikasi kesesuaian berkas ini dengan identitas pemohon.')
                    ->schema([
                        Forms\Components\FileUpload::make('berkas_ktp_kk')
                            ->label('Foto KTP / KK Pemohon')
                            ->directory('pengajuan_surat')
                            ->disabled() // Hanya untuk dilihat/diunduh
                            ->dehydrated()
                            ->columnSpanFull()
                            ->openable() // Membuka tab baru
                            ->downloadable() // Mendownload file
                            ->helperText('Klik tombol ikon buka/unduh untuk memverifikasi berkas secara manual.'),
                    ]),

                Forms\Components\Section::make('Verifikasi Status Pengajuan')
                    ->description('Ubah status pengajuan dan berikan nomor surat resmi atau alasan penolakan jika diperlukan.')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Pengajuan')
                            ->options([
                                'menunggu' => 'Menunggu Verifikasi',
                                'diproses' => 'Sedang Diproses',
                                'selesai' => 'Selesai (Surat Siap)',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->live() // Memicu reaktivitas form
                            ->default('menunggu'),

                        Forms\Components\TextInput::make('nomor_surat_resmi')
                            ->label('Nomor Surat Resmi')
                            ->placeholder('Contoh: 140/012/PEM/VI/2026')
                            ->maxLength(255)
                            ->live()
                            ->visible(fn (Get $get): bool => $get('status') === 'selesai')
                            ->required(fn (Get $get): bool => $get('status') === 'selesai'),

                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan penolakan agar pemohon mengetahui kelengkapan berkas yang kurang.')
                            ->live()
                            ->visible(fn (Get $get): bool => $get('status') === 'ditolak')
                            ->required(fn (Get $get): bool => $get('status') === 'ditolak')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik_pemohon')
                    ->label('NIK Pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisSurat.nama_surat')
                    ->label('Jenis Surat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_surat_resmi')
                    ->label('No. Surat')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan Pada')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_surat_id')
                    ->label('Jenis Surat')
                    ->relationship('jenisSurat', 'nama_surat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Proses'),
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
            'index' => Pages\ListPengajuanSurats::route('/'),
            'create' => Pages\CreatePengajuanSurat::route('/create'),
            'edit' => Pages\EditPengajuanSurat::route('/{record}/edit'),
        ];
    }
}
