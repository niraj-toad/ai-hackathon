<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!! \Sentry\Laravel\Integration::sentryMeta() !!}
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/favicon.png') }}"/>
    @vite(['resources/scripts/main.ts'])
</head>
<body>
<div
    id="app"
    data-app-url="{{ config('app.url') }}"
    data-app-version="{{ config('services.app.version') }}"
    data-sentry-dsn="{{ config('sentry.dsn') }}"
    data-sentry-environment="{{ config('sentry.environment') }}"
    data-sentry-release="{{ config('sentry.release') }}"
    data-sentry-trace-propagation-targets="{{ config('services.sentry.trace_propagation_targets') }}"
    data-sentry-traces-sample-rate="{{ config('sentry.traces_sample_rate') }}"
></div>
</body>
</html>
