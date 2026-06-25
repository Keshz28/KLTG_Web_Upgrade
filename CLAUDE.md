# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

KL The Guide (kltheguide.com.my) — a Kuala Lumpur travel/tourism site. Plain PHP multi-page application with no framework and no router: every `*.php` at the web root is a directly-served page. There is no build step or test suite for the public site.

## Local development

This runs under XAMPP on Windows. PHP binary: `d:/xampp/php/php.exe`.

- **Run the site**: serve via XAMPP/Apache, open `http://localhost/kltheguide.com.my - backup/<page>.php`. There is no dev server or hot reload.
- **Syntax-check a file before relying on it**: `"d:/xampp/php/php.exe" -l somefile.php`
- **No automated tests exist.** `admin/test.php`, `admin/test2/`, `admin/contest.php` are ad-hoc scratch pages, not a test suite — do not treat them as one.

### Admin theme CSS build (only place with a build step)

The `admin/` panel is the StartBootstrap **SB Admin 2** Bootstrap 4 theme. SCSS → CSS is built with Gulp; `node_modules` is not committed.

```
cd admin
npm install
npx gulp        # one-off build (compiles scss/, vendors assets)
npm start       # gulp watch + BrowserSync during development
```

The **public** site (`assets/`) has no build: `assets/css/main.css` and `assets/css/variables.css` are hand-edited, and `assets/vendor/` (Bootstrap, AOS, glightbox, swiper) is vendored as-is.

### PHP dependencies

Composer deps live under `admin/` and `admin/vendor/` **is committed** (no install normally needed). If reinstalling: `cd admin && composer install`. Key libs: `vlucas/phpdotenv`, `phpmailer/phpmailer`, `minishlink/web-push`, `nadar/quill-delta-parser`.

## Architecture

### Page composition

Public pages are assembled from shared PHP includes, not templates:
- `header.php` — `<head>` contents: SEO/OG meta, Google Analytics/AdSense, fonts, vendor CSS, `assets/css/main.css`, PWA manifest. Pages set their own `<title>`/meta *before* including it.
- `nav.php`, `footer.php`, `banner.php` — shared chrome.
- `*_old.php`, `*2.php`, `*_orig.php`, `header2.php` etc. are stale alternates kept alongside live pages. Confirm which file the live page actually includes before editing — do not assume by filename.

### Database and the `admin/functions.php` hub

Every dynamic page begins with `include('admin/functions.php')`. That file is the central bootstrap and does much more than connect to the DB — it loads Composer autoload + `.env`, starts the session, opens the `mysqli` connection as **`$db`**, and wires PHPMailer and WebPush. When editing it, assume every public page and the admin panel depend on it.

Content is CMS-driven: pages `SELECT` from MySQL tables (e.g. `indexpage`) and echo the columns. There is no ORM — raw `mysqli_query` with `mysqli_fetch_assoc`. Rich-text fields are stored as **Quill delta** and rendered via `nadar/quill-delta-parser`.

### DB credentials resolution (order matters)

`admin/functions.php` → `envv()` resolves each of `DB_HOST/DB_USER/DB_PASS/DB_NAME`:
1. `.env` loaded by phpdotenv — searched at project root first, then `admin/`. (Note: a `.env` also exists under the oddly-named `--settingsEnv/` directory; the active one is root or `admin/`.)
2. If still unset, a hostname check falls back: `localhost`/`127.0.0.1` → XAMPP defaults (`root`, no password, db `kltheguide`); otherwise hard-coded production placeholders.

When DB connection "works locally but not in prod" (or vice-versa), this resolution chain is almost always the cause.

### Admin CMS

`admin/` is a password-gated CMS (`admin/login.php`). Each public page has a paired editor: `admin/edit-<page>.php` plus a handler in `admin/pagefunctions/edit-<page>.php` that writes the submitted content back to the corresponding table. Adding an editable section means touching the public page, its `edit-*.php`, the matching `pagefunctions/` handler, and the DB table together.

### Background jobs / queue

`admin/cron2.php`, `cron3.php`, `cron4.php` are cron entry points that simply `curl` `functions.php` with a `testqueue` POST to drain an email/push-notification queue — there is no real job runner. Email campaign templates live in `admin/email/`.

### PWA & push

`manifest.json` + `serviceWorker.js` make the public site installable. Web push subscriptions are handled through `minishlink/web-push` inside `functions.php`.

### Schema changes

No migration framework. `db_migration_email_verification.sql` is applied manually (phpMyAdmin / `mysql` CLI). New schema changes should follow the same pattern: a checked-in `.sql` file applied by hand.

## Assets and image weight

Source images in `asset-backups/` are extremely oversized (multi-MB originals used in small UI slots), which causes real page lag. The established fix pattern is optimized copies under `asset-backups/opt/` (PHP-GD downscaled), with the page repointed to the `opt/` path while originals stay untouched. Several large PDFs in `assets/pdf/ebook/` exceed GitHub's 50 MB warning threshold — avoid adding more large binaries; consider Git LFS if this grows.

## Git / deployment

Single contributor, single `main` branch, deployed to GitHub `Keshz28/KLTG_Web_Upgrade`. The repo is the full site tree (no separate build artifact). Commit author identity has been corrected to `Sukesh <surasesukesh@gmail.com>` — ensure local `git config user.email` matches before committing so history stays consistent.
