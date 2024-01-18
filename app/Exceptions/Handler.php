<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ReportContext;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ReflectionClass;
use Sentry\Laravel\Integration;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            if ($this->shouldNotReport($exception, ReportContext::Sentry)) {
                return;
            }

            Integration::captureUnhandledException($exception);
        });
    }

    protected function getDoNotReportToAttribute(Throwable $exception): DoNotReportTo
    {
        $attributes = (new ReflectionClass($exception))
            ->getAttributes(DoNotReportTo::class);

        $attribute = $attributes[0] ?? null;

        return $attribute?->newInstance() ?? new DoNotReportTo;
    }

    protected function shouldNotReport(Throwable $exception, ReportContext $context): bool
    {
        return $this->shouldntReport($exception)
            || $this->getDoNotReportToAttribute($exception)->shouldNotReportTo($context);
    }
}
