# Deploying KL The Guide to InfinityFree (for testing)

A concrete, repeatable process for uploading **this site** (the plain-PHP KL The Guide
CMS) to InfinityFree free hosting so it can be tested live — using the same
**zip + server-side extract** method the other system was deployed with.

This is written specifically for *this* repo. It is not a generic guide:
the public site has **no build step**, `admin/vendor/` is **already committed**,
and there are **no ES-module scripts**, so most of the classic InfinityFree
JavaScript traps do not apply here. The two things that actually matter for us
are **(1) the database `.env`** and **(2) getting the files up past the 10 MB
File Manager limit**.

---

## What does NOT apply to this site (so we skip it)

- **ES-module / JS MIME-type problem.** Our pages load `assets/js/*.js` as plain
  classic `<script>` tags — there are zero `<script type="module">` tags in the
  codebase. No IIFE rebuild, no Vite config, no `defer` surgery needed.
- **Frontend build step.** The public site (`assets/`) is hand-edited; there is
  nothing to compile. `assets/css/main.css` ships as-is.
- **Composer install on the server.** Not possible on InfinityFree (no SSH), but
  also not needed — `admin/vendor/` is committed to the repo, so the autoload,
  PHPMailer, web-push and Quill parser all travel with the upload.
- **Laravel `public/` restructuring, `@vite`, Blade view cache, artisan seeders.**
  None of it — this is plain PHP served straight from the web root.

---

## Part 1 — InfinityFree account & subdomain

1. Register at [infinityfree.com](https://www.infinityfree.com) → **Client Area** → **Create Account**.
2. Pick a test subdomain (e.g. `kltgtest.infinityfreeapp.com`) or attach a domain.
3. Wait for activation. You'll get **vPanel**, **FTP**, and **File Manager** access.
4. Note your account ID (`if0_xxxxxxxx`) — your DB name, DB user, and FTP paths are all prefixed with it.
5. Site files live under **`htdocs/`**. The document root cannot be moved — our
   pages already expect to be served from the web root, so this is fine: the
   contents of this repo go directly into `htdocs/`.

---

## Part 2 — Create the database and import the schema

InfinityFree is **MySQL only, port 3306, and the host is NOT `localhost`.**

1. **vPanel → MySQL Databases** → create a database. You'll get a name like
   `if0_xxxxxxxx_kltg`.
2. Record these four values — you'll put them in `.env` (Part 3):

   | Value | Where to find it | Example |
   |---|---|---|
   | **DB Host** | shown in vPanel → MySQL Databases | `sqlXXX.infinityfree.com` |
   | **DB Name** | the database you created | `if0_xxxxxxxx_kltg` |
   | **DB User** | same as your account ID | `if0_xxxxxxxx` |
   | **DB Password** | your vPanel password | — |

3. Export the local DB from XAMPP phpMyAdmin. Our real local database is
   **`bluedale2_kltg`** (not the `kltheguide` fallback mentioned in CLAUDE.md).
   Export it as a `.sql` file.
4. In **vPanel → phpMyAdmin**, select the new `if0_..._kltg` database and **Import**
   the `.sql`. If it's too big for the upload limit, gzip it or import the largest
   tables one at a time.
5. Don't forget the manual migration: apply
   [db_migration_email_verification.sql](db_migration_email_verification.sql) too,
   the same way (phpMyAdmin → Import), since there's no migration framework.

---

## Part 3 — Point the app at the InfinityFree DB (the critical step)

Our DB credentials are resolved in
[admin/functions.php](admin/functions.php) by the `envv()` helper, which reads a
`.env` file **before** any fallback. The loader prefers a `.env` at the **project
root**, then falls back to `admin/.env`:

```php
$envDir = is_file($root . '/.env') ? $root : __DIR__;   // root wins
Dotenv::createImmutable($envDir)->safeLoad();
```

So the clean, no-code-change way to configure production is to create a **root
`.env`** with the four InfinityFree values:

```dotenv
# .env  (project root — do NOT commit this)
DB_HOST=sqlXXX.infinityfree.com
DB_USER=if0_xxxxxxxx
DB_PASS=your_vpanel_password
DB_NAME=if0_xxxxxxxx_kltg
```

Why this matters: if no `.env` is present, `functions.php` falls back to a
hostname check. On a non-localhost host it lands on the **placeholder production
block** (`YOUR_PRODUCTION_DB_USER`, etc.), which will fail to connect. A correct
root `.env` short-circuits all of that.

> ⚠️ Keep `.env` out of git and out of any public zip listing. It contains live
> credentials. (It's read server-side only; it is never served as a page.)

---

## Part 4 — Build the upload zip locally

The File Manager rejects single uploads over **10 MB** and is painfully slow with
thousands of small files, so we zip the whole tree and extract it on the server —
the same method used for the other system.

### 4a. Trim the heavy stuff first (recommended for a test deploy)

This repo carries very heavy binaries that will eat InfinityFree's disk/inode
quota and make the zip huge:

- `assets/pdf/ebook/` — several PDFs **over 50 MB** each.
- `asset-backups/` — multi-MB original images (the optimized copies live in
  `asset-backups/opt/`).

For a **testing** deploy, exclude the raw originals and any ebook PDFs you don't
need to test. The live pages should reference `asset-backups/opt/` already; verify
before excluding. Also exclude `.git/`, `admin/node_modules/` (not committed anyway),
and any local `.env`.

### 4b. Make the zip (PowerShell)

```powershell
# from the repo root: d:\xampp\htdocs\kltheguide.com.my - backup
$src  = 'd:\xampp\htdocs\kltheguide.com.my - backup'
$dest = "$env:USERPROFILE\Desktop\kltg_site.zip"

# Stage a clean copy without the heavy/secret folders, then zip it
$stage = "$env:TEMP\kltg_stage"
robocopy $src $stage /E /XD .git admin\node_modules asset-backups changes_script_to_KLTG /XF .env *.zip | Out-Null
Compress-Archive -Path "$stage\*" -DestinationPath $dest -Force
Remove-Item $stage -Recurse -Force
```

Adjust the `/XD` (exclude dirs) and `/XF` (exclude files) lists to taste. Keep
`admin/vendor` IN — it's required at runtime.

---

## Part 5 — Upload and extract on the server

1. Upload `kltg_site.zip` into **`htdocs/`** via File Manager (or FTP/FileZilla —
   more reliable for a large file).
2. Create `htdocs/unzip.php`:

   ```php
   <?php
   $zip = new ZipArchive;
   if ($zip->open(__DIR__ . '/kltg_site.zip') === TRUE) {
       $zip->extractTo(__DIR__ . '/');
       $zip->close();
       echo 'Done.';
   } else {
       echo 'Failed.';
   }
   ```
3. Visit `https://kltgtest.infinityfreeapp.com/unzip.php` → it should print **Done.**
4. **Delete `unzip.php` and `kltg_site.zip` immediately.** A public extraction
   script is a security hole.

> ⚠️ `ZipArchive::extractTo()` does not reliably overwrite existing files. If you
> re-upload a new build and stale pages still load, check the file *dates* in File
> Manager — delete the old files/folder first, then extract again, or upload the
> changed files individually.

---

## Part 6 — Things on InfinityFree that affect THIS app

- **Cron / queue won't run.** `admin/cron2.php`, `cron3.php`, `cron4.php` are meant
  to be hit on a schedule to drain the email/push queue via `functions.php`.
  InfinityFree has no cron daemon, so the queue won't auto-drain. For testing,
  either trigger those URLs manually in a browser, or accept that queued
  email/push won't send. (InfinityFree also blocks most outbound SMTP, so
  PHPMailer sending may not work at all on free hosting — fine for UI testing.)
- **Free SSL.** Enable it in the Client Area so the site loads over `https://`.
- **`.htaccess` MIME overrides are ignored.** We don't rely on any, so this is moot
  — but don't add `AddType`/`Header set Content-Type` expecting it to work.
- **Ad/cache script injection.** InfinityFree may inject a small script into HTML
  responses. Harmless; don't panic at unfamiliar markup in View Source.
- **Disk/inode limits.** This is why Part 4a matters — the raw PDFs and original
  images can blow the quota on a free account.

---

## Part 7 — Smoke test (incognito window)

- [ ] `.env` present at project root with the four InfinityFree DB values
- [ ] `unzip.php` and `kltg_site.zip` deleted from `htdocs/`
- [ ] SSL enabled; site loads over `https://`
- [ ] Homepage (`index.php`) loads **with styling** (`assets/css/main.css` returns 200)
- [ ] DevTools Console: no red errors; DevTools Network: JS/CSS all 200
- [ ] A few CMS-driven pages render their content (e.g. `explorekl.php`,
      `accommodation.php`) — confirms the DB connection end-to-end
- [ ] Admin login at `/admin/login.php` loads and authenticates
- [ ] Submit one form / open one editor to confirm DB reads **and** writes work

---

## Quick debugging reference (this site)

| Symptom | Likely cause | Fix |
|---|---|---|
| Pages show "DB Connection Failed" / 503 JSON | No root `.env`, so `functions.php` hit the `YOUR_PRODUCTION_*` placeholders | Create root `.env` with the vPanel DB values (Part 3) |
| DB connection refused | Used `localhost` or wrong port | Use the `sqlXXX.infinityfree.com` host from vPanel, port 3306 |
| Homepage loads but pages are empty | DB connected but schema not imported | Import the `.sql` + `db_migration_email_verification.sql` via phpMyAdmin |
| Stale pages after re-upload | `ZipArchive` didn't overwrite | Delete old files in File Manager, extract again |
| Can't upload the zip | 10 MB File Manager limit | Use FTP (FileZilla), or trim heavy assets (Part 4a) and re-zip |
| Account over quota / upload fails midway | Raw PDFs / original images too heavy | Exclude `assets/pdf/ebook` originals and `asset-backups/` (Part 4a) |
| Emails never arrive | InfinityFree blocks SMTP + no cron to drain the queue | Expected on free hosting; test email flows elsewhere |

---

*Tailored to the KL The Guide plain-PHP CMS. The general InfinityFree mechanics
(zip-extract upload, non-localhost MySQL, no cron) match the other system's
deployment; the site-specific wiring (root `.env` → `admin/functions.php` `envv()`,
committed `admin/vendor/`, no JS build) is what differs.*
