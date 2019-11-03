<?php

namespace App\Providers;

use App\Http\Composers\PostSidebarComposer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

        Carbon::setLocale(config('app.locale'));

        Request::macro('intersect', function ($data) {
            return array_filter($this->only($data));
        });

        $this->registerViewComposers();
    }

    protected function registerViewComposers()
    {
        View::composer('posts.sidebar', PostSidebarComposer::class);
    }
}
