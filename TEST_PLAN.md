# Master Test & Launch Plan
**Products:** KLTG Website · Bluedale Website (Admin Dashboard) · KLTG Flutter App  
**Deployment target:** 20 June 2026 (Friday)  
**Plan period:** 16 June – 31 July 2026  
**Weekly anchors:** Each Monday starting 23 June  
**Prepared by:** Sukesh

---

## Gantt Overview

```
PRODUCT / PHASE                  | W0 Jun16-20 | W1 Jun23-27 | W2 Jun30-Jul4 | W3 Jul7-11  | W4 Jul14-18 | W5 Jul21-25 | W6 Jul28-31
---------------------------------|-------------|-------------|---------------|-------------|-------------|-------------|------------
KLTG WEBSITE                     |             |             |               |             |             |             |
  Pre-deploy prep                | ▓▓▓▓▓▓▓▓▓▓▓ |             |               |             |             |             |
  🚀 Deployment (Jun 20)         |          🚀 |             |               |             |             |             |
  Post-launch smoke test         |             | ▓▓▓▓▓▓▓▓▓▓▓ |               |             |             |             |
  Functional / regression        |             |             | ▓▓▓▓▓▓▓▓▓▓▓▓▓ |             |             |             |
  Performance & SEO audit        |             |             | ▒▒▒▒▒▒▒▒▒▒▒▒▒ | ▓▓▓▓▓▓▓▓▓▓▓ |             |             |
  Admin CMS & Security           |             |             |               | ▒▒▒▒▒▒▒▒▒▒▒ | ▓▓▓▓▓▓▓▓▓▓▓ |             |
  Monitoring & maintenance       |             | ░░░░░░░░░░░ | ░░░░░░░░░░░░░ | ░░░░░░░░░░░ | ░░░░░░░░░░░ | ▓▓▓▓▓▓▓▓▓▓▓ |
  Final sign-off                 |             |             |               |             |             |             | ▓▓▓▓▓▓▓▓▓▓▓
---------------------------------|-------------|-------------|---------------|-------------|-------------|-------------|------------
BLUEDALE WEBSITE (ADMIN DASH)    |             |             |               |             |             |             |
  Pre-deploy prep                | ▓▓▓▓▓▓▓▓▓▓▓ |             |               |             |             |             |
  🚀 Deployment (Jun 20)         |          🚀 |             |               |             |             |             |
  Post-launch smoke test         |             | ▓▓▓▓▓▓▓▓▓▓▓ |               |             |             |             |
  Dashboard functional testing   |             |             | ▓▓▓▓▓▓▓▓▓▓▓▓▓ |             |             |             |
  Admin CMS deep validation      |             |             | ▒▒▒▒▒▒▒▒▒▒▒▒▒ | ▓▓▓▓▓▓▓▓▓▓▓ |             |             |
  Security & access testing      |             |             |               |             | ▓▓▓▓▓▓▓▓▓▓▓ |             |
  Monitoring & maintenance       |             | ░░░░░░░░░░░ | ░░░░░░░░░░░░░ | ░░░░░░░░░░░ | ░░░░░░░░░░░ | ▓▓▓▓▓▓▓▓▓▓▓ |
  Final sign-off                 |             |             |               |             |             |             | ▓▓▓▓▓▓▓▓▓▓▓
---------------------------------|-------------|-------------|---------------|-------------|-------------|-------------|------------
KLTG FLUTTER APP                 |             |             |               |             |             |             |
  Coordinate build & APK export  | ▓▓▓▓▓▓▓▓▓▓▓ | ▓▓▓▓▓▓▓▓▓▓▓ |               |             |             |             |
  Store / TestFlight deployment  |             | ▓▓▓▓▓▓▓▓▓▓▓ |               |             |             |             |
  Smoke test on device           |             |             | ▓▓▓▓▓▓▓▓▓▓▓▓▓ |             |             |             |
  Functional testing             |             |             | ▒▒▒▒▒▒▒▒▒▒▒▒▒ | ▓▓▓▓▓▓▓▓▓▓▓ |             |             |
  Deep / edge-case testing       |             |             |               | ▒▒▒▒▒▒▒▒▒▒▒ | ▓▓▓▓▓▓▓▓▓▓▓ |             |
  Final testing & sign-off       |             |             |               |             |             | ▓▓▓▓▓▓▓▓▓▓▓ | ▓▓▓▓▓▓▓▓▓▓▓

▓ = active phase   ▒ = overlap/carry-over   ░ = passive monitoring
```

---

## Week 0 — 16–20 June 2026 · Pre-Deploy All Products

> **Anchor:** Deployment day is **Friday 20 June** for both websites. Flutter app build coordination starts this week.

---

### KLTG Website — Pre-Deploy

**Environment & Config**
- [ ] Confirm `.env` at project root with correct InfinityFree / cPanel DB values (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`)
- [ ] Verify `admin/functions.php` `envv()` resolves to prod env (not localhost fallback)
- [ ] `display_errors = Off` in `.htaccess` on production
- [ ] `.env` blocked from web access (`.htaccess` deny)

**Database**
- [ ] Export local `bluedale2_kltg` DB from phpMyAdmin; import to prod MySQL
- [ ] Apply `db_migration_email_verification.sql` via phpMyAdmin → Import
- [ ] Verify all tables exist: `indexpage`, `klglance`, `blogs`, `events`, `ebooks`, etc.

**Pre-deploy Smoke (Local)**
- [ ] Home page loads; all CMS sections render
- [ ] KL @ A Glance slider — raw text renders without double-encoding
- [ ] Blog listing + blog detail pages load
- [ ] Explore KL View on Map deep-link buttons work
- [ ] Beyond KL View on Map buttons work
- [ ] Admin login → dashboard works
- [ ] PWA manifest + service worker registers in DevTools

**Deploy (Friday 20 June)**
- [ ] Push latest `main` to GitHub (`Keshz28/KLTG_Web_Upgrade`)
- [ ] Zip and upload to hosting (see `INFINITYFREE_DEPLOYMENT_GUIDE.md`)
- [ ] Delete `unzip.php` and `.zip` immediately after extraction
- [ ] SSL enabled; site loads over `https://`
- [ ] Root `.env` in place with production DB credentials

---

### Bluedale Website — Pre-Deploy

**Environment & Config**
- [ ] Confirm DB credentials for Bluedale production host
- [ ] Confirm `dashboard/` and `admin/` are correctly configured for prod
- [ ] `.env` or equivalent credential file present and web-blocked
- [ ] PHP version on host compatible with Bluedale codebase

**Database**
- [ ] Export Bluedale local DB; import to production MySQL
- [ ] Run any pending schema migrations
- [ ] Verify tables: billboard, career, gallery, publication, clients, services, etc.

**Pre-deploy Smoke (Local)**
- [ ] Public site home page (`index.php`) loads
- [ ] All public pages: about, billboard, career, contact, services, team, etc.
- [ ] `dashboard/login.php` loads and authenticates
- [ ] Dashboard index (`dashboard/index.php`) loads after login
- [ ] Admin panel (`admin/login.php`) → dashboard (`admin/index.php`) works

**Deploy (Friday 20 June)**
- [ ] Upload files to Bluedale production host
- [ ] Verify domain resolves and SSL active
- [ ] Quick sanity check: homepage + dashboard login on live domain

---

### KLTG Flutter App — Build Coordination

- [ ] Coordinate with developer to confirm latest build is stable
- [ ] Confirm target platforms: Android (APK/AAB) and/or iOS (IPA)
- [ ] Confirm API base URL is pointed at production KLTG backend
- [ ] Confirm push notification / Firebase config set to production keys
- [ ] Request debug APK / TestFlight build for internal testing (if store submission not ready)
- [ ] Confirm which store accounts are being used (Google Play, Apple App Store)

---

## Week 1 — Monday 23 June – 27 June · Post-Launch Smoke Tests + App Deployment

> **Anchor:** Monday 23 June — first post-deployment review day.

---

### KLTG Website — Post-Launch Smoke Test

Test in an **incognito window** on the live domain.

| Page | Check |
|---|---|
| Home (`index.php`) | Loads, hero, nav, footer, all CMS sections |
| Blog listing (`blog.php`) | Cards load |
| Blog detail (`blog-details.php?id=X`) | Full post, Quill delta renders |
| Explore KL | Cards + View on Map buttons deep-link to Maps |
| Beyond KL | Cards + View on Map buttons |
| Accommodation | Loads |
| Events | Listing renders |
| Ebooks | Grid + PDF links resolve |
| Medical Tourism | Loads |
| Places to Shop | Loads |
| Spa | Loads |
| Advertise with Us | Form present |
| Admin login | Login works; session persists |
| Admin dashboard | All quick-stats render |
| About Us | Loads, no broken images |

**Cross-browser check**
- [ ] Chrome desktop · Firefox desktop · Chrome mobile · Safari mobile

**Hotfix sprint (Thu–Fri)**
- P0 (site down): fix same day
- P1 (broken page / feature): fix within 24 hrs
- P2 (visual): log for Week 2

---

### Bluedale Website — Post-Launch Smoke Test

| Page | Check |
|---|---|
| Home | Loads, styling correct |
| About, Services, Team | Render |
| Billboard | Listing loads |
| Career listing + form | Form submits |
| Press Release | Loads |
| Contact | Form works |
| Clients Gallery | Images load |
| `dashboard/login.php` | Login works |
| `dashboard/index.php` | Dashboard home renders |
| Dashboard pages | billboard.php, career.php, gallery.php, publication.php, services.php all load |
| Admin login | `admin/login.php` → `admin/index.php` loads |

**Cross-browser check**
- [ ] Chrome desktop · Firefox · Chrome mobile · Safari mobile

**Hotfix sprint (Thu–Fri)**
- P0/P1 fixes same as KLTG process

---

### KLTG Flutter App — Store / TestFlight Deployment

- [ ] Submit Android APK/AAB to Google Play (internal testing track)
- [ ] Submit iOS IPA to TestFlight or App Store Connect (internal group)
- [ ] Confirm build passes store review (or is available on internal track)
- [ ] Install on test Android device
- [ ] Install on test iOS device (TestFlight)
- [ ] App launches without crash on both platforms
- [ ] App connects to production KLTG backend (API calls return data)

---

## Week 2 — Monday 30 June – 4 July · Functional & Dashboard Testing

---

### KLTG Website — Functional & Regression Testing

**Public features**
- [ ] Banner / carousel on home page rotates
- [ ] Email subscription form submits; confirmation email arrives
- [ ] Newsletter welcome email flow works
- [ ] AOS animations play on scroll
- [ ] Glightbox lightboxes open
- [ ] Swiper carousels swipe on mobile
- [ ] PWA install prompt appears (mobile Chrome)

**Admin CMS**
- [ ] Edit Home page content → appears on live home
- [ ] Edit KL @ A Glance slides → raw text, no double-encoding
- [ ] Add / Edit / Delete blog post → verified on `/blog.php`
- [ ] Add / Edit ebook → verified on `/ebook.php`
- [ ] Add / Edit event → verified on event page
- [ ] Add / Edit Explore KL entry (with View on Map URL)
- [ ] Add / Edit Beyond KL entry
- [ ] Add / Edit Accommodation entry
- [ ] Edit Highlights section
- [ ] Banner management (`admin/bannerreach.php`)
- [ ] Page view stats display correctly

**Cron / Email queue**
- [ ] Email campaign test send (to test address) from `admin/emailcampaign.php`
- [ ] Queue drains via cron2 → cron3 → cron4 chain
- [ ] Cron jobs configured on hosting panel

---

### Bluedale Website — Dashboard Functional Testing

**Dashboard navigation**
- [ ] Dashboard sidebar / nav links all work
- [ ] All dashboard pages load without PHP errors: billboard, career, gallery, publication, services
- [ ] Dashboard `setup.php` — any initial configuration flags correct

**Admin CMS editors**
- [ ] `admin/edit-index.php` → home page content updates on public site
- [ ] `admin/edit-billboard.php` → billboard listing updates
- [ ] `admin/edit-career.php` → career listing updates; career form submissions visible
- [ ] `admin/edit-clients.php` + `admin/edit-clientgallery.php` → gallery updates
- [ ] `admin/edit-publication.php` → publication listing updates
- [ ] `admin/edit-blog.php` → blog posts update (if blog exists)
- [ ] `admin/edit-ebook.php` → ebook listing updates
- [ ] `admin/edit-explorekl.php`, `edit-beyondkl.php`, `edit-accomodation.php` → listings update
- [ ] `admin/edit-spa.php`, `edit-medical-tourism.php`, `edit-places-to-shop.php` → update

**Stats & reporting**
- [ ] `admin/pageviews.php` + `admin/edit-pageviews.php` — view counts incrementing
- [ ] `admin/blogviews.php` + `admin/ebookviews.php` — engagement data present
- [ ] `admin/errors.php` + `admin/errors2.php` — no unexpected PHP errors

---

### KLTG Flutter App — Device Smoke Testing

- [ ] App home screen loads with current KLTG content
- [ ] Blog listing loads from API
- [ ] Blog detail page renders (text + images)
- [ ] Events listing renders
- [ ] Explore KL section loads
- [ ] Navigation between screens is smooth
- [ ] App does not crash on back navigation
- [ ] Offline state: app shows appropriate message (no white screen crash)
- [ ] Test on Android and iOS simultaneously

---

## Week 3 — Monday 7 July – 11 July · Performance + CMS Deep Dive + App Functional

---

### KLTG Website — Performance & SEO Audit

**Performance**
- [ ] Run PageSpeed Insights — Home, Blog Listing, Blog Detail
- [ ] Target: Mobile ≥ 70, Desktop ≥ 85
- [ ] Check for oversized images still on live pages — apply `asset-backups/opt/` versions
- [ ] HTTP/2 enabled on host
- [ ] Gzip/Brotli compression active
- [ ] Browser caching headers on assets (`.htaccess`)

**SEO**
- [ ] Every page has unique `<title>` and `<meta description>`
- [ ] OG tags present on blog detail pages
- [ ] `robots.txt` — allows public, blocks `/admin/`
- [ ] `sitemap.xml` present and submitted to Google Search Console
- [ ] Canonical URLs on blog detail pages
- [ ] Google Analytics fires on all pages
- [ ] No broken internal links

**Accessibility spot-check**
- [ ] All images have `alt` text
- [ ] Keyboard navigable
- [ ] Sufficient colour contrast on body text

---

### Bluedale Website — Admin CMS Deep Validation

- [ ] Quill / rich text fields parse and render correctly on public pages
- [ ] Image uploads via editor: correct path, size limits respected
- [ ] Delete entry → disappears from public listing without breaking page
- [ ] Admin session expiry: log out → accessing admin page redirects to login
- [ ] `admin/sendemail.php` sends correctly (test to known address)
- [ ] `admin/export.php` or data export works
- [ ] `bgocinfobooth.bluedale.com.my` subdomain loads and works if active

---

### KLTG Flutter App — Functional Testing

- [ ] Blog search / filter works
- [ ] Image loading: all cards and detail images render
- [ ] Map / location features (if any) function correctly
- [ ] Push notifications received on device
- [ ] Login / authentication flow (if applicable)
- [ ] User session persists after app backgrounding and restore
- [ ] Deep links open correct in-app screen
- [ ] App correctly handles slow connection (spinner, not crash)
- [ ] Test on Android 10+, Android 12+, iOS 15+

---

## Week 4 — Monday 14 July – 18 July · Security + Admin + App Deep Testing

---

### KLTG Website — Admin CMS & Security

**Admin CMS edge cases**
- [ ] Quill delta: bold, italic, links, embedded images all parse on public pages
- [ ] Upload file size limit enforced in editors
- [ ] Delete blog/ebook/event — no broken pages downstream
- [ ] `admin/register.php` disabled or access-restricted (no public registration)

**Security**
- [ ] SQL injection: `' OR 1=1--` in blog ID, search params — should fail gracefully
- [ ] XSS: `<script>alert(1)</script>` in subscription form name field — sanitised
- [ ] `.env` returns 403 when accessed in browser
- [ ] 5 random admin pages redirect to login when no session
- [ ] VAPID keys set for web push (`admin/vapid.php`)
- [ ] File permissions on host: 755 dirs / 644 files

**PWA final**
- [ ] Install on Android Chrome — icon, splash, name correct
- [ ] Offline fallback (service worker) triggers when network dropped
- [ ] Push notification received on installed PWA

---

### Bluedale Website — Security & Access Testing

- [ ] SQL injection: test any URL params on public pages (billboard ID, career ID, etc.)
- [ ] XSS: submit `<script>alert(1)</script>` in career form, contact form
- [ ] Dashboard login required for all `/dashboard/` pages
- [ ] Admin login required for all `/admin/` pages
- [ ] `admin/register.php` — not publicly accessible
- [ ] No DB credentials exposed in page source or error messages
- [ ] `admin/errors.php` — error log doesn't expose system paths to public
- [ ] File permissions: 755 dirs / 644 files on host

---

### KLTG Flutter App — Deep / Edge-Case Testing

- [ ] App with no internet connection: graceful error states everywhere
- [ ] App with slow network: no timeout crashes
- [ ] Very long blog post: scroll performance acceptable
- [ ] Many images: memory usage doesn't crash app (test 20+ image feed scroll)
- [ ] Back button behaviour on all screens (Android hardware back)
- [ ] App state after incoming call (interrupted, then resumed)
- [ ] Screen rotation behaviour
- [ ] Push notification received while app is in background
- [ ] Push notification tapped: deep-links to correct screen
- [ ] Dark mode (if supported): UI renders correctly

---

## Week 5 — Monday 21 July – 25 July · Monitoring + App Final Testing

---

### KLTG Website — Monitoring & Maintenance

- [ ] Google Analytics: traffic, bounce rate, session duration look reasonable
- [ ] `admin/errors.php` / `errors2.php` — review PHP error log
- [ ] `admin/blogviews.php` / `admin/pageviews.php` — counts incrementing
- [ ] Cron jobs still firing — email/push queue not backing up
- [ ] Apply any backlogged P2 issues
- [ ] Prune test data inserted during CMS testing
- [ ] Confirm `admin/test.php` and `admin/contest.php` not publicly accessible
- [ ] Export clean DB backup from hosting panel

---

### Bluedale Website — Monitoring & Maintenance

- [ ] Check server error logs on hosting panel for PHP errors
- [ ] Dashboard usage: all editors still working cleanly
- [ ] Career form submissions visible in admin
- [ ] Client gallery images loading (no broken image paths)
- [ ] Prune test entries created during validation weeks
- [ ] Export clean DB backup
- [ ] `bgocinfobooth.bluedale.com.my` still operational if in use

---

### KLTG Flutter App — Final Testing & Store Prep

- [ ] Full regression run on Android: all screens, all flows
- [ ] Full regression run on iOS: all screens, all flows
- [ ] App Store screenshots captured on correct device sizes
- [ ] App Store listing: description, keywords, category set correctly
- [ ] Privacy policy URL present in store listing
- [ ] Version number and build number set correctly for public release
- [ ] Submit to Google Play public / production track
- [ ] Submit to App Store for review (if not already approved)
- [ ] Confirm no rejected app review flags outstanding

---

## Week 6 — Monday 28 July – 31 July · Final Sign-off (All Three Products)

---

### Final Checks — KLTG Website (Mon–Tue 28–29 July)

- [ ] Re-run full post-launch smoke test (all public pages)
- [ ] PageSpeed re-run — scores held or improved vs Week 3 baseline
- [ ] Google Search Console: no crawl errors or manual actions
- [ ] Admin CMS editors all working
- [ ] No outstanding P0/P1 bugs

### Final Checks — Bluedale Website (Mon–Tue 28–29 July)

- [ ] Re-run smoke test on all public pages and dashboard pages
- [ ] Admin CMS editors all clean
- [ ] No outstanding P0/P1 bugs
- [ ] Dashboard stats correct

### Final Checks — KLTG Flutter App (Mon–Tue 28–29 July)

- [ ] App live on Google Play (or confirmed in review)
- [ ] App live on App Store (or confirmed in review)
- [ ] Install from store on fresh device — completes without issue
- [ ] Core flows tested on freshly installed app
- [ ] No crash reports in Google Play Console / App Store Connect

---

### Documentation & Handover (Wed 30 July)

- [ ] Update `CLAUDE.md` with any schema or architecture changes from launch
- [ ] Document hosting cron job schedule for KLTG (endpoint, frequency)
- [ ] Document any `.htaccess` rules added during launch
- [ ] Confirm Git history clean: no credentials or large binaries committed
- [ ] Tag KLTG release: `git tag v1.0-launch`
- [ ] Note Bluedale deployment config (host, DB names, any env setup)
- [ ] Note KLTG App version numbers and store URLs for records

### Sign-off (Thu 31 July)

- [ ] All Weeks 0–5 critical items resolved or formally deferred
- [ ] All three products live and stable
- [ ] Steady-state maintenance plan agreed for each product
- [ ] Active test/maintenance period officially closed

---

## Issue Severity Reference

| Priority | Description | Response |
|---|---|---|
| **P0** | Product down / crash / login broken | Fix same day |
| **P1** | Key page / feature broken; data not saving | Fix within 24 hrs |
| **P2** | Minor visual bug, single broken image, analytics gap | Fix within current week |
| **P3** | Cosmetic / nice-to-have | Defer to post 31 July |

---

## Key Resources

| Product | Detail |
|---|---|
| KLTG live site | kltheguide.com.my |
| KLTG admin | kltheguide.com.my/admin/login.php |
| KLTG GitHub | Keshz28/KLTG_Web_Upgrade (branch: `main`) |
| KLTG local DB | `bluedale2_kltg` (XAMPP phpMyAdmin) |
| KLTG deploy guide | `INFINITYFREE_DEPLOYMENT_GUIDE.md` |
| Bluedale live site | bluedale.com.my |
| Bluedale dashboard | bluedale.com.my/dashboard/login.php |
| Bluedale admin | bluedale.com.my/admin/login.php |
| Bluedale local path | `c:/Users/admin/Desktop/BGOC_Web/bluedale.com.my/bluedale.com.my/` |
| KLTG App (Android) | Google Play Console — internal/production track |
| KLTG App (iOS) | App Store Connect — TestFlight / public |
| Git user | Sukesh `<surasesukesh@gmail.com>` |

---

*Document created: 16 June 2026 · Last updated: 16 June 2026*
