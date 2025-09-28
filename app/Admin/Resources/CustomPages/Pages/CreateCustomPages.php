<?php

namespace App\Admin\Resources\CustomPages\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Admin\Resources\CustomPages\CustomPagesResource;

class CreateCustomPages extends CreateRecord
{
    protected static string $resource = CustomPagesResource::class;
}
