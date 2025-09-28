<?php

namespace App\Livewire\CustomPages;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\CustomPages\Page;
use App\Models\CustomPages\PageView;

class Show extends Component
{
    public Page $page;

    public function mount()
    {
        $slug = request()->path();

        $this->page = Page::where('slug', $slug)->firstOrFail();

        // Check if page is active
        if (! $this->page->is_active) {
            abort(404);
        }

        // Check for expiration
        if ($this->page->expires_at && now()->greaterThan($this->page->expires_at)) {
            abort(404);
        }

        // Access control 
        switch ($this->page->visibility) {
            case 'admins':
                if (!Auth::check() || is_null(Auth::user()->role)) {
                    abort(404);
                }
                break;

            case 'customers':
                if (!Auth::check() || !is_null(Auth::user()->role)) {
                    abort(404);
                }
                break;

            case 'guests':
                if (Auth::check()) {
                    abort(404);
                }
                break;

            case 'logged-in':
                if (!Auth::check()) {
                    abort(404);
                }
                break;

            case 'everyone':
            default:
                // everyone can see
                break;
        }

        // Track page views
        PageView::create([
            'page_id'    => $this->page->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'viewed_at'  => now(),
        ]);
    }

    public function render()
    {
        return view('custom-pages.show', [
            'page' => $this->page,
        ])->layout('layouts.app', [
            'title' => $this->page->meta_title ?? $this->page->title,
            'description' => $this->page->meta_description ?? '',
            'image' => $this->page->meta_image ?? null,
            'color' => $this->page->meta_color ?? null,
            'favicon' => $this->page->meta_favicon ?? null,
        ]);
    }
}
