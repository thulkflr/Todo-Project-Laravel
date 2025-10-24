<?php

namespace App\Providers;

use App\Models\TodoModel;
use App\Policies\TaskPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    protected $policies = [
        TodoModel::class=> TaskPolicy::class
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
