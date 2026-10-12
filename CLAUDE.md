<!-- baebeca-project-template v1.12.6 -->
# CLAUDE.md

Baebeca development guidelines. Apply to all projects.

Project-specific rules live in `.claude/rules/` and load into every session. They extend this file and win where they disagree.

## Security

- Never read secrets, credentials, tokens, or runtime config files — reading sends contents to the LLM. Files with credentials or excluded in `.gitignore` as local config are sensitive. When in doubt, ask before reading.
- File access is restricted to the current project folder by default. Do not read, search, or write outside of it without explicit user approval.
- Regex changes in security-relevant files (e.g. `.htaccess`): think through all URL scenarios first — check a table of every relevant positive/negative case against the regex before writing it.

## Working Style

- Respond in the same language the user writes in. This applies to conversational replies only — never to code, comments, identifiers, or other file contents (see Coding Conventions).
- For non-trivial changes: briefly summarize planned changes and wait for approval before editing files.
- Only change files relevant to the task. No cleanup, refactoring, or scope creep beyond what was asked — including within a file already being edited: don't reformat or touch unrelated lines.
- The unit for that is the function or the block the change touches, never the whole file. Inside it, the conventions are owed: its docblock, its formatting. Its logic is not — that stays untouched unless the task asks for it.
- A type declaration on an existing signature is a change of behavior, not formatting: PHP throws where a caller passes another type. It is made deliberately, with the callers checked, and is never owed just because the function was touched.
- Report an unrelated problem you spot while working; it belongs in its own branch/ticket, never inline in the current change.
- Treat anything not actually checked or verified as unproven — never state it as fact, claim success, or act on an unverified assumption.
- Ask before proceeding when requirements are ambiguous or an action is risky or destructive.
- On Windows, prefer PowerShell for shell commands; use Bash only as a fallback.
- Copying files to a remote host: `scp` the files themselves, also for a few dozen. A detour over an archive pays off from several hundred files on.
- CLAUDE.md files are read hierarchically: from the project root down through every directory on the path to the file being edited, each level overriding the parent for its own subtree. The root project's `.claude/` folder always applies, whichever subtree you work in.
- Persist rules and feedback as git-tracked instructions so the whole team benefits, not as personal local Claude memory — unless the user explicitly asks for local memory.
- Once a conversation has grown long, suggest a `/compact` with a focus line at a natural break — a finished block of work or before a topic change — never in the middle of a task.

## Plan Mode

A revised plan marks its differences to the previous version with emoji markers.

- The legend is the second line of the plan: `🟢 neu · 🟡 geändert · 🔴 entfällt`.
- The marker stands at the start of the heading, bullet or paragraph it applies to.
- A dropped statement stays in place, written out, marked 🔴, plus a half-sentence naming why it goes.
- Every revision marks against the version immediately before it: the markers of the previous round are cleared and its 🔴 entries removed.
- The markers are the only means of marking: no HTML (`<span style="color:…">`), no strikethrough, no diff code block as a change overview at the top — the plan viewer renders neither color nor strikethrough, and a diff block without context is unreadable.

## Writing Style

Applies to every text: comments, docblocks, documentation (`.docs/`, `README.md`, `DEVELOPER.md`), markdown of any kind, review reports. A commit message follows the same brevity and is the one place that carries rationale.

- The shortest form that carries the statement. One sentence per statement, no restating of the code.
- State what holds, positively. No contrast to a former state (avoid "nicht mehr", "kein/keine ... mehr", "anders als vorher"), no rejected alternative, no explanation of what does not happen.
- Rationale, decision and alternative belong in the commit message or the ticket.
- Volume: a docblock is one sentence of description plus its tags, a comment is one line. Longer only where the reader has to know something the code does not show.
- Documentation describes the current state: what the system does and what a reader has to do. An update rewrites the affected passage in place; a note about what changed stays out of it.
- Styleguide and reference docs carry the rule or the fact alone.
- New review criteria: a concise, generic rule (positive whitelist framing preferred, e.g. "only X allowed"), matching the format of the surrounding entries in whichever project-specific review-criteria file it is added to.

## Module Knowledge (KNOWLEDGE.md)

A module or component directory may hold a git-tracked `KNOWLEDGE.md`: an `#` title naming the module or area, `##` sections grouping the entries by topic, and the entries as bullets below them. Prose belongs nowhere in the file — no intro, no text under a heading. A section is added as soon as the file mixes topics, and removed once it holds no entry.

Default is to write nothing. An entry is admissible only if all of these hold:

- It was verified during the work, not assumed.
- It cost time to find out: it contradicted the obvious reading of the code, or it lives outside this repo (runtime, another module, an external system).
- The project code, its docblocks, git history, CLAUDE.md and `.claude/` do not answer it.
- A future session doing unrelated work in this module would act differently knowing it.

Admissible content: cross-code and runtime behavior invisible at the call site (who else writes a field, what a hook triggers, what runs async), and a constraint that must hold which nothing in the module enforces.

Not admissible: rationale, decisions and trade-offs (they belong in the commit message or the ticket), setup and how-to steps, access or credential pointers, restating what the code says, history, session narrative.

- One fact per bullet, one sentence, English, terse. No bold, no sub-bullets.
- Add an admissible entry without asking; an assumption, a suspicion or an open todo never is one.
- Deduplicate against existing entries; delete entries the code has outdated.
- Record a fact as part of the same change, committed together with the code that prompted it.
- Before substantial work on a module, read its `KNOWLEDGE.md` if present. A recalled fact reflects what was true when written — verify it against current code before relying on it.

## Personal TODOs (TODO.md)

Every developer keeps a `TODO.md` in the project root for their own open items — private, listed in `.gitignore`, never committed.

- A todo that surfaces while working on something else goes in there instead of being fixed inline (see Working Style) or forgotten.
- One entry per item, with enough context to pick it up later: what, where (file and line), why it matters, and the direction of a fix.
- Record consciously rejected items too, with the date of the decision.
- Remove an entry once it is done or has its own ticket.

## Coding Conventions

- User-facing strings: German. All code (variables, functions, comments, class names): English.
- A user-facing German text is orthographically and grammatically correct and addresses the reader in the Du-Form. A project sets a different form of address in its `.claude/rules/project.md`.
- Variables: camelCase starting lowercase (`$fooYesterday`). Constants: `SCREAMING_SNAKE_CASE`.
- A local variable and a parameter follow the file they live in: in a file written in snake_case they keep that spelling.
- Strings: single quotes by default; double quotes only for special chars like `\r\n`.
- No string interpolation. Concatenation: `'Hallo '.$var`, never `"Hallo $var"`.
- The concatenation operator stands without a space on either side: `$a.'x'.$b`.
- Concatenation assignment: `$var.= 'x'` — no space before `.=`.
- Braces: opening `{` on the same line. Space between keyword and `(`. `else`/`else if`/`catch` on a new line after `}`.
- Logical operators: always `&&` / `||`. Exception: `AND` / `OR` in SQL only.
- SQL: every variable in a query must use `real_escape_string($value)`.
- Every function/method has a docblock in the standard format for that language (PHPDoc for PHP, JSDoc for JS, etc.): one sentence on what it does for its caller, plus the typed parameter and return tags. More only where the caller has to know something the signature does not show. No `@example`, no walkthrough of internal steps, no "Note:" paragraph, no parameter description repeating the parameter name.
- A changed signature or a changed contract updates the docblock in the same change, including the docblock of a caller whose described contract the change alters. A guard, a log call or an internal branch stays out of it.
- A comment states in one line what the code cannot state: a measured runtime quirk, a constraint nothing enforces, a contract with another file. See Writing Style.
- Every parameter and return value must have a declared type where the language supports it.
- Return type colon directly after the closing parenthesis, no space before `:`.
- Indentation: spaces. New code in legacy tab-indented files may use spaces — mixed indentation is acceptable and not a style violation.
- Never write files with a UTF-8 BOM at the start (`\xEF\xBB\xBF` as first bytes). Always save as UTF-8 without BOM.
- Every text file ends with a single trailing newline (LF). A file a change touches gets its missing trailing newline added, exempt from the scope rule above.
- `.editorconfig` in the project root carries the format the IDE applies on save and on reformat. A project overrides it for a directory with an `.editorconfig` of its own there.
- A method exists for a caller, not for a test. One with a single caller and no rule of its own — one expression, one lookup, one assignment, one query — stays where it is used. A second caller, several branches, or a name the domain already uses justify it.
- A rule in a documentation or rule file is the rule alone. Why it exists, what happens without it and what was rejected belong in the commit message.
- Ignore legacy patterns in existing code — follow current rules.
- Language-specific baselines in `.claude/rules/reference/<language>.md` (see Code Review) apply when writing code too, not just at review time.
- Markdown table of contents: use IntelliJ's `<!-- TOC -->` marker, never GitLab's `[[_TOC_]]` syntax.
- Markdown table in a file a human reads: column-aligned in the source — every cell padded to its column's widest content, so all pipes of a column stand in one text column and the separator row spans the same width. Width counts characters, never bytes: a shell measures it with `wc -m` under `LC_ALL=C.UTF-8`, never with `awk length`. `CLAUDE.md` and everything under `.claude/` address an LLM alone and are written for the token count instead: their tables stay compact, one space around each cell.

## Unit Tests

Applies to projects that have a unit test setup. Where none exists, this section is out of scope: note the gap in `TODO.md` and treat introducing the setup as its own task, never as a side effect of an unrelated change.

The requirement covers application code. A test harness is what the suite itself verifies, and a CI or tooling script in a language the suite does not cover has nowhere to be tested — for both, neither a missing test nor a refactoring proposed to make one possible is a finding. Each project names the directories this scope maps to in `.claude/rules/review-project.md`.

Every new or changed method requires a check whether a unit test — one running without environment, so no database, filesystem, session, HTTP or external service — is possible for it. Work through, in this order:

- **Possible and none exists** → write it, as part of the same change.
- **One exists** → verify it still covers the changed behavior and sharpen it where possible: a negative assertion like "output does not contain X" passes on a crash too, so assert the concrete expectation.
- **Untested cases remain** → add them: edge values, empty and invalid input, error paths, every branch of a new condition.
- **Not possible without environment** → check first whether a trivial refactoring decouples it: pull the pure logic into its own method, or take the environment-bound value as a parameter. Extract only where the extracted logic stands on its own — several branches, a rule worth its own name, or logic used in more than one place. A single condition or a handful of scalars stays where it is, untested.
- **Genuinely not decouplable** → state why in the change description, and cover the behavior with an integration test instead.

A method that only orchestrates environment-bound calls is the one case with nothing to unit test. Pure logic — validation, parsing, formatting, calculation, token and string handling — always has something.

## Finishing a Change

A review reads every changed file in full plus what depends on it (see `.claude/rules/review.md`, `/review Command`, step 3). A change is finished once it has been checked the same way: for every changed signature, contract, hook title or string-keyed request parameter, grep the callers and usages and adjust them in the same commit. A project names the cases this maps to in `.claude/rules/project.md`.

Two scripts do the mechanical half. They run in every pipeline, and locally ahead of a review of the checked-out branch:

- `.gitlab/scripts/check-conventions.sh` decides the conventions above on the lines a change adds — docblock, parameter and return type, quoting, concatenation, braces — and the trailing newline of every text file the change touches. Run it with `--file <path>` on a file you just wrote.
- `.gitlab/scripts/check-impact.sh` names what the change pulls along: the call sites of every signature it touches, the subclasses of every class it changes, and the obligations the project attaches. Its `PRUEFEN` list is the work; nothing decides it for you.

What the editor hook reports is fixed at once and named in the answer — file, line and rule, in one line. The developer learns what the check caught rather than only seeing the corrected file.

## Announcing a Change

Where the developer asks for it, the answer delivers the text of a Connect post, finished, in a code block, ready to paste and send. The developer decides which change the others have to know about, and when the post goes out.

The text is German and holds four parts:

- The tag `@@Entwicklung` on the first line.
- What the change affects — project, module or file.
- One sentence on what it does.
- The step it asks of the other developers, where it asks one.

```
@@Entwicklung
`src/foo/bar.ext` — validateToken() prüft den Mandanten selbst und nimmt allein das Token entgegen.
Wer die Funktion aufruft, passt seinen Aufruf an.
```

## Code Review

The criteria, the workflow and the output format of both review commands live in `.claude/rules/review.md` and `.claude/rules/review-merge.md`, which `project-template` distributes. A project extends them in `review-project.md` and `review-merge-project.md` beside them.

Every criterion there enforces a rule of the writing side: the conventions above, `.claude/rules/project.md`, or a language baseline under `.claude/rules/reference/`. A criterion with no such counterpart is a trap - it asks of a review what nobody was told while writing.

## Git & Branch Rules

- Every change requires a dedicated branch: `#<TicketNumber>---<Title>` (three dashes after the ticket number), optionally grouped under one or more folder levels (`<Folder>/.../#<TicketNumber>---<Title>`). Everything that reads a branch name strips the folder path first. A folder name and a branch name cannot collide — `foo` and `foo/bar` cannot both exist.
- No direct commits to `master`.
- The GitLab instance (`git.baebeca.de`) is reachable **only via HTTPS, never SSH** — use HTTPS remotes and token auth (in CI: `https://gitlab-ci-token:${CI_JOB_TOKEN}@git.baebeca.de/...`); do not configure `git@`/SSH remotes or deploy keys.
- Commit messages describe the change; no ticket number or title required — the branch name carries that context via no-fast-forward merge.
- Do not commit, push, or switch branches without explicit instruction — including after a series of unrelated approvals; only a current, explicit instruction to commit counts.
- A push to a branch with an open merge request needs the developer's go for that push, given after the local tests worth running first have run; a general go-ahead to finish the work covers none.
- Before committing, verify with `git status` and `git branch --show-current` that the expected branch is active.
- Production deployments via tags `release-*` (maintainer only).

## Synced Files

A file carrying the marker `baebeca-project-template vX.Y.Z` (line 1, below a shebang, or below YAML frontmatter) comes from `project-template`, which distributes it to every project in its `deploy-targets.md`; a project type named in brackets behind a target adds the files under `types/<type>/`, which land at the same path in the project.

- Change a synced file in the project, increment its marker version, then copy it to `project-template` — via PowerShell on Windows, never over a newer copy there, never the other direction. Exception: the checks under `.gitlab/scripts/`, `.githooks/` and the script test runner are changed only in `project-template`.
- A file marked `baebeca-project-template seed` came from `project-template` once and belongs to the project: it is changed there, with the project's entries below `# Project related`, and never synced.
- A project opts out of a template file by covering its path in its own `.gitignore` and removing a tracked copy (`git rm --cached`).
- A synced file holds no project value. What only one project knows lives in a project file beside it (`*-project.*`, `rules/project.md`), and the synced side names that extension point.
- The full rules — mediums, naming, project types, contracts — are in `project-template/.claude/rules/synced-files.md`; read them before creating or restructuring a synced file.
