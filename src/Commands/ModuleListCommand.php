<?php

namespace Mangosoft\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleListCommand extends Command
{
    protected $signature = 'module:list';
    protected $description = 'List all available modules with status';

    public function handle()
    {
        $modulesPath = base_path('modules');
        if (!File::exists($modulesPath)) {
            $this->warn('No modules directory found.');
            return;
        }

        $modules = File::directories($modulesPath);
        $configApp = file_get_contents(base_path('bootstrap/providers.php'));

        $data = [];
        foreach ($modules as $path) {
            $name = basename($path);
            $provider = "Modules\\{$name}\\Providers\\{$name}ServiceProvider::class";
            $status = str_contains($configApp, $provider) ? 'Enabled' : 'Disabled';
            $data[] = [$name, $status];
        }

        $this->table(['Module', 'Status'], $data);
    }
}
