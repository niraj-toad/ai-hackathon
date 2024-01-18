<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BuildTypescriptEnumCommand extends Command
{
    protected $signature = 'build:ts-enum {enum?} {--check} {--preview}';
    protected $description = 'Builds the TS enum based on the PHP enum.';

    protected const COMMON_OUTPUT_PATH = 'scripts/common/enums';

    /**
     * @var array<class-string<\UnitEnum>, array{outputPath: string, requiresComments: bool}>
     */
    protected array $enums = [
        Permission::class => [
            'outputPath' => self::COMMON_OUTPUT_PATH,
            'requiresComments' => true,
        ],
        Role::class => [
            'outputPath' => self::COMMON_OUTPUT_PATH,
            'requiresComments' => false,
        ],
    ];

    public function handle(): int
    {
        try {
            $enums = $this->argument('enum')
                ? collect($this->getSingleEnumConfig($this->argument('enum')))
                : collect($this->enums);

            if (!$this->option('preview')) {
                $this->components->info(sprintf(
                    '%s %d enum %s...',
                    $this->option('check') ? 'Checking' : 'Generating',
                    $enums->count(),
                    Str::plural('file', $enums->count()),
                ));
            }

            $result = $enums->reduce(
                fn(bool $carry, array $options, string $enumClass): bool => $this->processEnum(
                    $this->getEnumReflection($enumClass),
                    $options['requiresComments'],
                    $options['outputPath'],
                ) && $carry,
                true,
            );

            return $result ? self::SUCCESS : self::FAILURE;
        } catch (\Exception $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function processEnum(
        \ReflectionEnum $enumReflection,
        bool $requireComments,
        string $outputPath,
    ): bool {
        $shortName = $enumReflection->getShortName();

        $cases = collect($enumReflection->getCases())
            ->map(function (\ReflectionEnumUnitCase $case) use ($requireComments) {
                if (!($case instanceof \ReflectionEnumBackedCase)) {
                    throw new \Exception("The {$case->getName()} case is not backed.");
                }

                $docComment = $case->getDocComment();
                $name = $case->getName();

                if (!$docComment && $requireComments) {
                    throw new \Exception("The {$name} case does not have a doc comment.");
                }

                $backingValue = $case->getBackingValue();
                $renderedValue = match (gettype($backingValue)) {
                    'string' => "'$backingValue'",
                    'integer' => (string) $backingValue,
                };

                return collect([
                    $docComment ? "    {$case->getDocComment()}" : null,
                    "    {$case->getName()}: $renderedValue,",
                ])->filter()->all();
            })
            ->flatten();

        /** @var Collection<int, string> $lines */
        $lines = collect([
            '/* eslint-disable max-len */',
            '// !!! This is a generated file. Do not change this file manually.',
            '',
            sprintf('const %s = {', $shortName),
            ...$cases->all(),
            '} as const;',
            '',
            sprintf('type %1$s = typeof %1$s[keyof typeof %1$s];', $shortName),
            '',
            sprintf('export default %s;', $shortName),
            '',
        ]);

        if ($this->option('preview')) {
            $this->components->info(sprintf('Previewing %s...', $enumReflection->getName()));
            echo $lines->map(fn(string $line) => '    '.$line)->join("\n");
            echo "\n";
        } elseif ($this->option('check')) {
            $outputFile = resource_path(sprintf('%s/%s.ts', $outputPath, $shortName));
            $expectedContent = $lines->join("\n");
            $isMatch = file_exists($outputFile) && file_get_contents($outputFile) === $expectedContent;
            $this->components->twoColumnDetail(
                sprintf('Checking %s', $enumReflection->getName()),
                $isMatch ? 'Up to date' : 'Out of date',
            );

            return $isMatch;
        } else {
            $outputFile = resource_path(sprintf('%s/%s.ts', $outputPath, $shortName));
            $this->components->twoColumnDetail($shortName, $outputFile);
            file_put_contents($outputFile, $lines->join("\n"));
        }

        return true;
    }

    /**
     * @param class-string<T> $enumClass
     * @template T of \UnitEnum
     */
    private function getEnumReflection(string $enumClass): \ReflectionEnum
    {
        $enumReflection = new \ReflectionEnum($enumClass);

        if (!array_key_exists($enumReflection->getName(), $this->enums)) {
            throw new \Exception(sprintf('Enum "%s" is not supported.', $enumReflection->getShortName()));
        }

        return $enumReflection;
    }

    /**
     * @return array<class-string<\UnitEnum>, array{outputPath: string, requiresComments: bool}>
     */
    private function getSingleEnumConfig(mixed $enum): array
    {
        if (!is_string($enum)) {
            throw new \InvalidArgumentException('Unknown enum type received.');
        }
        $className = 'App\\Enums\\'.Str::studly($enum);

        if (!array_key_exists($className, $this->enums)) {
            throw new \Exception(sprintf('Enum "%s" is not supported.', $className));
        }

        if (!is_subclass_of($className, \UnitEnum::class)) {
            throw new \InvalidArgumentException(sprintf('Enum "%s" is not a UnitEnum.', $className));
        }

        return [$className => $this->enums[$className]];
    }
}
