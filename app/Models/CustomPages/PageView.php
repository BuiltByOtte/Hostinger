<?php

namespace App\Models\CustomPages;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $table = 'custom_page_views';

    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
