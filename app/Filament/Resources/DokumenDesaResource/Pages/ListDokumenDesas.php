<?php

namespace App\Filament\Resources\DokumenDesaResource\Pages;

use App\Filament\Resources\DokumenDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenDesas extends ListRecords
{
    protected static string $resource = DokumenDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
