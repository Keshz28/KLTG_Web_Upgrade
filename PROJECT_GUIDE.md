# PROJECT GUIDE — KL The Guide (kltheguide.com.my)

> **Read me first.** This is the onboarding map for the next person (likely an intern) who
> touches this project. It explains *what each file does* and *where to find things*.
> For build/run/deploy mechanics and architecture conventions, see **`CLAUDE.md`** — this
> file is the "file-by-file" companion to it.

---

## 0. Working on this project — stack, workflow & deploy

**Before you change anything, read this section.** It tells you what you're working with and
the order you must do things in.

### What it's built with (the stack)

| Layer | Technology |
|-------|------------|
| Server language | **PHP** (procedural, no framework, no router). Runs on PHP 8 in production, 7.3 fallback handler exists. |
| Database | **MySQL** — accessed with raw `mysqli` (no ORM). Connection opens as `$db` in `admin/functions.php`. |
| Front-end | Plain **HTML + CSS + vanilla JavaScript** (jQuery in a few spots). Vendored libraries: Bootstrap, AOS, GLightbox, Swiper, Isotope. |
| Rich text | Content fields are stored as **Quill delta** and rendered with `nadar/quill-delta-parser`. |
| Local server | **XAMPP** on Windows (Apache + MySQL + PHP). PHP binary: `d:/xampp/php/php.exe`. |
| Live hosting | **cPanel** (Apache + MySQL). Config files (`.htaccess`, `php.ini`, `.user.ini`) are cPanel-generated. |

### The golden rule: localhost first, then live

> **Always test on localhost before pushing live.** Never edit files directly on the live
> server. The flow is one direction: **edit → test on localhost → commit → push → publish via cPanel.**

1. **Run it locally first.** Start Apache + MySQL in XAMPP, then open the page in your browser:
   `http://localhost/kltheguide.com.my - backup/<page>.php`. There is **no dev server, no hot
   reload** — just save the file and refresh the browser.
2. **Syntax-check PHP before you trust it:**
   `"d:/xampp/php/php.exe" -l somefile.php` (should print *"No syntax errors detected"*).
3. **The local database is MySQL via XAMPP.** Manage it through **phpMyAdmin**
   (`http://localhost/phpmyadmin`). The DB actually being served locally is
   **`kltheguidecom_bluedale2_kltg`** — that's what the root `.env` (`DB_NAME`) points at, and it
   is the same name used in production. ⚠️ A stale near-duplicate DB called `bluedale2_kltg` also
   exists in local phpMyAdmin; **it is not the live one** — editing it changes nothing. Schema
   changes are applied **by hand** (run the `.sql` file in phpMyAdmin) — there is no migration tool.
4. **Commit to git** once it works locally (single `main` branch).
5. **Push live via cPanel.** The repository *is* the whole site — there is no separate build
   artifact for the public site. Deploy the files through cPanel; the live MySQL database is
   separate from your local one, so any schema/`.sql` change must be **re-applied on the live
   DB** too.

### What "making a change" usually involves

- **Editing a public page's content** → it's CMS-driven, so most content is changed through the
  **admin panel** (`admin/edit-<page>.php`), *not* by editing the `.php` file. Editing the file
  itself is only for layout/markup changes.
- **Adding a new editable section** → you must touch **four** things together: the public page,
  its `admin/edit-<x>.php`, the matching `admin/pagefunctions/edit-<x>.php` handler, and the
  **MySQL table**. (See §3c.)
- **Changing site-wide look/meta** → `header.php`, `nav.php`, `footer.php`, or
  `assets/css/main.css` (the public CSS is hand-edited — no build).
- **Anything that breaks on every page at once** → look at `admin/functions.php` first (DB,
  session, env). See `CLAUDE.md` for the DB-credential resolution order.

### Don't touch these casually

- `.htaccess`, `php.ini`, `.user.ini` — **cPanel-generated** server config. Change them via the
  cPanel MultiPHP INI Editor, not by hand.
- `admin/vendor/` — committed Composer dependencies. Don't hand-edit.
- Files named `*_old.php`, `*2.php`, `*_orig.php` — stale alternates. Confirm which file the
  live page actually includes before editing (don't assume by filename).

---

## 1. The 30-second mental model

- **Plain PHP, no framework, no router.** Every `*.php` at the web root is a page you can
  open directly in the browser (e.g. `/spa.php`). There is **no build step** for the public
  site. (The *admin* theme has an optional Gulp build — see §5.)
- **Every dynamic page starts with `include('admin/functions.php')`.** That one file is the
  engine room: it connects to MySQL (`$db`), starts the session, loads `.env` + Composer,
  and wires up email (PHPMailer) and web-push. If something breaks everywhere at once, look
  there first.
- **Content is CMS-driven.** Public pages `SELECT` from MySQL tables and echo the columns.
  Each public page has a matching admin editor (`admin/edit-<page>.php`) and a handler
  (`admin/pagefunctions/edit-<page>.php`).
- **Pages are assembled from includes**, not templates: `header.php` (the `<head>`),
  `nav.php` (top menu), `footer.php` (footer).

A typical public page looks like:
```php
<?php include('admin/functions.php'); ?>   // DB + session + libs
... page-specific <title>/meta ...
<?php include 'header.php'; ?>             // <head> contents
<?php include 'nav.php'; ?>                // navigation
... SELECT from DB and echo content ...
<?php include 'footer.php'; ?>            // footer
```

A typical **admin** page looks like:
```php
<?php include('functions.php');           // note: admin/ files include their own copy
if (!isset($_SESSION['username'])) {       // ← auth gate, redirects to login.php
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
```

---

## 2. Root-level files (the public site)

### 2a. Public pages (open directly in browser)

| File | What it does |
|------|--------------|
| `index.php` | **Homepage.** Hero, featured sections, pulls content from the `indexpage` table. |
| `aboutus.php` | About Us / Bluedale Publishing story. |
| `contribute.php` | "Contribute" page (invites user submissions). |
| `advertisewithus.php` | Advertise-with-us info + enquiry. |
| `accommodation.php` | Accommodation listings. Uses `view_on_map_helper.php` + `kltg_mapcoords.php`. |
| `spa.php` | Spa & wellness listings. Same map-helper pattern. |
| `where-to-shop.php` | Shopping listings. Same map-helper pattern. |
| `medical-tourism.php` | Medical tourism listings. Same map-helper pattern. |
| `merchandise.php` | **Store front.** Merchandise items by category. The "Buy Now" button hands off to `order.php`. |
| `order.php` | **Checkout page.** Shows the payment QR, takes buyer details + a **required** payment-receipt upload, writes the row into `merchandise_orders`, then hands the customer to WhatsApp via a `wa.me` link. Admin side: the Orders card in `admin/edit-merchandise.php`. |
| `beyondkl.php` | "Beyond KL" day-trip destinations. Uses `beyondkl_mapcoords.php` (its own inline map button). |
| `explorekl.php` | "Explore KL" attractions. Has its own inline map button. |
| `event.php` | Events listing. |
| `event-details.php` | Single event page (linked from the events listing). |
| `kl-glance.php` | "KL at a Glance" landmark slides. CMS-managed via the `klglance` table (editor: `admin/edit-klglance.php`). |
| `getting-around-kl.php` | Transport / getting-around guide. |
| `travel-tips.php` | Travel tips. |
| `map.php` | Interactive map page. Target of every "View on Map" deep-link button. |
| `blog.php` | **Blog listing.** Renders via `assets/js/blog2.js`, which fetches from `fetch_blogger.php`. |
| `blog-details.php` | **Single blog article.** Renders via `assets/js/blog-details.js`. Linked from the blog listing + sitemap. |
| `ebook.php` | E-book listing. Category tabs come from the `ebook_category` table (managed in `admin/edit-ebook.php`). Links to `ebook-details.php`. |
| `ebook-details.php` | Single e-book (DearFlip flipbook viewer). |
| `lpage.php` | Standalone **landing-page contact form** (uses `assets/css/lpage.css`). Admin side: `admin/landing-page.php`. |
| `verify-email.php` | **Email-verification handler.** Validates the token from a confirmation link, flips `verified = 1` in `emailsub`, and sends the welcome email. ⚠️ The newsletter is now **single opt-in** (`functions.php` sets `verified = 1` at signup), so this page is a legacy/back-up path for links already sitting in people's inboxes — don't delete it while those are live. |

### 2b. Shared includes (chrome — not opened directly)

| File | What it does |
|------|--------------|
| `header.php` | Everything inside `<head>`: SEO/OpenGraph meta, Google Analytics + AdSense, fonts, vendor CSS, `assets/css/main.css`, and the PWA `<link rel="manifest">`. Pages set their own `<title>`/meta **before** including this. |
| `nav.php` | Top navigation bar shared across pages. |
| `footer.php` | Site footer shared across pages. |
| `banner.php` | **Banner-click tracking proxy.** Receives a banner click POST and forwards it to `admin/functions.php` (via cURL) to record reach/clicks. |

### 2c. Data sources & helpers (no direct UI)

| File | What it does |
|------|--------------|
| `fetch_blogger.php` | **Blogger API proxy.** Fetches blog posts (single or paginated list) from the Google Blogger API and **caches** responses to `cache/*.json` for 5 minutes. This is what powers `blog.php` / `blog-details.php`. |
| `view_on_map_helper.php` | Defines `viewOnMapButton()` — renders the shared "View on Map" button for medical-tourism / where-to-shop / spa / accommodation. Pins by exact coords when available, else falls back to a Google search anchored to *Malaysia*. |
| `kltg_mapcoords.php` | **Generated** `title => "lat,lng"` lookup for medical-tourism / shop / spa / accommodation. Returns a PHP array. Regenerate via the resolver loop (see git history). |
| `beyondkl_mapcoords.php` | **Generated** `title => "lat,lng"` lookup for Beyond KL places. Same idea, separate dataset. |
| `chatbot-api.php` | **Gemini proxy for the "KL Travel Concierge" chat bubble.** `assets/js/chatbot.js` POSTs `{message, history}` here; this forwards to Google Gemini and returns the reply. Exists so `GEMINI_API_KEY` never reaches the browser. Reads `.env` directly and deliberately **does not** include `admin/functions.php` (no DB/session cost per message). The bot's personality + its list of linkable pages is the `$systemInstruction` string inside. |
| `ebook-track-download.php` | Records an e-book download (fired from the e-book pages; feeds the download stats). |

### 2d. SEO / PWA / config (don't casually edit)

| File | What it does |
|------|--------------|
| `robots.txt` | Crawler rules. Currently blocks `/admin/` and points to the sitemap. |
| `sitemap.xml` | URL list for search engines. |
| `blog-list.xml` | Supplementary blog URL feed. |
| `ads.txt` | **Google AdSense authorization** (`pub-3696733888071014`). Don't delete — removing it can disrupt ad serving. |
| `manifest.json` | PWA metadata (name, icons, theme color, `start_url`). Referenced by `header.php`. |
| `serviceWorker.js` | PWA service worker: precaches core assets on install and handles web-push notification display/clicks. (Note: it has **no `fetch` handler**, so it warms a cache but does not serve pages offline.) |
| `.htaccess` | Apache config — **cPanel-generated** PHP directives, caching headers, and a trailing-slash redirect. Edit via cPanel MultiPHP INI Editor, not by hand. |
| `php.ini` / `.user.ini` | **cPanel-generated** PHP runtime settings for production. Inert on local XAMPP. Leave as-is. |
| `db_migration_*.sql`, `db_cleanup_*.sql`, `*_export.sql` | **Hand-applied schema changes** — there is no migration tool, so each one is a `.sql` file you run yourself in phpMyAdmin, on **both** local and live DBs. Current set: `email_verification`, `email_dedupe`, `ebook_category`, `ebook_download`, `merchandise`, `merchandise_orders`, `recommendation_pk`, `devpanel`, plus `db_cleanup_bot_subscribers.sql` (purges bot signups) and the `klglance` exports. They are kept as a record of what has been applied — **check whether a given one is already live before re-running it.** |

### 2e. Utility / misc

| File | What it does |
|------|--------------|
| `500.php` | A custom HTTP 500 error page (static HTML). Not wired to `.htaccess` `ErrorDocument` currently. |
| `xp.php` | **🔒 Hidden Dev Panel / system console.** Lives *outside* the normal `admin/` CMS: its own URL, its own auth (the single `DEV_MASTER_KEY` from `.env`, not a user login), its own API. Without a valid key **every request returns a fake 404** — the panel is invisible, not merely locked; if the key is unset, nobody can get in at all. Its headline feature is the **Ads** tab, which hides all ads for one visitor IP (via the `devpanel_ad_block` table + a gate in `header.php`). Full write-up: `DEVPANEL.md`. |
| `gantt_website_redesign.html` | A standalone project-planning Gantt chart. **Not part of the running site** — internal planning doc. |

### 2f. The other docs in this repo

| File | What it does |
|------|--------------|
| `CLAUDE.md` | Architecture + build/run/deploy conventions. This guide's companion. |
| `DEVPANEL.md` | Full documentation of `xp.php` (the hidden dev panel). |
| `APP_API_REFERENCE.md` | API reference for the companion mobile app. |
| `INFINITYFREE_DEPLOYMENT_GUIDE.md` | Notes from a trial deployment on InfinityFree hosting. Live hosting is **cPanel** — treat this as historical. |
| `QA_TEST_PLAN.md`, `TEST_PLAN.md`, `testing-log.md` | Manual QA checklists / test log. There is **no automated test suite**. |
| `admin security.md` | Notes on admin-panel security. |

---

## 3. The `admin/` folder (the CMS)

`admin/` is the password-gated content management system (StartBootstrap **SB Admin 2** theme).
Log in at `admin/login.php`. Almost every admin page begins with `include('functions.php')`
followed by the session guard that bounces you to `login.php` if not logged in.

### 3a. The hub

| File | What it does |
|------|--------------|
| `admin/functions.php` | **The central bootstrap for the whole site.** Loads Composer autoload + `.env`, starts the session, opens the MySQL connection as `$db`, and wires PHPMailer + WebPush. Also handles incoming POST "actions" (e.g. the email/push queue drain via `testqueue`, banner tracking). Every public page and admin page depends on it. |

### 3b. Auth & dashboard

| File | What it does |
|------|--------------|
| `admin/login.php` | Admin login form. |
| `admin/register.php` | Creates a new admin user. |
| `admin/index.php` | Admin **dashboard** — landing page after login, shows pageview stats. |
| `admin/nav.php` | Sidebar navigation (the admin menu). |
| `admin/topnav.php` | Top bar partial (HTML only) included into admin pages. |

### 3c. Page editors (each pairs with a public page + a handler in `pagefunctions/`)

> Pattern: editing one editable section means touching **four** things together — the public
> page, its `admin/edit-<x>.php`, the matching `admin/pagefunctions/edit-<x>.php` handler, and
> the DB table.

| Editor | Edits |
|--------|-------|
| `admin/edit-index.php` | Homepage (`index.php`). |
| `admin/edit-accomodation.php` | Accommodation. |
| `admin/edit-spa.php` | Spa. |
| `admin/edit-places-to-shop.php` | Where to Shop. |
| `admin/edit-medical-tourism.php` | Medical tourism. |
| `admin/edit-beyondkl.php` | Beyond KL. |
| `admin/edit-explorekl.php` | Explore KL. |
| `admin/edit-event.php` | Events. |
| `admin/edit-klglance.php` | KL at a Glance (`klglance` table). ⚠️ Unlike the other editors this one stores **raw text**, not `htmlspecialchars`-escaped text. |
| `admin/edit-highlights.php` | Highlights section. |
| `admin/edit-blog.php` | Blog metadata/content. |
| `admin/edit-ebook.php` | E-books **and e-book categories** — the "E-book Categories" card is full CRUD over the `ebook_category` table. A category's **code is the folder name** on disk, so renaming a code means moving files. |
| `admin/edit-merchandise.php` | **The whole store, in one page.** Four cards: **Store Settings** (payment QR, WhatsApp number), **Categories**, **Products**, and **Orders** (every row from `merchandise_orders`, newest first, with the customer's uploaded payment receipt). This is where you go to look at what someone actually bought via `order.php`. |
| `admin/edit-advertisement.php` | Advertisements (paired with `track_ad_click.php`). |
| `admin/edit-voucher.php` | Vouchers. |
| `admin/landing-page.php` | The `lpage.php` landing page. |

### 3d. Analytics & reporting

| File | What it does |
|------|--------------|
| `admin/pageviews.php` | Pageview report (sums `views` from the `pageview` table by URL). |
| `admin/edit-pageviews.php` | Pageview management/editing view. |
| `admin/blogviews.php` / `admin/blogviews2.php` | Blog view stats (`2` is the newer version). |
| `admin/ebookviews.php` | E-book view stats. |
| `admin/bannerreach.php` | Banner reach/click analytics (data fed by root `banner.php`). |
| `admin/export.php` | Exports data to a downloadable **CSV**. |

### 3e. Email & subscribers

| File | What it does |
|------|--------------|
| `admin/emailcampaign.php` | Compose/send email campaigns to subscribers. |
| `admin/sendemail.php` | **HTML email template** (campaign body; uses an inline `cid:logoimg` logo). Not a page — a template. |
| `admin/welcomeemail.php` | **HTML welcome-email template** for new subscribers. |
| `admin/sub.php` | Subscriber management page (with flash messages). |
| `admin/sub_handler.php` | Backend handler for subscribe form submissions (guards + error handling). |
| `admin/email/` | Folder of additional email-campaign templates. |

### 3f. Web push & background jobs

| File | What it does |
|------|--------------|
| `admin/cron2.php`, `cron3.php`, `cron4.php` | Cron entry points. Each simply cURLs `functions.php` with `testqueue` to **drain the email/push queue**. There is no real job runner — these are the queue tick. |

> The old `admin/vapid.php` (a one-time helper that printed the web-push **private** key to the
> browser) has been **deleted** in the pre-launch cleanup. The VAPID keys now live in `.env`; if you
> ever need to regenerate them, do it from the CLI — don't re-add a page that prints a private key.

### 3g. Ads

| File | What it does |
|------|--------------|
| `admin/track_ad_click.php` | Records an advertisement click (paired with `edit-advertisement.php`). |
| `admin/reorder.php` | Drag-and-drop reordering endpoint (works with `admin/js/dragreorder.js`). |

### 3h. Partials & helpers

| File | What it does |
|------|--------------|
| `admin/errors.php` / `admin/errors2.php` | Small include partials that render the `$errors` array as Bootstrap alerts (used by login/register/forms). No session guard of their own. |

### 3i. Scratch / not production (safe to ignore — see CLAUDE.md)

| File | What it does |
|------|--------------|
| `admin/test.php`, `admin/contest.php`, `admin/test2/` | Ad-hoc scratch pages. **Not a test suite.** |

### 3j. Build & dependencies (admin theme only)

| File | What it does |
|------|--------------|
| `admin/gulpfile.js` | Gulp build for the SB Admin 2 theme: compiles `admin/scss/` → `admin/css/` and vendors assets. `node_modules` is **not** committed (`cd admin && npm install && npx gulp`). |
| `admin/vendor/` | Committed Composer dependencies (phpdotenv, PHPMailer, web-push, quill-delta-parser). Normally no install needed. |
| `admin/scss/`, `admin/css/`, `admin/js/`, `admin/img/` | Theme source/compiled assets. |

---

## 4. Where do I find…?

| I want to… | Go to |
|------------|-------|
| Change site-wide `<head>`, analytics, or meta | `header.php` |
| Change the top menu | `nav.php` (public) / `admin/nav.php` (admin) |
| Change the footer | `footer.php` |
| Fix the DB connection / `.env` resolution | `admin/functions.php` (see `CLAUDE.md` §"DB credentials resolution") |
| Edit a public page's content | `admin/edit-<page>.php` + `admin/pagefunctions/edit-<page>.php` |
| Touch the blog data pipeline | `fetch_blogger.php` (+ `cache/`), `assets/js/blog2.js`, `assets/js/blog-details.js` |
| Add/fix a "View on Map" button | `view_on_map_helper.php`, `kltg_mapcoords.php`, `beyondkl_mapcoords.php` |
| Work on emails | `admin/emailcampaign.php`, `admin/sendemail.php`, `admin/welcomeemail.php`, `admin/email/` |
| Work on web push | `admin/functions.php` (WebPush wiring), `serviceWorker.js`, VAPID keys in `.env` |
| Change what the chatbot says / links to | `chatbot-api.php` (the `$systemInstruction` string) + `assets/js/chatbot.js` for the widget itself |
| See customer orders / change the payment QR | `admin/edit-merchandise.php` (Orders + Store Settings cards) |
| Get into the hidden dev panel | `/xp.php` with the `DEV_MASTER_KEY` from `.env` (see `DEVPANEL.md`) |
| Drain the email/push queue manually | hit `admin/cron2.php` (or `cron3`/`cron4`) |
| Apply a schema change | write a `.sql` file and run it by hand (see `db_migration_email_verification.sql`) |

---

## 5. Build / run / deploy (quick pointer)

Full details live in `CLAUDE.md`. In short:
- **Run locally:** XAMPP/Apache → `http://localhost/kltheguide.com.my - backup/<page>.php`. No dev server.
- **Syntax check before relying on a file:** `"d:/xampp/php/php.exe" -l somefile.php`
- **Admin theme build (only build step):** `cd admin && npm install && npx gulp`
- **Deploy:** the repo *is* the site tree; pushed to GitHub and published via **cPanel**.

---

## 6. ⚠️ Security flags to resolve before / during a live push

These are safe locally but risky on the public server. Address them when deploying via cPanel.

**Still open:**

1. **🔴 Leaked credentials in git history.** The production DB password, SMTP password, and
   `DEV_MASTER_KEY` were committed to the **public** GitHub repo. `.env` has since been untracked,
   but **the old commits still contain the secrets and the credentials have NOT been rotated yet.**
   Untracking a file does not remove it from history. Rotate every secret in `.env` — this is the
   most urgent item on this page.
2. **API key in `fetch_blogger.php`** — the Google Blogger API key is hard-coded. It's a public
   read key, but restrict it (HTTP-referrer lock in Google Cloud) so it can't be reused elsewhere.
3. **Scratch pages** (`admin/test.php`, `admin/contest.php`, `admin/test2/`) — still present, and
   **not gated** like the rest of the admin panel. Delete them before go-live.
4. **`GEMINI_API_KEY`** — lives in `.env` and is only ever used server-side by `chatbot-api.php`.
   Keep it that way; never move the Gemini call into front-end JavaScript. Note `chatbot-api.php`
   has **no rate limiting**, so a scraper could burn your Gemini quota — worth adding a throttle.

**Resolved (kept here so nobody re-introduces them):**

- ~~`info.php`~~ — the `phpinfo()` page has been **deleted**. Don't add it back on a live server.
- ~~`admin/vapid.php`~~ — the helper that printed the web-push **private key** to the browser has
  been **deleted**. Keys live in `.env` now.

---

*Maintainer note:* when you add a new top-level page or admin tool, add a one-line row to the
relevant table above so this guide stays the single source of truth. Each file also carries a
short header memo at the top describing its own job — this guide is the index, those headers
are the local detail.
