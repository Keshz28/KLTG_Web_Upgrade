# Admin Security — KLTG Admin Panel

**Date applied:** 2026-06-17  
**Scope:** `admin/` directory — all PHP pages, function handlers, and pagefunctions

---

## Summary of fixes applied

| # | Issue | Severity | Status |
|---|-------|----------|--------|
| 1 | Missing `exit` after session redirects | Critical | ✅ Fixed |
| 2 | MD5 password hashing | High | ⚠️ Pending |
| 3 | Local File Inclusion in `sendinternal` | Critical | ✅ Fixed |
| 4 | CSRF protection on all admin forms | High | ✅ Fixed |
| 5 | `register.php` publicly accessible | High | ✅ Fixed |
| 6 | No login rate limiting | High | ✅ Fixed |
| 7 | Hardcoded SMTP password in source code | High | ✅ Fixed |
| 8 | No `admin/.htaccess` security layer | Medium | ✅ Fixed |
| 9 | No session regeneration on login | Medium | ✅ Fixed |
| 10 | No session timeout | Medium | ✅ Fixed |

---

## Fix details

---

### Fix 1 — `exit` after every session redirect

**Problem:** Every admin page called `header('location: login.php')` but had no `exit` after it. PHP continued executing the full page after the redirect header was sent. An attacker using a tool like `curl` or a browser extension that blocks redirects could see the full page content without being logged in.

**Files changed:** 22 admin PHP files + `admin/functions.php`

```
admin/index.php               admin/blogviews.php
admin/blogviews2.php          admin/bannerreach.php
admin/ebookviews.php          admin/contest.php
admin/pageviews.php           admin/edit-pageviews.php
admin/edit-index.php          admin/edit-blog.php
admin/edit-ebook.php          admin/edit-explorekl.php
admin/edit-beyondkl.php       admin/edit-accomodation.php
admin/edit-highlights.php     admin/edit-klglance.php
admin/edit-medical-tourism.php  admin/edit-spa.php
admin/edit-event.php          admin/emailcampaign.php
admin/landing-page.php        admin/sub.php
admin/functions.php  (login + register redirects)
```

**Fix applied:** Added `exit;` after every `header('location: ...')` call in both the session check block and the logout block.

```php
// Before (broken)
if (!isset($_SESSION['username'])) {
    header('location: login.php');  // page kept running
}

// After (correct)
if (!isset($_SESSION['username'])) {
    header('location: login.php');
    exit;
}
```

---

### Fix 3 — Local File Inclusion (LFI) in `sendinternal`

**Problem:** `admin/functions.php` accepted a file path from `$_POST['file']` and passed it directly to `require_once`. A logged-in attacker could include any PHP file on the server — combined with a file upload this would be remote code execution.

```php
// Before (dangerous)
$file = $_POST['file'];
require_once $file;   // could be ../../.env, /etc/passwd wrapper, etc.
```

**File changed:** `admin/functions.php` — `sendinternal` and `queuemail` blocks

**Fix applied:** Replaced with an explicit allowlist of the 14 valid email templates. Any other value is rejected with HTTP 400 and logged. The path is constructed server-side from `__DIR__`.

```php
$allowed_email_templates = [
    'article.php', 'billboard.php', 'article-061123.php', /* ... all 14 */
];
$requested = basename($_POST['file'] ?? '');
if (!in_array($requested, $allowed_email_templates, true)) {
    error_log('sendinternal: blocked disallowed template: ' . $requested);
    http_response_code(400);
    exit;
}
$file = __DIR__ . '/email/' . $requested;
require_once $file;
```

Both `sendinternal` and `queuemail` now also require an active admin session before processing.

---

### Fix 4 — CSRF protection

**Problem:** Every admin form had no CSRF token. A logged-in admin clicking a malicious external link could silently trigger any admin action (delete content, send mass email, send push notifications, etc.).

**Files changed:** `admin/functions.php`, `admin/topnav.php`

**Fix applied — three layers:**

**Layer 1: Token generation** (`admin/functions.php`, runs on every page load)
```php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

**Layer 2: Helper functions** (`admin/functions.php`)
```php
function csrf_token(): string { return $_SESSION['csrf_token'] ?? ''; }
function csrf_field(): string { /* returns hidden input HTML */ }
function csrf_check(): void   { /* validates or dies with 403 */ }
```

`csrf_check()` uses `hash_equals()` for timing-safe comparison. On failure it returns HTTP 403, logs the event, and stops execution.

**Layer 3a: JS auto-injection** (`admin/topnav.php`)

Since `topnav.php` is included on every admin page, a single script block auto-injects the token into every `<form>` at runtime — no individual form files need to be touched.

```javascript
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        if (!form.querySelector('input[name="csrf_token"]')) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'csrf_token'; inp.value = token;
            form.appendChild(inp);
        }
    });
});
```

**Layer 3b: Server-side validation** (`admin/functions.php`)

Individual `csrf_check()` calls on all direct admin POST handlers:
- `sendmail`
- `sendinternal`
- `queuemail`
- `sendpushnotification`

Plus one centralized guard just before the pagefunctions block — covers all 26 pagefunctions handlers without touching any of them:

```php
// Only fires for admin sessions on admin pages
if (!empty($_POST) && isset($_SESSION['username']) && strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    csrf_check();
}
```

---

### Fix 5 — `register.php` gated behind admin session

**Problem:** `admin/register.php` was publicly accessible. Anyone who could reach the URL could create an admin account (the only gate was knowing the `PASSBD` env variable).

**File changed:** `admin/register.php`

**Fix applied:** Session check added at the top — you must already be a logged-in admin to access the registration page.

```php
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit;
}
```

---

### Fix 6 — Login rate limiting

**Problem:** The login form had no lockout after failed attempts. Brute-force attacks were unrestricted.

**File changed:** `admin/functions.php`

**Fix applied:** File-based rate limiter (per IP, no DB changes required). After 5 failed attempts within 15 minutes, further login attempts are blocked until the window resets.

Three helper functions added:
- `login_rate_limit_check()` — returns an error message string if the IP is locked, or `null` if clear
- `login_rate_limit_fail()` — increments the fail counter for the current IP
- `login_rate_limit_clear()` — resets the counter on successful login

Rate limit files are stored in the system temp directory, keyed by `md5($ip)`. They expire automatically after 15 minutes.

---

### Fix 7 — Hardcoded SMTP password removed

**Problem:** `admin/functions.php` had the real SMTP password `'BluedaleMarketing#001'` as a PHP string fallback — visible in source code and in Git history.

```php
// Before
$pass = $_ENV['MAIL_PASS4'] ?? 'BluedaleMarketing#001';

// After
$pass = $_ENV['MAIL_PASS4'] ?? '';
```

**File changed:** `admin/functions.php` — `send_email_html()` function

**Action required:** Ensure `MAIL_PASS4` is set in `.env`. If it is missing, email sending fails gracefully (logs an error, returns false) rather than using a hardcoded fallback.

**Note:** Rotate the SMTP password on the mail server since it was previously in source code. Update `.env` with the new password.

---

### Fix 8 — `admin/.htaccess` security layer

**Problem:** There was no `.htaccess` in the `admin/` directory — no server-level protection before PHP even runs.

**File created:** `admin/.htaccess`

**Rules active immediately:**
- `Options -Indexes` — disables directory listing
- `<FilesMatch>` — blocks direct access to `.log`, `.json`, `.sql`, `.env`, `.bak` files inside `admin/`
- `X-Frame-Options: DENY` — prevents admin pages from being embedded in iframes (clickjacking protection)
- `X-Content-Type-Options: nosniff` — prevents MIME-type sniffing
- `Referrer-Policy: no-referrer` — does not leak admin URLs to external sites

**HTTP Basic Auth (second password layer — requires manual setup):**

The `.htaccess` includes a commented-out Basic Auth block. To activate it:

1. Generate a hashed password file (run this on the server):
   ```
   htpasswd -c /home/YOUR_CPANEL_USERNAME/private/.htpasswd adminuser
   ```
2. Edit `admin/.htaccess` and uncomment the `<RequireAll>` block, replacing the `AuthUserFile` path with your real path.
3. Save — the browser will now prompt for a username/password **before** the PHP login page is shown.

---

### Fix 9 — Session regeneration on login

**Problem:** After a successful login, the session ID was not regenerated. A session fixation attack could allow an attacker who obtained a pre-login session ID to take over the session after the victim logs in.

**File changed:** `admin/functions.php` — login success block

**Fix applied:**
```php
session_regenerate_id(true);  // called immediately on successful login
```

---

### Fix 10 — 30-minute session timeout

**Problem:** Admin sessions lived indefinitely. A logged-in admin who forgot to log out (or whose machine was compromised) remained authenticated forever.

**File changed:** `admin/functions.php`

**Fix applied:** On every admin page load, the last-activity timestamp is checked. If more than 30 minutes have passed since the last request, the session is destroyed and the user is redirected to `login.php`.

```php
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
$_SESSION['last_activity'] = time();  // refreshed on every admin page load
```

---

## Remaining item (not yet fixed)

### MD5 password hashing (High severity)

**Problem:** Passwords are stored as `md5($password)` — MD5 is a general-purpose hash, not a password hash. Any leaked database gives an attacker all passwords instantly via online rainbow tables. Common passwords crack in under a second.

**What needs to change:**
- Replace `md5($password)` in the login and register blocks with `password_hash($password, PASSWORD_BCRYPT)`
- Replace the login comparison with `password_verify($password, $storedHash)`
- Migrate existing password hashes in the `users` table (existing users must reset their password, or a hybrid approach can upgrade the hash transparently on next login)

**Files to change:** `admin/functions.php` — `REGISTER USER` and `LOGIN USER` blocks  
**DB impact:** `users.password` column must hold the longer bcrypt hash (VARCHAR 255 is sufficient)

---

## How to activate HTTP Basic Auth (Fix 8)

1. SSH into the server or use cPanel Terminal
2. Run: `htpasswd -c /home/YOUR_CPANEL_USERNAME/private/.htpasswd adminuser`
3. Enter a strong password when prompted
4. Open `admin/.htaccess` and uncomment the `<RequireAll>` block
5. Set `AuthUserFile` to the absolute path from step 2
6. Test by visiting `/admin/` — you should see a browser password prompt before the login page

---

## Files changed summary

| File | Changes |
|------|---------|
| `admin/functions.php` | CSRF token generation, CSRF helpers, rate limiter functions, session timeout, session regeneration, LFI fix (sendinternal/queuemail), session+CSRF guards on admin POST handlers, centralized CSRF guard before pagefunctions |
| `admin/topnav.php` | CSRF JS auto-injection script |
| `admin/register.php` | Session check — must be logged in to access |
| `admin/.htaccess` | Created — directory listing off, file type blocks, security headers, Basic Auth template |
| `admin/index.php` + 21 other admin pages | `exit` added after all session redirects and logout redirects |
