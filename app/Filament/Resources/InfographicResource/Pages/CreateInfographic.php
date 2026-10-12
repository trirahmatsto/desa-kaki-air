<?php

namespace App\Filament\Resources\InfographicResource\Pages;

use App\Filament\Resources\InfographicResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInfographic extends CreateRecord
{
    protected static string $resource = InfographicResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['file_type'] = pathinfo($data['file_path'], PATHINFO_EXTENSION);
        return $data;
    }
}
