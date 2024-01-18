# AIH (AI Hackathon Template)
_A Laravel (10.x) project using Postgres_

## Setup
1. Click use this template > Create a new repository
2. Change owner to your personal account
3. `git clone [your github ssh url]`
4. Open project in your IDE
5. Install [SIDT](https://github.com/sourcetoad/DevopsToolKit), and have running.
6. Configure a [PAT for GPR](https://sourcetoad.atlassian.net/wiki/spaces/SI/pages/3648684033/Authenticate+to+GitHub+Packages).
7. `cp .env.example .env`
8. `docker-compose up --build`
9. `scripts/initial_setup.sh`
10. Edit the `/etc/hosts` file on your system and add `127.0.0.1 aihack.docker`
11. Request OpenAI key from Gregg
12. Set key to `OPENAI_API_KEY` in `.env`
13. Open `aihack.docker/` in web browser (be sure to include the trailing `/`)

## FAQ
### How do I change the chatbot's prompt?
Go to `app/Http/Controllers/Api/ChatSessionController.php` and change the `$systemPrompt` variable.

### How do I change the chatbot's prompt?
Go to `app/Http/Controllers/Api/ChatSessionController.php` and change the `$systemPrompt` variable.

### How do I change the look of the chatbot?
Go to `resources/scripts/common/components/chatbot` and change any of the `.vue` files.

### How do I create an embedding?
Go to `app/Http/Controllers/Api/ChatMessageController.php` and change the `findRelatedQuote` function.

### How do I compare embeddings?
Go to `app/Http/Controllers/Api/ChatMessageController.php` and change the `findRelatedQuote` function.

### How do I use the API key?
Open the `.env` file and change the `OPENAI_API_KEY` variable.

### Tests
Unit and feature tests can be run through artisan using `php artisan test`.  Dusk tests can be run using `./scripts/dusk.sh`.

Both test commands support `--filter <method or data provider label>` to run a specific test case or passing a file relative to the project root to run a full test file.

## Common Commands
### Composer
* `composer install` - Installs composer dependencies.
* `composer check-enums` - Runs script to validate enums between PHP and TypeScript.
* `composer phpstan` - Runs PHPStan for static analysis.
* `composer lint` - Runs Pint for linting.
* `composer test` - Runs PHPUnit tests.

### Assets
* `npm run dev` - Spawns Vite dev server for hot reloading.
* `npm run build` - Builds assets for production.
* `npm run check-types` - Runs `vue-tsc` to validate types.
* `npm run lint` - Runs ESLint for `.ts` and `.vue` files.
* `npm run test` - Runs Vitest tests.

## Other Docs
* [Enums](docs/Enums.md)
* [Permissions](docs/Permissions.md)

## Environment Variables

### Sentry
Used for error collection, the following envs need to be set:

* `SENTRY_LARAVEL_DSN` - The DSN for the environment
* `SENTRY_TRACES_SAMPLE_RATE` - Percent in decimal (1.0 = 100%) for how many requests to trace.

## Dependencies

#### Composer (Required)
* `guzzlehttp/guzzle` [(Repo)](https://github.com/guzzle/guzzle) [(MIT License)](https://github.com/guzzle/guzzle/blob/master/LICENSE)
* `laravel/forify` [(Repo)](https://github.com/laravel/fortify) [(MIT License)](https://github.com/laravel/fortify/blob/1.x/LICENSE.md)
* `laravel/framework` [(Repo)](https://github.com/laravel/framework) [(MIT License)](https://github.com/laravel/framework/blob/master/LICENSE.md)
* `laravel/sanctum` [(Repo)](https://github.com/laravel/sanctum) [(MIT License)](https://github.com/laravel/sanctum/blob/2.x/LICENSE.md)
* `laravel/tinker` [(Repo)](https://github.com/laravel/tinker) [(MIT License)](https://github.com/laravel/tinker/blob/master/LICENSE.md)
* `sentry/sentry-laravel` [(Repo)](https://github.com/getsentry/sentry-laravel) [(Apache License 2.0)](https://github.com/getsentry/sentry-laravel/blob/master/LICENSE)
* `sourcetoad/enhanced-resources` [(Repo)](https://github.com/sourcetoad/enhanced-resources) [(MIT License)](https://github.com/sourcetoad/enhanced-resources/blob/master/LICENSE)
* `sourcetoad/rule-helper-for-laravel` [(Repo)](https://github.com/sourcetoad/rule-helper-for-laravel) [(MIT License)](https://github.com/sourcetoad/rule-helper-for-laravel/blob/master/LICENSE.md)

#### Composer (Required Dev)
* `brianium/paratest` [(Repo)](https://github.com/paratestphp/paratest) [(MIT License)](https://github.com/paratestphp/paratest/blob/6.x/LICENSE)
* `fakerphp/faker` [(Repo)](https://github.com/FakerPHP/Faker) [(MIT License)](https://github.com/FakerPHP/Faker/blob/main/LICENSE)
* `laravel/dusk` [(Repo)](https://github.com/laravel/dusk) [(MIT License)](https://github.com/laravel/dusk/blob/7.x/LICENSE.md)
* `laravel/pint` [(Repo)](https://github.com/laravel/pint) [(MIT License)](https://github.com/laravel/pint/blob/main/LICENSE.md)
* `mockery/mockery` [(Repo)](https://github.com/mockery/mockery) [(3-Clause BSD License)](https://github.com/mockery/mockery/blob/master/LICENSE)
* `nunomaduro/collision` [(Repo)](https://github.com/nunomaduro/collision) [(MIT License)](https://github.com/nunomaduro/collision/blob/stable/LICENSE.md)
* `nunomaduro/larastan` [(Repo)](https://github.com/nunomaduro/larastan) [(MIT License)](https://github.com/nunomaduro/larastan/blob/master/LICENSE.md)
* `phpunit/phpunit` [(Repo)](https://github.com/sebastianbergmann/phpunit) [(3-Clause BSD License)](https://github.com/sebastianbergmann/phpunit/blob/master/LICENSE)
* `spatie/laravel-ignition` [(Repo)](https://github.com/spatie/laravel-ignition) [(MIT License)](https://github.com/spatie/laravel-ignition/blob/main/LICENSE.md)

### Features
* Chatbot - [Docs](docs/Chatbot.md)
