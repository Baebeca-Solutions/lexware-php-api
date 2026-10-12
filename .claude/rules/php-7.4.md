## PHP 7.4

This branch targets PHP 7.4 without Composer: users load `lexoffice-php-api.php` via `require_once`. Every change runs unchanged on PHP 7.4.

### Packaging

- The library stays the single file `lexoffice-php-api.php`: no namespace, no autoloader, no Composer dependency.
- Extensions are limited to the ones in use: curl, json, mbstring, fileinfo.
- Tests run with the PHP 7.4 binary (`$php_binary_74` in `tests/_local_settings.php`).

### Language

Available: scalar and nullable types (`?string`), `void`, `object`, `iterable`, typed properties, arrow functions `fn() =>`, `??=`, array spread with numeric keys, trailing comma in call arguments.

Only PHP 7.4 syntax is allowed. This excludes in particular:

- PHP 8.0: union types (`int|string`), `mixed`, `static` return type, constructor property promotion, named arguments, `match`, nullsafe `?->`, `throw` as an expression, attributes `#[...]`, `catch` without a variable, `$object::class`, trailing comma in a parameter list or closure `use` list
- PHP 8.1+: enums, `readonly`, `never`, first-class callables `foo(...)`, `new` in initializers, intersection types, typed class constants, `#[\Override]`, property hooks, asymmetric visibility

A type PHP 7.4 cannot declare (union, `mixed`, `false`) goes into the `@param`/`@return` tag alone; this satisfies the typing rule in CLAUDE.md.

`.editorconfig` sets `ij_php_comma_after_last_parameter_style = always`: the IDE formatter adds a trailing comma to parameter lists, which is a parse error on PHP 7.4 and has to be removed.

### Functions

Only functions of PHP 7.4 core and the extensions above. Replacements for common newer functions:

| PHP 8+ | PHP 7.4 |
|---|---|
| `str_contains($h, $n)` | `strpos($h, $n) !== false` |
| `str_starts_with($h, $n)` | `strncmp($h, $n, strlen($n)) === 0` |
| `str_ends_with($h, $n)` | `$n === '' \|\| substr($h, -strlen($n)) === $n` |
| `get_debug_type($v)` | `is_object($v) ? get_class($v) : gettype($v)` |
| `array_is_list($a)` | `$a === [] \|\| array_keys($a) === range(0, count($a) - 1)` |
| `preg_last_error_msg()` | `preg_last_error()` |
| `json_validate($s)` | `json_decode($s)`, then `json_last_error() === JSON_ERROR_NONE` |
| `array_find()`, `array_any()`, `array_all()` | `foreach` with early return |

### Runtime behaviour

- `0 == 'abc'` is `true`: compare numbers and strings strictly.
- Internal functions emit a warning and return `null`/`false` on a wrong argument type instead of throwing a `TypeError`.
- `sort()`, `usort()` and friends sort unstably: a comparator decides every tie itself.
- `curl_init()` returns a resource, checked with `=== false`.
