<?php

namespace App\Providers;

use App\Models\Message;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Jumlah pesan belum dibaca dipakai sidebar + bell di layout admin.
        // Disediakan lewat composer dengan cache singkat agar tidak ada query
        // per render layout.
        View::composer('admin.layouts.app', function ($view) {
            $view->with('unreadCount', Cache::remember('admin.unreadCount', 30, function () {
                return Message::where('is_read', false)->count();
            }));
        });
    }
}
