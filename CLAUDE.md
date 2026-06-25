<!-- baebeca-conventions v1.2.6 2026-05-28 -->
# CLAUDE.md

Baebeca development guidelines. Apply to all projects.

@.claude/

## Security

- Never read secrets, credentials, tokens, or runtime config files — reading sends contents to the LLM. Files with credentials or excluded in `.gitignore` as local config are sensitive. When in doubt, ask before reading.
- File access is restricted to the current project folder by default. Do not read, search, or write outside of it without explicit user approval.

## Working Style

- Respond in the same language the user writes in.
- For non-trivial changes: briefly summarize planned changes and wait for approval before editing files.
- Only change files relevant to the task. No cleanup, refactoring, or scope creep beyond what was asked.
- Ask before proceeding when requirements are ambiguous or an action is risky or destructive.
- On Windows, prefer PowerShell for shell commands; use Bash only as a fallback.

## Coding Conventions

- User-facing strings: German. All code (variables, functions, comments, class names): English.
- Variables: camelCase starting lowercase (`$fooYesterday`). Constants: `SCREAMING_SNAKE_CASE`.
- Strings: single quotes by default; double quotes only for special chars like `\r\n`.
- No string interpolation. Concatenation: `'Hallo '.$var`, never `"Hallo $var"`.
- Concatenation assignment: `$var.= 'x'` — no space before `.=`.
- Braces: opening `{` on the same line. Space between keyword and `(`. `else`/`else if`/`catch` on a new line after `}`.
- Logical operators: always `&&` / `||`. Exception: `AND` / `OR` in SQL only.
- SQL: every variable in a query must use `real_escape_string($value)`.
- Every function/method must have a documentation comment in the standard format for that language (PHPDoc for PHP, JSDoc for JS, etc.).
- Every parameter and return value must have a declared type where the language supports it.
- Return type colon directly after the closing parenthesis, no space before `:`.
- Indentation: spaces. New code in legacy tab-indented files may use spaces — mixed indentation is acceptable and not a style violation.
- Never write files with a UTF-8 BOM at the start (`\xEF\xBB\xBF` as first bytes). Always save as UTF-8 without BOM.
- Ignore legacy patterns in existing code — follow current rules.

## Code Review

### Workflow

1. Developer requests a Claude review first.
2. Only on `Freigabe` or `Freigabe mit Hinweisen`: developer opens the review request for a second developer.
3. Only after the second developer approves: maintainer may merge.

### /review Command

When called without argument: reviews the currently checked-out branch against `master`.

When called as `/review <ticket>` (e.g. `/review 1234` or `/review #1234`):

1. Strip the leading `#` if present to get the numeric ticket number.
2. Run `git branch -a` and find branches whose name starts with `#<number>-` or `<number>-`.
3. If no match found: report error. If multiple matches: ask the user to clarify.
4. Get the diff with `git diff master...<branch>` — do NOT checkout the branch.
5. Review this diff using all criteria below.

### Criteria

Act as a strict senior developer — reject anything violating style, unnecessarily complex, insecure, or unmaintainable.

- **Security**: inputs validated, no SQL injection / XSS / path traversal / auth bypass, no secrets in output
- **Style**: follows project conventions, no legacy patterns, consistent naming
- **Simplicity**: simplest solution, no premature abstractions or unnecessary layers
- **Readability**: clear control flow, no deep nesting, comments only where non-obvious and always in English
- **Maintainability**: no duplication, clear responsibilities, no hidden side effects
- **Correctness**: solves the actual problem, no scope creep, no unjustified new dependencies

### Verdict

Always one of: `Freigabe` / `Freigabe mit Hinweisen` / `Keine Freigabe`

Output structure: Verdict → Critical issues → Required changes → Recommendations → Positive points (only if relevant).

- Any security issue → `Keine Freigabe`
- Clear style violation → minimum `Freigabe mit Hinweisen`
- Unnecessarily complex when a simpler solution is obvious → `Keine Freigabe`
- Name file and line where possible. Distinguish confirmed finding from suspicion.
- Module uses `.secrets.php`: maintainer must confirm in writing that the file exists on all live servers before merge — otherwise `Keine Freigabe`
- Module uses `load_secrets()`: all properties populated via secrets file must be declared as `protected` in the module class — otherwise `Keine Freigabe`
- Any changed file starts with a UTF-8 BOM (`\xEF\xBB\xBF` as first bytes of the file) → `Keine Freigabe`

## Git & Branch Rules

- Every change requires a dedicated branch: `#<TicketNumber>-<Title>`.
- No direct commits to `master`.
- Commit messages describe the change; no ticket number or title required — the branch name carries that context via no-fast-forward merge.
- Do not commit, push, or switch branches without explicit instruction.
- When committing code Claude wrote or co-authored, append `Co-Authored-By: <model-name>` (no email address).
- Production deployments via tags `release-*` (maintainer only).
- Pipeline skip tags `[pipeline-skip-tests]`, `[pipeline-skip-docs]` are possible.

## CLAUDE.md Maintenance

On every change: increment the version in line 1 and push the updated file to the `project-template` repository.