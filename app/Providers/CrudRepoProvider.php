<?php

namespace App\Providers;

use app;
use App\Http\Controllers\ClientOrderController;
use App\Interfaces\CrudRepoInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\ClientOrderRepository;

class CrudRepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->bind(CrudRepoInterface::class ,ClientOrderRepository::class);

        $this->app->when(ClientOrderController::class)
          ->needs(CrudRepoInterface::class)
          ->give(function () {
              return  ClientOrderRepository::class;
          });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
