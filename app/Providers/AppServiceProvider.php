<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
/**
* @method static Builder whereAnyLike(array $columns,string $searchTeram)
 * @method static Builder orderByDynamic(string $sort_field, string $sort_value)
*/
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //        if ($this->app->environment('local')) {
        //        }
        // if ($this->app->isLocal()) {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        // }
        $this->app->register(BuilderMacrosServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
