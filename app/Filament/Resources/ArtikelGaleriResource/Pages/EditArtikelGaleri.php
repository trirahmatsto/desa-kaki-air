<?php

namespace App\Filament\Resources\ArtikelGaleriResource\Pages;

use App\Filament\Resources\ArtikelGaleriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtikelGaleri extends EditRecord
{
    protected static string $resource = ArtikelGaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
