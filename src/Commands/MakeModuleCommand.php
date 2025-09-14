<?php

namespace Mangosoft\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{

    protected $signature = 'make:module {name} {--api}';
    protected $description = 'Create a new module with providers, routes, controllers, models, views (optionally API-ready)';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $lower = Str::lower($name);
        $modulePath = base_path("modules/{$name}");
        $isApi = $this->option('api');

        if (is_dir($modulePath)) {
            $this->error("Module {$name} already exists!");
            return;
        }

        // Directories
        $dirs = [
            "{$modulePath}/Http/Controllers",
            "{$modulePath}/Http/Controllers/Api",
            "{$modulePath}/Models",
            "{$modulePath}/Providers",
            "{$modulePath}/Database",
            "{$modulePath}/resources/views",
            "{$modulePath}/routes",
        ];
        foreach ($dirs as $dir) {
            mkdir($dir, 0755, true);
        }
        $this->createMigration($name);
        if ($isApi) {
            file_put_contents("{$modulePath}/Http/Controllers/Api/{$name}Controller.php",
                $this->getStub('ApiController', $name, $lower));

            file_put_contents("{$modulePath}/routes/api.php",
                $this->getStub('api-route', $name, $lower));

            file_put_contents("{$modulePath}/Providers/{$name}RouteServiceProvider.php",
                $this->getStub('RouteServiceProviderWithApi', $name, $lower));

            $this->info("✅ API Controller and routes created");
        }else{
            file_put_contents("{$modulePath}/Providers/{$name}RouteServiceProvider.php",
                $this->getStub('RouteServiceProvider', $name, $lower));
        }
        // Files
        file_put_contents("{$modulePath}/Http/Controllers/{$name}Controller.php",
            $this->getStub('Controller', $name, $lower));

        file_put_contents("{$modulePath}/Models/{$name}.php",
            $this->getStub('Model', $name, $lower));

        file_put_contents("{$modulePath}/Providers/{$name}ServiceProvider.php",
            $this->getStub('ServiceProvider', $name, $lower));

        file_put_contents("{$modulePath}/routes/web.php",
            $this->getStub('route', $name, $lower));

        file_put_contents("{$modulePath}/resources/views/index.blade.php",
            $this->getStub('view', $name, $lower));

        $this->info("✅ Module {$name} created at {$modulePath}");

        // Append to bootstrap/providers.php providers (optional, if you want automatic registration)
        $configApp = base_path('bootstrap/providers.php');
        $contents = file_get_contents($configApp);
        $needle = "return [";
        if (strpos($contents, "Modules\\{$name}\\Providers\\{$name}ServiceProvider::class") === false) {

            $contents = str_replace(
                $needle,
                $needle . "\n        Modules\\{$name}\\Providers\\{$name}ServiceProvider::class,",
                $contents
            );
            $this->info($contents);
            file_put_contents($configApp, $contents);
            $this->info("📌 Registered provider in bootstrap/providers.php");
        }
    }

    protected function createMigration($module)
    {
        $migrationsPath = base_path("modules/{$module}/Database/migrations");
        if (!is_dir($migrationsPath)) {
            mkdir($migrationsPath, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $table = strtolower($module) . 's';

        $migrationFile = "{$migrationsPath}/{$timestamp}_create_{$table}_table.php";

        $stub = file_get_contents(__DIR__.'/../stubs/migration.stub');
        $stub = str_replace('{{table}}', $table, $stub);

        file_put_contents($migrationFile, $stub);

        $this->info("Migration created: {$migrationFile}");
    }

    protected function getStub(string $type, string $name, string $lower): string
    {
        $stub = file_get_contents(__DIR__."/../stubs/{$type}.stub");

        return str_replace(
            ['{{name}}', '{{lower}}'],
            [$name, $lower],
            $stub
        );
    }

}
