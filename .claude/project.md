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
Run via: `php tests/test.php` (from project root).
Test files are included in numeric order; set `$run_specific_test = 12` to run only test 012.