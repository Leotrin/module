<?php

namespace Mangosoft\Modules\Composer;

use Composer\Script\Event;

class ModulesInstaller
{
    public static function postInstall(Event $event)
    {
        $composerFile = getcwd() . '/composer.json';
        $composerJson = json_decode(file_get_contents($composerFile), true);

        // Ensure autoload exists
        if (!isset($composerJson['autoload']['psr-4'])) {
            $composerJson['autoload']['psr-4'] = [];
        }

        // Add the Modules namespace if not already present
        if (!array_key_exists('Modules\\', $composerJson['autoload']['psr-4'])) {
            $composerJson['autoload']['psr-4']['Modules\\'] = 'modules/';
            file_put_contents(
                $composerFile,
                json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            echo "✅ Added `Modules\\ => modules/` to composer.json\n";
            echo "ℹ️ Run `composer dump-autoload` to refresh autoload.\n";
        } else {
            echo "ℹ️ `Modules\\` namespace already exists in composer.json\n";
        }
    }
}
