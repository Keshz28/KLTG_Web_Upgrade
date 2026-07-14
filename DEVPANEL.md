# DevPanel (System Console) — KL The Guide

A standalone, hidden operator console for this site, separate from the normal
`admin/login.php` CMS. It has its own URL, its own auth (a single master key, not
a user login), its own API, and its own dark theme.

> **Security model in one line:** anyone who knows the URL still sees nothing
> unless they also hold the `DEV_MASTER_KEY`. Without a valid key, every API call
> returns **404** — the panel is invisible, not just locked.

---

## How to access it

1. Open **`/xp.php`** in the browser
   (local: `http://localhost/kltheguide.com.my - backup/xp.php`).
2. You get a generic **"System Access"** lock screen. Enter the developer key.
3. The key is validated against `DEV_MASTER_KEY` (from `.env`). On success it is
   stored in `sessionStorage` (`_dmk`) and sent on every request as the
   `X-Dev-K` header.
4. **Lock session** clears the stored key. It is also dropped automatically if the
   server ever rejects it (any 404 from the API bounces you back to the lock).

### Setting / rotating the key

In `.env` (project root — the same file `admin/functions.php` loads):

```
DEV_MASTER_KEY=some-long-random-secret
```

A strong key was generated during setup. **If this value is empty the panel is
completely inaccessible** (every API call 404s). Treat the key like a root
password — never commit a real production key.

> For extra obscurity you can rename `xp.php` to anything (e.g. `_ops7x.php`);
> nothing else references the filename.

---

## Architecture

| Layer | File |
|-------|------|
| Panel (lock screen + UI + API, all in one) | `xp.php` |
| Bootstrap / `$db` / `envv()` / helpers | `admin/functions.php` |
| Ad-gate helpers | `kltg_client_ip()`, `kltg_ads_hidden()` in `admin/functions.php` |
| Ad suppression on public pages | `header.php` |
| Ad-block table | `devpanel_ad_block` (auto-created; `db_migration_devpanel.sql`) |

**Auth flow:** every API call is `POST xp.php` with `dp_action=...` and the
`X-Dev-K` header. The server does a constant-time `hash_equals` of the header
against `DEV_MASTER_KEY`. Any failure — no key configured, no header, wrong key —
returns `abort 404`, not 401/403, so the API is indistinguishable from a
non-existent route. Invalid-key attempts are throttled per IP (10 / 10 min).

---

## Tabs

### Overview
Environment/health snapshot: PHP & MySQL versions, server software, host, DB
status/name/host, disk free/total, **your detected IP**, and whether ads are
currently hidden for you. Read-only.

### Ads  ← the headline feature
Hide **all** advertisements for specific visitor IPs. Everyone else still sees
ads normally.
- Shows your current IP with a one-click "use it".
- Add an IP (IPv4 or IPv6, validated) with an optional note → that visitor sees
  no ads anywhere on the site.
- Remove an IP → ads are restored for it.

How it works: `header.php` calls `kltg_ads_hidden($db)`, which matches the
visitor's real IP (Cloudflare / `X-Forwarded-For` / `REMOTE_ADDR`, validated)
against `devpanel_ad_block`. When matched, the AdSense loader is skipped and a
site-wide `style` rule hides every `ins.adsbygoogle` slot. The check **fails
safe** — a DB error or missing table just means "show ads", so the public site
can never break because of this feature.

### Database
`SHOW TABLES` + a row count per table, sorted by row count. Read-only inspector.

### Users
CRUD over the `users` table (the `admin/login.php` CMS accounts): list, create
(password stored as `md5`, matching the existing login scheme), reset password,
delete. Refuses to delete the last remaining admin so you can't lock yourself out.

---

## API reference

All `POST xp.php`, field `dp_action`, header `X-Dev-K`. Unauthorised → 404.

| dp_action | Purpose |
|-----------|---------|
| `ping` | Key handshake (used by the lock screen) |
| `info` | Environment/health snapshot |
| `db_tables` | Table list + row counts |
| `ads_list` | List blocked IPs + your current IP |
| `ads_add` | Add an IP (`ip`, `note`) to the ad block list |
| `ads_delete` | Remove an IP by `id` |
| `users_list` | List admin users |
| `users_create` | Create admin user (`username`, `email`, `password`) |
| `users_password` | Reset a user's password (`username`, `password`) |
| `users_delete` | Delete an admin user (`username`) |

---

## Cautions

- **Guard the key.** It grants full access (manage users, view DB, change ad
  visibility). Use a long random value; rotate if exposed; never commit it.
- **It writes to the real database.** User create/delete and the ad-block list
  hit live data.
- **No per-operator audit trail** — the panel only knows "holds the key".
- **Apply on production:** ensure `DEV_MASTER_KEY` is set in the production
  `.env`, confirm `/xp.php` loads and that a wrong key shows "Invalid key". The
  `devpanel_ad_block` table is created automatically on first use of the Ads tab;
  `db_migration_devpanel.sql` is provided if you prefer to create it by hand.
