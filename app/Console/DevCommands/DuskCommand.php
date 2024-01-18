<?php

declare(strict_types=1);

namespace App\Console\DevCommands;

use Illuminate\Support\Facades\File;

class DuskCommand extends \Laravel\Dusk\Console\DuskCommand
{
    public function handle(): mixed
    {
        if (!env('GITHUB_ACTIONS') && !File::exists(base_path('.env.dusk.local'))) {
            $this->components->error('Use "scripts/dusk.sh" to run this command.');

            return static::FAILURE;
        }

        return parent::handle();
    }
}
