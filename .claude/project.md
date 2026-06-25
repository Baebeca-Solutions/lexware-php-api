## Project: lexware-php-api

Public PHP middleware for the Lexware Office REST API. Provides a PHP class to create/manage invoices, contacts, vouchers, articles, and more. Also contains helpers for EU VAT logic (tax rates, OSS, Drittland detection).

### Architecture

- `src/LexwareApi.php` — main class, all API calls + tax logic
- `src/LexwareException.php` — custom exception class
- `tests/test.php` — custom integration test runner (not PHPUnit)
- `tests/tests/*.php` — individual test files; numbered prefix controls run order

### Entry Points

- `src/LexwareApi.php` — instantiate with `['api_key' => '...', 'sandbox' => bool]`

## Local Tests

Tests require `tests/_local_settings.php` (not in git) with real Lexware API keys.
Copy `tests/_local_settings_default.php` as starting point.

Set `$php_binary_8` / `$php_binary_74` in local settings to your local PHP installation paths.
Use the binary matching the current branch (PHP 8.x → master, PHP 7.4 → php-7.4-legacy-no-composer branch).

Run all tests:        `[php_binary] tests/test.php`
Run specific test:    `[php_binary] tests/test.php 12`   (runs only test group 012)

## Sensitive Files

Do not read:

- (none)

## Access Control

Readable (explicitly allowed):

- `tests/_local_settings.php` — contains sandbox API keys only, no production credentials