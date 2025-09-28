<?php

namespace App\Providers;

use App\Models\CustomPages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CustomPagesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register custom pages navigation
        Event::listen('navigation', function () {
            return Page::where('visible_in_menu', true)
                ->where('is_active', true)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->get()
                ->filter(function ($page) {
                    switch ($page->visibility) {
                        case 'admins':
                            if (!Auth::check() || is_null(Auth::user()->role)) {
                                return false;
                            }
                            break;

                        case 'customers':
                            if (!Auth::check() || !is_null(Auth::user()->role)) {
                                return false;
                            }
                            break;

                        case 'guests':
                            if (Auth::check()) {
                                return false;
                            }
                            break;

                        case 'logged-in':
                            if (!Auth::check()) {
                                return false;
                            }
                            break;

                        case 'everyone':
                        default:
                            // everyone can see
                            break;
                    }

                    return true;
                })
                ->map(function ($page) {
                    return [
                        'route' => 'custom-pages.show',
                        'name' => $page->title,
                        'params' => ['fallbackPlaceholder' => $page->slug],
                        'icon' => 'ri-pages',
                    ];
                })
                ->toArray();
        });
    }

    public function register()
    {
        //
    }
}
