<?php

namespace App\Admin\Resources\CustomPages\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Admin\Resources\CustomPages\CustomPagesResource;

class ListCustomPages extends ListRecords
{
    protected static string $resource = CustomPagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
