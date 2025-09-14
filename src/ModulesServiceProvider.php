<?php

namespace Mangosoft\Modules;

use Illuminate\Support\ServiceProvider;
use Mangosoft\Modules\Commands\ModuleListCommand;
use Mangosoft\Modules\Commands\ModuleRemoveCommand;
use Mangosoft\Modules\Commands\MakeModuleCommand;

class ModulesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! is_dir(base_path('modules'))) {
            mkdir(base_path('modules'));
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleCommand::class,
                ModuleListCommand::class,
                ModuleRemoveCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        //
    }
}
