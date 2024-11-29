<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BuilderMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Builder::macro('whereLike', function (string $field, string $searchTerm): Builder {

            /** @var Builder $this */

            return $this->where(
                $field,
                'LIKE',
                '%'.$searchTerm.'%',
            );
        });

        Builder::macro('whereAnyLike', function (array $fields, string $searchTerm): Builder {

            /** @var Builder $this */

            return $this->whereAny(
                $fields,
                'LIKE',
                '%'.$searchTerm.'%',
            );
        });

        Builder::macro('orderByDynamic', function (string $sort_field, string $sort_value): Builder {

            /** @var Builder $this */

            return $sort_value === 'asc' ?
                $this->orderBy($sort_field)
                :
                $this->orderByDesc($sort_field);
        });
    }
}
