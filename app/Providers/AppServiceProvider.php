<?php

namespace App\Providers;

use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.BuilderMacrosServiceProvider
     */
    public function register(): void
    {
        //        if ($this->app->environment('local')) {
        //        }
        // if ($this->app->isLocal()) {
        // app('config')->set('app.aliases', [
        //     'Validator' => 'Illuminate\Support\Facades\Validator',
        //     'Eloquent' => 'Illuminate\Database\Eloquent\Model',
        //     //add other map you want
        // ]);

        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        // }
        $this->app->register(BuilderMacrosServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->app->extend(
                Generator::class,
                fn (Generator $generator) => tap($generator)->seed('1234')
            );
        }

        //for debug bar loggin
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
