<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;
use Sourcetoad\RuleHelper\RuleSet;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return RuleSet::create()
            ->required()
            ->string()
            ->rule(new Password())
            ->confirmed()
            ->toArray();
    }
}
