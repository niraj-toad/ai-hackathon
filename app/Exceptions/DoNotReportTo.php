<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ReportContext;
use Attribute;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class DoNotReportTo
{
    /** @var Collection<int|string, ReportContext> */
    protected readonly Collection $contexts;

    public function __construct(ReportContext ...$contexts)
    {
        $this->contexts = collect($contexts);
    }

    public function shouldNotReportTo(ReportContext $context): bool
    {
        return $this->contexts->contains($context);
    }
}
