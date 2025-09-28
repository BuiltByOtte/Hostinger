<?php

namespace App\Models\CustomPages;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'custom_pages';

    protected $fillable = [
        'is_active',
        'expires_at',
        'title',
        'slug',
        'content',
        'visibility',
        'visible_in_menu',

        'meta_title',
        'meta_description',
        'meta_image',
        'meta_color',
        'meta_favicon',

        'htmlcontent',
    ];

    protected $casts = [
        'visible_in_menu' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
   
    public function views()
    {
        return $this->hasMany(PageView::class);
    }
}
