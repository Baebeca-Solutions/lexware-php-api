<!-- baebeca-project-template seed -->
## Project: lexware-php-api

Public PHP middleware for the Lexware Office REST API. Provides a PHP class to create/manage invoices, contacts, vouchers, articles, and more. Also contains helpers for EU VAT logic (tax rates, OSS, Drittland detection).

### Architecture

This branch is the legacy version for PHP 7.4 without Composer, see `.claude/rules/php-7.4.md`.

- `lexoffice-php-api.php` — class `lexoffice_client` with all API calls + tax logic, and class `lexoffice_exception`
- `callback.php`, `example.php` — webhook receiver and usage example
- `tests/test.php` — custom integration test runner (not PHPUnit)
- `tests/tests/*.php` — individual test files; numbered prefix controls run order

### Entry Points

- `lexoffice-php-api.php` — load via `require_once`, instantiate `lexoffice_client` with `['api_key' => '...', 'sandbox' => bool]`

## Local Tests

Tests require `tests/_local_settings.php` (not in git) with real Lexware API keys.

Set `$php_binary_74` in local settings to your local PHP 7.4 installation path.

Run tests:            `[php_binary_74] test.php` from within `tests/`
Run specific test:    set `$run_specific_test = 12;` in local settings (runs only test group 012, empty runs all)

## Documentation

User documentation lives in `docs/` (German, Du-Form), upgrade notes in `UPGRADE.md`.

- On any user-facing API change (new methods, changed signatures, new behaviour), update `docs/` in the same commit.
- A breaking change or a new optional parameter gets an entry in `UPGRADE.md`.
- Both branches carry their own `docs/`: `master` documents the Composer version, `php-7.4-legacy-no-composer` the legacy version. A change ported to the other branch ports its docs too, adapted to that branch's API.

## Sensitive Files

Do not read:

- (none)

## Access Control

Readable (explicitly allowed):

- `tests/_local_settings.php` — contains sandbox API keys only, no production credentials
