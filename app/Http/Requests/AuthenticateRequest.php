<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\Credentials;
use Illuminate\Foundation\Http\FormRequest;
use Sourcetoad\RuleHelper\RuleSet;

/**
 * @property-read string $email
 * @property-read string $password
 * @property-read mixed $remember
 */
class AuthenticateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => RuleSet::create()
                ->required()
                ->string(),
            'password' => RuleSet::create()
                ->required()
                ->string(),
            'remember' => RuleSet::create()
                ->boolean(),
        ];
    }

    public function credentials(): Credentials
    {
        return new Credentials($this->email, $this->password);
    }

    public function remember(): bool
    {
        return $this->boolean('remember');
    }
}
