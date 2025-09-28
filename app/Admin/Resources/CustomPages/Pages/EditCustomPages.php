<?php

namespace App\Admin\Resources\CustomPages\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Admin\Resources\CustomPages\CustomPagesResource;

class EditCustomPages extends EditRecord
{
    protected static string $resource = CustomPagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
