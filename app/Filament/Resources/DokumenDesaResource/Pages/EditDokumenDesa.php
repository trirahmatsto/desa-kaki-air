<?php

namespace App\Filament\Resources\DokumenDesaResource\Pages;

use App\Filament\Resources\DokumenDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenDesa extends EditRecord
{
    protected static string $resource = DokumenDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
