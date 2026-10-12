<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenDesaResource\Pages;
use App\Filament\Resources\DokumenDesaResource\RelationManagers;
use App\Models\DokumenDesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokumenDesaResource extends Resource
{
    protected static ?string $model = DokumenDesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Dokumen Publikasi';

    protected static ?string $pluralModelLabel = 'Dokumen Publikasi';

    protected static ?string $modelLabel = 'Dokumen Publikasi';

    protected static ?string $navigationGroup = 'Informasi Publik';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dokumen Publikasi Desa')
                    ->description('Unggah dokumen publikasi desa seperti RPJMDes, RKPDes, atau APBDes agar dapat diunduh oleh warga.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_dokumen')
                            ->label('Nama Dokumen')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_publik')
                            ->label('Publikasikan ke Warga?')
                            ->default(true)
                            ->required(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('nama_file')
                            ->label('File Dokumen (PDF, Word)')
                            ->required()
                            ->directory('dokumen_desa')
                            ->disk('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            ])
                            ->maxSize(10240) // 10MB
                            ->helperText('Format berkas yang diterima: PDF atau Word (.doc, .docx). Maksimal ukuran file 10MB.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_dokumen')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_publik')
                    ->label('Publik?')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_file')
                    ->label('Nama File')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->limit(30)
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Diunggah')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_publik')
                    ->label('Status Publikasi')
                    ->boolean()
                    ->trueLabel('Hanya Publik')
                    ->falseLabel('Hanya Privat'),
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
            'index' => Pages\ListDokumenDesas::route('/'),
            'create' => Pages\CreateDokumenDesa::route('/create'),
            'edit' => Pages\EditDokumenDesa::route('/{record}/edit'),
        ];
    }
}
