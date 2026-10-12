<?php

namespace App\Filament\Resources\ArtikelGaleriResource\Pages;

use App\Filament\Resources\ArtikelGaleriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtikelGaleris extends ListRecords
{
    protected static string $resource = ArtikelGaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
