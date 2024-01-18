<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BuildOrderLanguageKeys extends Command
{
    protected $signature = 'build:order-language-json-keys {--check : Return error if file required sorting}';
    protected $description = 'Orders the keys in language JSON files alphabetically.';

    public function handle(): int
    {
        $directory = resource_path('scripts/languages');

        if (!File::isDirectory($directory)) {
            $this->error("Provided path is not a directory: {$directory}");

            return Command::FAILURE;
        }

        $files = File::allFiles($directory);

        if (empty($files)) {
            $this->info("No JSON files found in the directory: {$directory}");

            return Command::SUCCESS;
        }

        foreach ($files as $file) {
            // Check if the file has a JSON extension
            if (strtolower($file->getExtension()) !== 'json') {
                $this->info('Skipping non-JSON file: '.$file->getFilename());

                continue; // Skip to the next iteration
            }

            $contents = file_get_contents($file->getPathname());

            if ($contents === false) {
                $this->error("Could not read file: {$file->getFilename()}");

                return Command::FAILURE;
            }

            $originalHash = md5($contents);

            $json = json_decode($contents, true);

            if (!is_array($json)) {
                $this->error('JSON decode not an array');

                return Command::FAILURE;
            }

            ksort($json);

            $sortedJson = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if ($sortedJson === false) {
                $this->error('Sorted JSON showing as false');

                return Command::FAILURE;
            }

            $sortedJson .= "\n";
            $sortedHash = md5($sortedJson);

            // Check if the file has changed
            if ($originalHash !== $sortedHash) {
                if ($this->option('check')) {
                    $this->error('Language file keys need to be sorted: '.$file->getFilename());

                    return Command::FAILURE;
                }

                file_put_contents($file->getPathname(), $sortedJson);
                $this->info('Language file keys sorted: '.$file->getFilename());
            } else {
                $this->info('Language keys are already sorted: '.$file->getFilename());
            }
        }

        return Command::SUCCESS;
    }
}
