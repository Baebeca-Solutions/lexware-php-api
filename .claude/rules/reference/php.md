<!-- baebeca-project-template v1.0.0 -->
# PHP Review Baseline

General PHP criteria that apply across all projects. Project-specific
criteria still take precedence and live in `.claude/rules/review.md`.

## Security

- Comparing secrets, tokens, or hashes: `hash_equals()`, never `==`/`===`.
- Generating tokens/codes: `random_bytes()`/`random_int()` (CSPRNG), never `rand()`/`mt_rand()`.
- No `@` error suppression — handle the failure explicitly.
- New files declare `declare(strict_types=1);` at the top.
- Passwords: `password_hash()`/`password_verify()`, never `md5()`/`sha1()`.
- Untrusted input never reaches `unserialize()` — object injection risk.
- Strict comparison (`===`/`!==`, `in_array(..., true)`) in auth, token, payment, or state logic.
- File uploads: MIME type, size, and extension validated server-side; filename generated server-side, never taken from the client; stored outside an executable path.

## Robustness

- Constructors only establish invariants — no HTTP calls, file/network I/O, or heavy queries inside one.
- File handles, curl handles, temp files, and locks are closed explicitly after use (`try`/`finally` around the resource).

## Performance

- No database queries or other expensive I/O inside a loop — batch-load related data instead.

## Database

- Writes spanning several persistence calls are wrapped in a transaction or have a compensating mechanism.

## Dependencies (Composer)

- `composer.lock` is committed for application repos.
- `require-dev` packages don't ship in the production image/autoload.
- The PHP platform version matches the CI runner's PHP version.
