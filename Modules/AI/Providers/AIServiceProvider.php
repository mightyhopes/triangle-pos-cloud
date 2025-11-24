<?php

namespace Modules\AI\Providers;

use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}
