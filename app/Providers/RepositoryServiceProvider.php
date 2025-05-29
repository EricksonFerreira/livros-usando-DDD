<?php

namespace App\Providers;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\AssuntoRepository;
use App\Infrastructure\Repositories\Eloquent\AutorRepository;
use App\Infrastructure\Repositories\Eloquent\LivroRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AutorRepositoryInterface::class,
            AutorRepository::class
        );

        $this->app->bind(
            AssuntoRepositoryInterface::class,
            AssuntoRepository::class
        );

        $this->app->bind(
            LivroRepositoryInterface::class,
            LivroRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
