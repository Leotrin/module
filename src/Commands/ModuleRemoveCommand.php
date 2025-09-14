<?php

namespace Mangosoft\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleRemoveCommand extends Command
{
    protected $signature = 'module:remove {name}';
    protected $description = 'Remove a module safely';

    public function handle()
    {
        $name = $this->argument('name');
        $modulePath = base_path("modules/{$name}");

        if (!File::exists($modulePath)) {
            $this->error("Module {$name} does not exist.");
            return;
        }

        File::deleteDirectory($modulePath);
        $this->info("✅ Module {$name} removed.");

        // Remove provider from config/app.php
        $provider = "Modules\\{$name}\\Providers\\{$name}ServiceProvider::class,";
        $configApp = base_path('bootstrap/providers.php');
        $contents = file_get_contents($configApp);
        $contents = str_replace($provider, '', $contents);
        file_put_contents($configApp, $contents);

        $this->info("📌 Removed provider from providers.php");
    }
}
