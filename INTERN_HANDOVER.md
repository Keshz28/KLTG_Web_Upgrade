# INTERN HANDOVER — KL The Guide (kltheguide.com.my)

> **What this document is.** A practical "where do I go to change X" handbook for the next intern.
> Two sections:
> 1. **Codebases (frontend + backend)** — a table of files and what you do in each.
> 2. **Admin Dashboard** — a walkthrough with screenshot slots (paste your screenshots into the marked spots).
>
> **Companion docs:** `PROJECT_GUIDE.md` is the full file-by-file index, and `CLAUDE.md` explains
> the architecture/build/deploy conventions. This handover is the short, task-focused version —
> when in doubt, those two go deeper.

---

## The one rule to never break

> **Edit → test on localhost → commit → push → publish via cPanel. One direction only.**
> **Never edit files directly on the live server.** Always test on localhost first
> (`http://localhost/kltheguide.com.my - backup/<page>.php`), and syntax-check PHP before trusting it:
> `"d:/xampp/php/php.exe" -l somefile.php` (should say *"No syntax errors detected"*).

**Content vs. code — know which one you're changing:**

- **Changing text/images/listings that already exist on a page?** → Do it through the **Admin Dashboard**
  (Section 2). You almost never edit the `.php` file for this. Example: *adding a new e-book to the live
  site is done in the Admin Dashboard*, not by editing `ebook.php`.
- **Changing layout, structure, styling, or how a page is built?** → Edit the code file (Section 1).
  Example: *moving the e-book grid or changing its columns* → edit `ebook.php`.

---

# SECTION 1 — Codebases (Frontend + Backend)

### 📸 Screenshot slot 1.0 — "Visual Map" of the homepage
*Paste an annotated screenshot of the live homepage here. Draw arrows/labels on it pointing each visible
region to its file, e.g. "top menu → nav.php", "hero banner → index.php", "footer → footer.php". This is
the single most useful image for a new intern — it turns "what file is this?" into a glance.*

`[ PASTE SCREENSHOT HERE ]`

> **Do the same for each major page** (Explore KL, E-books, Merchandise, Events…): a screenshot with
> labels showing which file/editor controls each part. Suggested slots are marked 📸 throughout below.

---

## 1a. Public pages — open these directly in the browser

These are the pages visitors see. Most of their **content** comes from the database (edit it in the Admin
Dashboard); you edit the **file** only for layout/markup.

| File | What you can do here |
|------|----------------------|
| `index.php` | **Homepage.** Hero, featured sections, KL highlights, chat CTA. Content pulled from `indexpage` table (edit via `admin/edit-index.php`). Edit the file for layout only. |
| `ebook.php` | **E-book listing page.** The grid of e-books + category tabs. Edit the file for layout; **add/remove actual e-books and categories via the Admin Dashboard** (`admin/edit-ebook.php`). Links to `ebook-details.php`. |
| `ebook-details.php` | **Single e-book viewer** (DearFlip flipbook). Edit for how one e-book displays. |
| `merchandise.php` | **Store front.** Product grid by category. "Buy Now" hands off to `order.php`. Products/categories are managed in the Admin Dashboard (`admin/edit-merchandise.php`). |
| `order.php` | **Checkout page.** Payment QR, buyer form, required receipt upload → writes to `merchandise_orders` → sends customer to WhatsApp. |
| `event.php` | **Events listing.** Content via `admin/edit-event.php`. |
| `event-details.php` | **Single event page** (linked from the listing). |
| `explorekl.php` | **Explore KL attractions.** Has an inline "View on Map" button. Content via `admin/edit-explorekl.php`. |
| `beyondkl.php` | **Beyond KL day-trips.** Uses `beyondkl_mapcoords.php` for its map button. Content via `admin/edit-beyondkl.php`. |
| `accommodation.php` | **Accommodation listings.** Uses `view_on_map_helper.php` + `kltg_mapcoords.php`. Content via `admin/edit-accomodation.php`. |
| `spa.php` | **Spa & wellness listings.** Same map-helper pattern. Content via `admin/edit-spa.php`. |
| `where-to-shop.php` | **Shopping listings.** Same pattern. Content via `admin/edit-places-to-shop.php`. |
| `medical-tourism.php` | **Medical tourism listings.** Same pattern. Content via `admin/edit-medical-tourism.php`. |
| `kl-glance.php` | **"KL at a Glance"** landmark slides. Content via `admin/edit-klglance.php` (⚠️ stores raw text, not escaped). |
| `getting-around-kl.php` | Transport / getting-around guide. |
| `travel-tips.php` | Travel tips page. |
| `blog.php` | **Blog listing.** Rendered by `assets/js/blog2.js`, which fetches from `fetch_blogger.php`. Posts come from Blogger, not the local DB. |
| `blog-details.php` | **Single blog article.** Rendered by `assets/js/blog-details.js`. |
| `map.php` | Interactive map page. Every "View on Map" button points here. |
| `aboutus.php` | About Us / Bluedale Publishing story. |
| `contribute.php` | "Contribute" page (user submissions invite). |
| `advertisewithus.php` | Advertise-with-us info + enquiry. |
| `lpage.php` | Standalone landing-page contact form (styled by `assets/css/lpage.css`). Managed in `admin/landing-page.php`. |
| `verify-email.php` | Email-verification link handler (legacy back-up path; newsletter is now single opt-in). |

📸 **Screenshot slot 1a** — *One labelled screenshot per major public page (E-books, Merchandise, Explore
KL, Events) showing which regions map to which file/editor.*

`[ PASTE SCREENSHOTS HERE ]`


## 1b. Backend engine + data helpers

| File | What you can do here |
|------|----------------------|
| `admin/functions.php` | **The engine room — the most important backend file.** Opens the MySQL connection (`$db`), starts the session, loads `.env` + Composer, wires up email (PHPMailer) and web-push. Every page depends on it. **If something breaks on *every* page at once, look here first** (usually a DB/`.env` issue — see `CLAUDE.md` "DB credentials resolution"). |
| `chatbot-api.php` | **The AI chatbot's brain.** Frontend `assets/js/chatbot.js` POSTs here; this calls Google Gemini and returns the reply. **To change what the bot says / how it behaves / which pages it links to, edit the `$systemInstruction` string inside.** Keeps the Gemini API key server-side — never move this into browser JS. |
| `fetch_blogger.php` | **Blog data pipeline.** Fetches posts from the Google Blogger API and caches them to `cache/*.json` for 5 min. Powers `blog.php` / `blog-details.php`. |
| `view_on_map_helper.php` | Defines the shared "View on Map" button (used by medical-tourism / shop / spa / accommodation). |
| `kltg_mapcoords.php` | Generated `title → "lat,lng"` lookup for the map buttons on shop/spa/accommodation/medical-tourism. |
| `beyondkl_mapcoords.php` | Same, but the Beyond KL dataset. |
| `ebook-track-download.php` | Records an e-book download (feeds the download stats). |

## 1c. Frontend assets (CSS / JS / images)

| Location | What you can do here |
|----------|----------------------|
| `assets/css/main.css` | **The main site stylesheet — hand-edited, no build step.** Change site-wide colours/spacing/styling here. |
| `assets/css/variables.css` | CSS variables (colours, etc.), also hand-edited. |
| `assets/css/lpage.css` | Styles for the `lpage.php` landing page only. |
| `assets/js/chatbot.js` / `chatbot.css` | The chat-bubble **widget** (its look and client behaviour). The bot's *replies* come from `chatbot-api.php`. |
| `assets/js/blog2.js` | Renders the blog **listing** (`blog.php`). |
| `assets/js/blog-details.js` | Renders a **single blog article** (`blog-details.php`). |
| `assets/vendor/` | Vendored libraries (Bootstrap, AOS, GLightbox, Swiper). Don't hand-edit — treat as read-only. |
| `assets/img/` | Live site images used by pages. |
| `asset-backups/` | **Oversized original images** (multi-MB). ⚠️ Don't point pages at these directly — they cause page lag. Use optimized copies in `asset-backups/opt/` instead. |

## 1d. The "I want to… → go to" quick lookup

| I want to… | Go to |
|------------|-------|
| Add/remove an **e-book** on the live site | **Admin Dashboard** → `admin/edit-ebook.php` (not the code) |
| Change the **e-book page layout** | `ebook.php` |
| Add/edit a **product** or the **payment QR** | **Admin Dashboard** → `admin/edit-merchandise.php` |
| See who **bought something** (orders) | **Admin Dashboard** → `admin/edit-merchandise.php` (Orders card) |
| Change the **top menu** | `nav.php` |
| Change the **footer** | `footer.php` |
| Change site-wide **`<head>` / analytics / meta** | `header.php` |
| Change site-wide **styling/colours** | `assets/css/main.css` (+ `variables.css`) |
| Change what the **chatbot** says / links to | `chatbot-api.php` (`$systemInstruction`) |
| Fix a **broken DB connection / site down everywhere** | `admin/functions.php` (+ `CLAUDE.md` DB section) |
| Add/fix a **"View on Map"** button | `view_on_map_helper.php`, `kltg_mapcoords.php`, `beyondkl_mapcoords.php` |
| Work on the **blog pipeline** | `fetch_blogger.php` (+ `cache/`), `assets/js/blog2.js` |
| Edit any page's **existing content** | Admin Dashboard → `admin/edit-<page>.php` |


---

# SECTION 2 — Admin Dashboard (with screenshot slots)

> **This is where almost all day-to-day content changes happen.** You do NOT edit code for these —
> you log in and use the forms.
>
> **How to get in:** go to `admin/login.php` (locally:
> `http://localhost/kltheguide.com.my - backup/admin/login.php`), enter the admin username + password.
> If you're not logged in, every admin page bounces you back to this login screen.

📸 **Screenshot slot 2.0 — Login screen**
*Paste a screenshot of the `admin/login.php` login page.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2a. The Dashboard home & sidebar

After logging in you land on the dashboard (`admin/index.php`), with the menu sidebar (`admin/nav.php`)
on the left — this is how you reach every editor below.

📸 **Screenshot slot 2.1 — Dashboard landing page**
*Paste a screenshot of the dashboard home right after login (shows pageview stats).*

`[ PASTE SCREENSHOT HERE ]`

📸 **Screenshot slot 2.2 — The full sidebar menu**
*Paste a screenshot of the left sidebar expanded, so the intern can see every section it links to.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2b. Editing page content

Each public page has a matching editor in the sidebar. Open it, change the fields, save — the live page
updates from the database.

| Sidebar editor | Controls this public page |
|----------------|---------------------------|
| Edit Homepage | `index.php` |
| Edit Explore KL | `explorekl.php` |
| Edit Beyond KL | `beyondkl.php` |
| Edit Accommodation | `accommodation.php` |
| Edit Spa | `spa.php` |
| Edit Where to Shop | `where-to-shop.php` |
| Edit Medical Tourism | `medical-tourism.php` |
| Edit Events | `event.php` |
| Edit KL at a Glance | `kl-glance.php` |
| Edit Highlights | homepage highlights section |
| Edit Blog | blog metadata/content |

📸 **Screenshot slot 2.3 — A page editor (example)**
*Paste a screenshot of one editor open, e.g. Edit Homepage or Edit Events, showing the content fields and
the Save button. This teaches the pattern for all of them.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2c. E-books (add/remove e-books + categories)

**This is how you add an e-book to the live site.** Open the E-book editor (`admin/edit-ebook.php`).
It manages both the **e-books themselves** and the **category tabs** (full add/edit/delete over the
`ebook_category` table).

> ⚠️ A category's **code is the folder name on disk** — renaming a code means files have to be moved,
> so avoid renaming codes casually.

📸 **Screenshot slot 2.4 — E-book editor**
*Paste a screenshot of the E-book editor: the list of e-books, the upload form, and the "E-book Categories"
card. Annotate where you click to add a new e-book.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2d. Merchandise store & orders

Open the Merchandise editor (`admin/edit-merchandise.php`) — **the whole store lives on one page**, in
four cards:

1. **Store Settings** — the payment QR image and the WhatsApp number customers get sent to.
2. **Categories** — product categories.
3. **Products** — the items shown on `merchandise.php`.
4. **Orders** — every order placed through `order.php`, newest first, **including the customer's uploaded
   payment receipt.** This is where you check what someone actually bought.

📸 **Screenshot slot 2.5 — Merchandise editor (all four cards)**
*Paste a screenshot (or several) of the Store Settings, Products, and Orders cards. Label which card does what.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2e. Advertisements & vouchers

| Sidebar editor | What it does |
|----------------|--------------|
| Edit Advertisement | Manage ads (click tracking via `track_ad_click.php`). |
| Edit Voucher | Manage vouchers. |
| Landing Page | The `lpage.php` landing-page form. |

📸 **Screenshot slot 2.6 — Advertisement editor**
*Paste a screenshot of the ad editor.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2f. Analytics & reporting

| Sidebar item | What it shows |
|--------------|---------------|
| Page Views | Pageview report per URL (`admin/pageviews.php`). |
| Blog Views | Blog view stats (`admin/blogviews2.php` is the newer one). |
| E-book Views | E-book view stats. |
| Banner Reach | Banner reach/click analytics. |
| Export | Download data as a CSV file (`admin/export.php`). |

📸 **Screenshot slot 2.7 — An analytics/reporting page**
*Paste a screenshot of Page Views or Banner Reach so the intern knows where stats live.*

`[ PASTE SCREENSHOT HERE ]`

---

## 2g. Email & subscribers

| Sidebar item | What it does |
|--------------|--------------|
| Email Campaign | Compose & send an email campaign to subscribers (`admin/emailcampaign.php`). |
| Subscribers | Manage the subscriber list (`admin/sub.php`). |

📸 **Screenshot slot 2.8 — Email campaign composer**
*Paste a screenshot of the campaign compose screen.*

`[ PASTE SCREENSHOT HERE ]`

📸 **Screenshot slot 2.9 — Subscribers list**
*Paste a screenshot of the subscriber management page.*

`[ PASTE SCREENSHOT HERE ]`


## Appendix — Screenshot checklist (what to capture)

Tick these off as you paste them in:

**Codebase visual maps (Section 1):**
- [ ] 1.0 — Homepage with regions labelled to files (top priority)
- [ ] 1a — E-books page labelled
- [ ] 1a — Merchandise page labelled
- [ ] 1a — Explore KL page labelled
- [ ] 1a — Events page labelled

**Admin Dashboard (Section 2):**
- [ ] 2.0 — Login screen
- [ ] 2.1 — Dashboard landing page
- [ ] 2.2 — Full sidebar menu
- [ ] 2.3 — A page editor (e.g. Homepage/Events)
- [ ] 2.4 — E-book editor (with "add e-book" annotated)
- [ ] 2.5 — Merchandise editor (all four cards)
- [ ] 2.6 — Advertisement editor
- [ ] 2.7 — An analytics page (Page Views / Banner Reach)
- [ ] 2.8 — Email campaign composer
- [ ] 2.9 — Subscribers list

---

*Keep this document alive: when you add a new page or admin tool, add a row to the right table above and,
ideally, a labelled screenshot. Companion references: `PROJECT_GUIDE.md` (full file index) and `CLAUDE.md`
(architecture + deploy).*
