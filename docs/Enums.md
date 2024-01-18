## Enums

Enums that are shared between the backend and the frontend like the Role and
Permission enums should be generated on the frontend based on the backend enum
file.

### Generating TS Enums
To generate enums you can call `php artisan build:ts-enum`.

### Adding New Generated Enums
To add a new enum to the list of generated enums you need to update
[`App\Console\Commands\BuildTypescriptEnumCommand`](../app/Console/Commands/BuildTypescriptEnumCommand.php).
