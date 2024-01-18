<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Sourcetoad\RuleHelper\RuleSet;

/**
 * @property-read int|null $page
 * @property-read int|null $per_page
 */
class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission(Permission::ViewUsers) ?? false;
    }

    public function rules(): array
    {
        return [
            'page' => RuleSet::create()
                ->sometimes()
                ->integer()
                ->min(1),
            'per_page' => RuleSet::create()
                ->sometimes()
                ->integer()
                ->min(1)
                ->max(100),
        ];
    }
}
