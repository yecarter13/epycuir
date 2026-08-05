<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, function () {
            return new CartService();
        });
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartService = app(CartService::class);
            $view->with('globalCartCount', $cartService->count());
        });
    }
}
