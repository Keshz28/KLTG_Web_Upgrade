# KL The Guide — QA Test Plan & Checklist

**Site:** kltheguide.com.my | **Admin dashboard:** `/admin/login.php`
**Last updated:** 2026-07-06

---

## Read this first (plain-English guide)

This document is a checklist for testing the whole website. You do **not** need to be a
programmer to use it. Each test tells you **what to do** and then lists **what should
happen** ("Expected results"). Tick the box next to each expected result once you've seen
it happen. If something does **not** happen, leave the box empty and write what went wrong
in the **Notes** line.

**What the marks mean:**

- **✅ Already checked by Claude (automated)** — Claude verified this behind the scenes
  by inspecting the program code and the database. You can usually trust these, but you may
  still want to confirm visually. Each one has a plain-English explanation of what was checked.
- **👤 You need to test this** — This can only be confirmed by a human actually clicking
  around the live website in a browser (Claude cannot see the screen, receive emails, or
  click buttons). These are the important ones for you to do.
- `- [ ]` = a checkbox. Tick it (`- [x]`) when you've confirmed that line.

**Tips for testing:**

- Test on a **computer** and on a **phone** (or a narrow browser window) — the site has no
  separate mobile app, so it must look right on both.
- Use **Google Chrome** plus one other browser (Edge, Firefox, or Safari) for anything with
  forms, uploads, or the chatbot.
- Do all the **Admin Dashboard** tests first — they create the content that the
  **Live Site** tests then check.

---

## Summary of what Claude already verified automatically

Before you start, here is what was checked automatically at the code and database level on
**2026-07-06**. This gives you confidence the "plumbing" is in place; your job is to confirm
it all **looks and behaves right** for a real visitor.

| # | Automated check | Result |
|---|-----------------|--------|
| 1 | Every website page opens without a code (syntax) error — **30 public pages** checked | ✅ All passed |
| 2 | Every admin editor + its save-handler opens without a code error — **26 admin files** checked | ✅ All passed |
| 3 | The website successfully connects to its database (`kltheguidecom_bluedale2_kltg`) | ✅ Working |
| 4 | All the database tables the CMS needs exist (KL @ A Glance, Ebook categories, Merchandise, Events, Blog, Subscribers, etc.) | ✅ All present |
| 5 | The database already contains real content (576 blogs, 210 events, 45 ebooks, 17 ebook categories, 7 KL@Glance slides, 8 Insider picks, 16,552 subscribers, 696 enquiry-form entries, 3 merchandise orders) | ✅ Populated |
| 6 | Merchandise checkout is fully wired: payment-QR + WhatsApp number storage, and an orders table with receipt upload | ✅ Present in code + DB |
| 7 | Email subscription code validates the address, checks the email domain is real, prevents duplicates, and sends a 48-hour confirmation link | ✅ Correct in code |
| 8 | Advertise-With-Us and Contribute forms save enquiries (696 already captured in the database) | ✅ Working |
| 9 | The chatbot is wired to Google Gemini and the API key exists in the local settings file | ✅ Present locally |
| 10 | Every section page (Explore KL, Beyond KL, Medical Tourism, Shop, Spa, Stay) has "View on Map" buttons linking to the map page | ✅ Present |
| 11 | About Us page has the "Ask our Chat Assistant" button and the "View Opportunities" link to bluedale.com.my (opens in a new tab) | ✅ Present |

**Two things Claude flagged for you to double-check (details in the Appendix):**

1. The extra database safety-lock that blocks duplicate subscriber emails (`db_migration_email_dedupe.sql`)
   is **not yet applied** to the database. Duplicates are already prevented by the program code, so this is
   a backup safeguard — but it should be applied before/at go-live, especially on the live server.
2. The chatbot's Gemini key exists **locally**, but earlier notes say it was **missing from the deployment
   bundle**. Confirm the key is set on the live server, or the chatbot will fail there.

> **Important:** "Already checked by Claude" does **not** replace your own testing of anything
> visual, anything involving real emails, uploads, external links, Google Maps, or the chatbot's
> actual answers. Claude cannot see the screen or use the site — those are all marked 👤.

---

## Quick-Glance Tracker

### Admin Dashboard

| ID | Area | Result | Tested by | Date |
|----|------|--------|-----------|------|
| TC-A1 | Index banner editing | ☐ | | |
| TC-A2 | KL @ A Glance content + live display | ☐ | | |
| TC-A3 | Blog freshness / publishing cadence | ☐ | | |
| TC-A4 | Ebook uploading + new category | ☐ | | |
| TC-A5 | Merchandise new category + product | ☐ | | |
| TC-A6 | Explore KL sections/subsections | ☐ | | |
| TC-A7 | Beyond KL sections/subsections | ☐ | | |
| TC-A8 | Medical Tourism sections/subsections | ☐ | | |
| TC-A9 | Places to Shop content | ☐ | | |
| TC-A10 | Spa content | ☐ | | |
| TC-A11 | Place to Stay sections/subsections | ☐ | | |
| TC-A12 | Events (Upcoming Highlights) | ☐ | | |

### Live Site

| ID | Area | Result | Tested by | Date |
|----|------|--------|-----------|------|
| TC-B1 | Email subscription (top form) → admin sync | ☐ | | |
| TC-B2 | Chatbot | ✅ Pass | User | 2026-07-06 |
| TC-B3 | Insider Suggestions blog links | ☐ Not yet | | |
| TC-B4 | Latest blog posts on landing page | ✅ Pass | User | 2026-07-06 |
| TC-B5 | Footer newsletter subscription | ☐ | | |
| TC-B6 | Advertise With Us page + form | ☐ | | |
| TC-B7 | Contribute an Article page + form | ☐ | | |
| TC-B8 | "View on Map" buttons across all sections | ✅ Pass | User | 2026-07-06 |
| TC-B9 | Ebook cover, read, download | ⏳ Read OK; download pending | User | 2026-07-06 |
| TC-B10 | Blog list + blog-details links | ✅ Pass | User | 2026-07-06 |
| TC-B11 | Map page (all sections/subsections) | ✅ Pass | User | 2026-07-06 |
| TC-B12 | Events linking + category filter | ✅ Pass | User | 2026-07-06 |
| TC-B13 | Merchandise buy flow + DuitNow QR | ⏳ Buy OK; QR not set up yet | User | 2026-07-06 |
| TC-B14 | About Us FAQ — chat + View Opportunities | ✅ Pass | User | 2026-07-06 |
| TC-M | Mobile / responsive layout | ⏳ Config verified; visual check pending | | |

---

# Section A — Admin Dashboard

## TC-A1 — Index Banner Editing
**Admin page:** Edit Index (`admin/edit-index.php`) → sections: **Banner**, **Hero / Title**, **KL Highlights**, **Recommendations**, **Insider**, **Blog**

**✅ Already checked by Claude:** The Edit Index page and its save-handler open without code errors, and the homepage content table (`indexpage`) exists in the database and holds real data. *(This means the editing tool and storage work; you must confirm the visuals.)*

**What to do:**
1. Log into `/admin/`, open **Edit Index**.
2. In the **Banner** card, upload a new banner image and Save.
3. Edit the **Hero / Title** text and Save.
4. Open `index.php` on the live site in a new tab and hard-refresh (Ctrl+F5).
5. Repeat step 2 with a very large image (e.g. over 5MB).

**Expected results (tick each):**
- 👤 - [ ] The editor loads with the current banner image and text already filled in.
- 👤 - [ ] After saving, a success message appears and there is no error on screen.
- 👤 - [ ] On the live homepage, the **new banner image** appears.
- 👤 - [ ] On the live homepage, the **new hero/title text** appears.
- 👤 - [ ] The banner image is not stretched, squashed, or badly cropped.
- 👤 - [ ] The homepage still looks correct on a phone / narrow window.
- 👤 - [ ] A very large image is either rejected with a clear message or shrunk automatically — it does **not** produce a blank white page.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A2 — KL @ A Glance (add image + description, verify live display)
**Admin page:** Edit KL @ A Glance (`admin/edit-klglance.php`) → database table `klglance` | **Live page:** `kl-glance.php`

**✅ Already checked by Claude:** The editor, its save-handler, and the live page all open without code errors; the `klglance` table exists and already holds **7 slides**. **Heads-up:** this editor saves text *exactly as typed* (it does not "clean" special symbols like most other editors do), so typing raw `<` or `>` or an unfinished tag could break the page layout — please test that on purpose in step 2.

**What to do:**
1. Open **Edit KL @ A Glance**, add a new landmark slide with an image + description, Save.
2. Add a description that contains special characters (`&`, `<`, `>`, quotes) and Save.
3. Open `kl-glance.php` on the live site.
4. View it on a phone / narrow window.
5. Edit an existing slide and replace only its image.

**Expected results (tick each):**
- 👤 - [ ] The new slide saves with no error.
- 👤 - [ ] The description with special characters does **not** break the saved record or the live page layout.
- 👤 - [ ] On the live page, the new slide appears with its image at the correct size/crop.
- 👤 - [ ] The description text reads correctly — no raw tags (like `<div>`) showing, no broken layout.
- 👤 - [ ] The slide/carousel still works and the image doesn't overflow on a phone.
- 👤 - [ ] After replacing an image, the old one is gone (not duplicated) and the page still loads.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A3 — Blog Freshness / Publishing Cadence
**Files:** `admin/edit-blog.php`, `fetch_blogger.php`, `blog.php`, `blog-details.php`
Blog posts are pulled in from **Blogger**. This test checks that the pipeline stays up to date.

**✅ Already checked by Claude:** The blog pages and the Blogger-fetch script open without code errors, and the `blog` table already contains **576 posts**. *(The pipeline and storage exist; you must confirm new posts actually flow through and how often.)*

**What to do:**
1. Publish (or confirm) a new post on the connected Blogger source.
2. Open `blog.php` on the live site.
3. Open `admin/edit-blog.php`.
4. Click into the new post from `blog.php`.
5. Note how often new blogs are actually being posted.

**Expected results (tick each):**
- 👤 - [ ] The new post appears at the top of `blog.php` within the expected refresh time (write down how long it took).
- 👤 - [ ] The admin blog area reflects the same latest post / lets you manage it.
- 👤 - [ ] Clicking the new post opens `blog-details.php` with the correct title, image, and body.
- 👤 - [ ] The blog is being updated regularly (note the actual frequency — flag it if updates have stalled).

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A4 — Ebook Upload (existing category) + Brand-New Category
**Admin page:** Edit Ebook (`admin/edit-ebook.php`, handler `admin/pagefunctions/edit-ebook.php`) → categories in `ebook_category` (`cat_code` = folder name) | **Live:** `ebook.php`, `ebook-details.php`

**✅ Already checked by Claude:** The ebook editor, its handler, and the live pages open without code errors. The `ebook_category` table exists with **17 categories** and the `ebook` table has **45 ebooks**. The code path for using a PDF as a cover image (shown via `<embed>`) is present. *(Storage and categories work; you confirm the upload + display.)*

**What to do:**
1. Open **Edit Ebook**, upload a new ebook PDF + cover image into an **existing** category.
2. Create a **brand-new category**.
3. Upload an ebook into the **new** category.
4. Open `ebook.php` on the live site.
5. Try uploading a **PDF as the cover** (no separate image).
6. Open the new ebook's details page.

**Expected results (tick each):**
- 👤 - [ ] The ebook uploads into the existing category with no error (within the site's file-size limit).
- 👤 - [ ] The new category is created and appears as a filter/folder on the live ebook page.
- 👤 - [ ] The ebook uploaded into the new category saves under it correctly.
- 👤 - [ ] On the live page, the new category and new ebook show, with the correct cover.
- 👤 - [ ] A PDF-as-cover actually displays (not a broken/empty box).
- 👤 - [ ] The new ebook's details page shows the correct title, description, and category.
- 👤 - [ ] "Read" and "Download" both work on the new ebook (see TC-B9).

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A5 — Merchandise: New Category + New Product
**Admin page:** Edit Merchandise (`admin/edit-merchandise.php`, handler `admin/pagefunctions/edit-merchandise.php`) | **Live:** `merchandise.php`, checkout `order.php`

**✅ Already checked by Claude:** The merchandise editor, its handler, and the live pages open without code errors. The database has a `merchandise` table (name, price, description, image, category, buy link), a `merchandise_category` table (**2 categories** currently), and a `merchandise` count of **1 product**. *(The structure is correct; you confirm creating/editing works and displays.)*

**What to do:**
1. Open **Edit Merchandise**, create a new product **category**.
2. Add a new **product** under it (name, price, description, image).
3. Open `merchandise.php` on the live site.
4. Click the product → "Buy Now".
5. Edit the product's price/description and re-check the live page.

**Expected results (tick each):**
- 👤 - [ ] The new category saves and appears as a filter on the live merchandise page.
- 👤 - [ ] The new product saves with no error.
- 👤 - [ ] On the live page, the category filter works and the product card shows the correct image, price, and description.
- 👤 - [ ] "Buy Now" goes to `order.php` with the correct product pre-filled (full checkout is TC-B13).
- 👤 - [ ] After editing price/description, the live page shows the update on refresh.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A6 — Explore KL: All Sections & Subsections
**Admin page:** Edit Explore KL (`admin/edit-explorekl.php`) | **Live:** `explorekl.php`

**✅ Already checked by Claude:** The editor and live page open without code errors, all Explore KL database tables exist (`explorekl_wtd`, `explorekl_hs`, `explorekl_kl4k`, `explorekl_p`, `explorekl_pwor`, `explorekl_nl`, `explorekl_ss`, `explorekl_wte_sf/_c/_r`, `explorekl_nav`), and the live page has "View on Map" buttons built in. *(You confirm adding content and that it appears + the map button works.)*

For **each** subsection below: add a new entry in the admin editor, then confirm on the live page.

| Subsection | Add new entry saves (admin) | Shows on live page | Image displays OK | "View on Map" button works | Overall |
|---|---|---|---|---|---|
| Navigation | 👤 ☐ | 👤 ☐ | 👤 ☐ | n/a | ☐ |
| What To Do | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Historical Sites | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| KL 4 Kids | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Parks | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Place Of Worship | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Night Life | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Sightseeing | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| What To Eat – Street Food | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| What To Eat – Cafes | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| What To Eat – Restaurant | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |

**Notes / bugs found:**

---

## TC-A7 — Beyond KL: All Sections & Subsections
**Admin page:** Edit Beyond KL (`admin/edit-beyondkl.php`) | **Live:** `beyondkl.php`

**✅ Already checked by Claude:** Editor and live page open without code errors; all Beyond KL tables exist (`beyondkl_i`, `beyondkl_hs`, `beyondkl_w`, `beyondkl_h`, `beyondkl_es`, `beyondkl_nav`); the live page has its own "View on Map" buttons.

| Subsection | Add new entry saves (admin) | Shows on live page | Image displays OK | "View on Map" button works | Overall |
|---|---|---|---|---|---|
| Navigation | 👤 ☐ | 👤 ☐ | 👤 ☐ | n/a | ☐ |
| Islands | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Hill Station | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Waterfall | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Hiking | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Extreme Sports | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |

**Notes / bugs found:**

---

## TC-A8 — Medical Tourism: All Sections & Subsections
**Admin page:** Edit Medical Tourism (`admin/edit-medical-tourism.php`) | **Live:** `medical-tourism.php`

**✅ Already checked by Claude:** Editor and live page open without code errors; all tables exist (`medical_tourism_hc`, `_dtl`, `_der`, `_oph`, `_ps`, `_nav`); the live page includes the shared "View on Map" helper.

| Subsection | Add new entry saves (admin) | Shows on live page | Image displays OK | "View on Map" button works | Overall |
|---|---|---|---|---|---|
| Navigation | 👤 ☐ | 👤 ☐ | 👤 ☐ | n/a | ☐ |
| Healthcare | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Dental | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Dermatologist | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Ophthalmologist | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Plastic Surgery | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |

**Notes / bugs found:**

---

## TC-A9 — Places to Shop
**Admin page:** Edit Places To Shop (`admin/edit-places-to-shop.php`) | **Live:** `where-to-shop.php`

**✅ Already checked by Claude:** Editor and live page open without code errors; the `place_shop` table exists; the live page includes the shared "View on Map" helper. **Note:** this editor has a single "Places To Shop" content block rather than several named subsections — confirm with the team that this is intended, not a missing feature.

**What to do:** Add a new shopping-location entry with image + description, then view `where-to-shop.php`.

**Expected results (tick each):**
- 👤 - [ ] The new entry saves with no error.
- 👤 - [ ] On the live page, the new entry appears with the correct image and description.
- 👤 - [ ] Its "View on Map" button opens the map focused on the correct location.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A10 — Spa
**Admin page:** Edit Spa (`admin/edit-spa.php`) | **Live:** `spa.php`

**✅ Already checked by Claude:** Editor and live page open without code errors; the `spa` table exists; the live page includes the shared "View on Map" helper.

**What to do:** Add a new spa entry with image + description, then view `spa.php`.

**Expected results (tick each):**
- 👤 - [ ] The new entry saves with no error.
- 👤 - [ ] On the live page, the new entry appears and the image is not distorted.
- 👤 - [ ] Its "View on Map" button works.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-A11 — Place to Stay: All Sections & Subsections
**Admin page:** Edit Accommodation (`admin/edit-accomodation.php`) | **Live:** `accommodation.php`

**✅ Already checked by Claude:** Editor and live page open without code errors; all tables exist (`accommodation_top`, `_h`, `_bh`, `_bks`, `_nav`); the live page includes the shared "View on Map" helper.

| Subsection | Add new entry saves (admin) | Shows on live page | Image displays OK | "View on Map" button works | Overall |
|---|---|---|---|---|---|
| Top Places To Stay In KL | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Hotels | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Budget Hotels | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |
| Backpackers Lodge | 👤 ☐ | 👤 ☐ | 👤 ☐ | 👤 ☐ | ☐ |

**Notes / bugs found:**

---

## TC-A12 — Events (Upcoming Highlights)
**Admin page:** Edit Event (`admin/edit-event.php`) | **Live:** `event.php`, `event-details.php`

**✅ Already checked by Claude:** Editor and live pages open without code errors; the `event` table exists (with a `event_category` column used for filtering) and already holds **210 events**. *(The category filter is backed correctly; you confirm adding + display + filtering.)*

**What to do:**
1. Add a new event with title, description, category, date, and image.
2. Open `event.php` on the live site.
3. Filter by the event's category.
4. Click into the event.
5. Add one event with a **past** date and one with a **future** date.

**Expected results (tick each):**
- 👤 - [ ] The new event saves with no error.
- 👤 - [ ] The new event card appears on the live events page.
- 👤 - [ ] Filtering by its category shows the event under the correct filter (full filter test is TC-B12).
- 👤 - [ ] Clicking the event opens `event-details.php` with the correct description, date, and image.
- 👤 - [ ] Past-dated and future-dated events both display/sort as expected (expired events aren't shown as "upcoming" if that logic exists).

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

# Section B — Live Site

## TC-B1 — Email Subscription (Top Form) → Admin Sync
**Flow:** subscription box → `admin/sub_handler.php?action=subscribe` → confirmation email → `verify-email.php` | **Admin:** subscriber list

**✅ Already checked by Claude:** The subscribe handler is coded correctly — it (a) rejects badly-formatted emails, (b) checks the email's domain is real, (c) looks up the email first and returns "already subscribed" instead of creating a duplicate, and (d) creates a 48-hour confirmation link. The `emailsub` table exists and already holds **16,552 subscribers**. **What Claude cannot test:** whether the confirmation email actually arrives in an inbox and whether the link works end-to-end — that's your 👤 job below.

**What to do:**
1. Submit a new, never-used email in the **top** subscription box.
2. Check that inbox for the confirmation email.
3. Click the confirmation link.
4. Check the admin dashboard subscriber list.
5. Re-submit the **same** email.
6. Submit an obviously invalid email (e.g. `abc@abc`).

**Expected results (tick each):**
- 👤 - [ ] After submitting, a success message appears ("check your inbox / confirm").
- 👤 - [ ] A confirmation email actually arrives in the inbox.
- 👤 - [ ] Clicking the link opens `verify-email.php` and confirms the subscription.
- 👤 - [ ] The new subscriber appears in the admin list with the correct status (pending → confirmed).
- 👤 - [ ] Re-submitting the same email shows an "already subscribed" message and does **not** create a duplicate.
- 👤 - [ ] An invalid email is rejected with a clear message.
- ✅ - [x] *(Code-verified by Claude: format validation, real-domain check, duplicate prevention, and 48-hour token are all present and correct.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-B2 — Chatbot
**Files:** `assets/js/chatbot.js`, `chatbot-api.php` (uses Google Gemini)

**✅ Already checked by Claude:** The chatbot page and its API script open without code errors, the script is wired to Google Gemini, and a Gemini API key **is present in the local settings file**. **What Claude cannot test:** the actual quality/relevance of answers, and whether it works on the **live server** (see Appendix — the key may be missing there). Those are your 👤 job.

**What to do:**
1. Open the chatbot on the homepage; ask a KL question (e.g. "top attractions in KL").
2. Ask something off-topic or nonsensical.
3. Send an empty message and several rapid messages.
4. Open the chatbot via the FAQ button on `aboutus.php` (see TC-B14).
5. Test on a phone / narrow window.

**Expected results (tick each):**
- 👤 - [x] A relevant, sensible answer comes back for the KL question.
- 👤 - [x] Off-topic/nonsense questions get a graceful reply — no raw error text or code shown to the user.
- 👤 - [x] Empty or rapid messages don't crash it.
- 👤 - [x] The FAQ "Ask our Chat Assistant" button opens the chat widget.
- 👤 - [ ] The chat widget works and is scrollable on a phone without breaking the layout. *(pending mobile check — see TC-M)*
- 👤 - [ ] On the **live server**, the chatbot actually responds (confirms the Gemini key is set there too). *(confirm on production)*
- ✅ - [x] *(Code-verified by Claude: chatbot wired to Gemini; API key present in the local `.env`.)*

**Result:** [x] Pass (desktop)  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06; still confirm on mobile + live server*
**Notes / bugs found:**

---

## TC-B3 — Insider Suggestions Blog Links (after replacing blogs)
**Database table:** `recommendation` | **Admin:** Edit Index → **Insider** card | **Live:** homepage "Insider" grid → links to `blog-details.php?postid=...`

**✅ Already checked by Claude:** The `recommendation` table exists, holds **8 entries**, and has a `recommendation_postid` column — this is the exact link between each Insider pick and its blog post. The homepage code turns that into a `blog-details.php?postid=…` link and safely converts the ID to a number. *(The linking mechanism is correct; you confirm it points at the right, current blogs.)*

**What to do:**
1. In admin, change an existing Insider entry to point at a **newly published** blog.
2. View the homepage Insider section.
3. Click that entry's link icon.
4. Check whether any Insider entry still points at a **deleted** blog post.
5. Use the category filter pills above the Insider grid.

**Expected results (tick each):**
- 👤 - [ ] The change saves with no error.
- 👤 - [ ] The new thumbnail/name shows under the correct category on the homepage.
- 👤 - [ ] Clicking it opens the **new** blog (`blog-details.php?postid=…`), not the old/removed one.
- 👤 - [ ] An entry pointing at a removed blog does **not** show a broken/blank page (note what happens).
- 👤 - [ ] The category filter pills correctly show only matching entries.
- ✅ - [x] *(Code-verified by Claude: Insider→blog link uses `recommendation_postid`, safely cast to a number.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-B4 — Latest Blog Posts on Landing Page
**Live:** `index.php` "Recent Blog Posts" section (loaded via `fetch_blogger.php`)

**✅ Already checked by Claude:** The homepage and the Blogger-fetch script open without code errors, and the `blog` table holds **576 posts**. *(The feed source works; you confirm the newest posts actually appear and link correctly.)*

**What to do:**
1. Publish a new blog post.
2. Load the homepage (hard refresh).
3. Click a post from this section.
4. Test on a throttled/slow connection (browser DevTools).

**Expected results (tick each):**
- 👤 - [x] The new post appears in the Recent Blog Posts row, newest first.
- 👤 - [x] Clicking a post opens the correct `blog-details.php`.
- 👤 - [ ] On a slow connection the section doesn't stay permanently empty/broken (there's a loading state or it fills in). *(optional edge case)*

**Result:** [x] Pass  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06*
**Notes / bugs found:**

---

## TC-B5 — Footer Newsletter Subscription
**File:** `footer.php` subscribe form → **same** `admin/sub_handler.php?action=subscribe` endpoint as TC-B1

**✅ Already checked by Claude:** The footer's subscribe form submits to the **exact same** backend endpoint and `emailsub` table as the top form — so it shares all the same validation and duplicate-prevention logic. The footer is a shared include, so it behaves the same on every page. *(You confirm the footer form behaves identically for a real user.)*

**What to do:**
1. Scroll to the footer on any page; submit a new email.
2. Confirm it lands in the same admin subscriber list as TC-B1.
3. Submit an already-subscribed email via the footer.
4. Try it on a page other than the homepage (e.g. `explorekl.php`).

**Expected results (tick each):**
- 👤 - [ ] Submitting shows the same success/confirmation message as the top form.
- 👤 - [ ] The subscriber shows up in the **same** admin list as TC-B1 (not a separate hidden list).
- 👤 - [ ] An already-subscribed email shows the same "already subscribed" message.
- 👤 - [ ] The footer form works the same on a non-homepage page.
- ✅ - [x] *(Code-verified by Claude: footer form uses the identical subscribe endpoint and table as the top form.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-B6 — Advertise With Us
**Files:** `advertisewithus.php` (form submits to itself), admin: `admin/edit-advertisement.php`

**✅ Already checked by Claude:** The page opens without code errors, the enquiry form has the expected fields (name, email, company, …), and enquiries are stored — the `contact_forms` table already holds **696 submissions**. *(Data capture works; you confirm the on-screen experience and where new entries land.)*

**What to do:**
1. Open Advertise With Us from the nav/footer.
2. Fill in and submit the enquiry form.
3. Check where the submission lands (email inbox and/or admin table).
4. Submit again with a required field left blank.

**Expected results (tick each):**
- 👤 - [ ] The link works and the page loads correctly.
- 👤 - [ ] The form submits successfully with a confirmation shown.
- 👤 - [ ] The submission is actually captured (email and/or admin) — not silently lost.
- 👤 - [ ] Leaving a required field blank prevents submission with a clear message.
- ✅ - [x] *(Code-verified by Claude: enquiries save to `contact_forms`, which already has 696 rows.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-B7 — Contribute an Article
**File:** `contribute.php` (form submits to itself)

**✅ Already checked by Claude:** The page opens without code errors and the form has the expected fields (name, email, article title, …). Submissions share the same enquiry storage as Advertise With Us (`contact_forms`, 696 rows). *(You confirm the on-screen flow and, if a file/image can be attached, that uploads work.)*

**What to do:**
1. Open Contribute an Article from the nav/footer.
2. Submit the form with sample article text (and a file/image if supported).
3. Check the site's file-size limit if you attach something.
4. Check where the submission lands.

**Expected results (tick each):**
- 👤 - [ ] The link works and the page loads correctly.
- 👤 - [ ] The form submits successfully.
- 👤 - [ ] Any attached file respects the upload size limit.
- 👤 - [ ] The submission is captured (email/admin) — not lost.

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked
**Notes / bugs found:**

---

## TC-B8 — "View on Map" Buttons Across All Sections
**Files:** `view_on_map_helper.php`, `kltg_mapcoords.php`, `beyondkl_mapcoords.php`, `map.php`

**✅ Already checked by Claude:** Every one of the six section pages has "View on Map" buttons that link to `map.php` (Explore KL and Beyond KL build them in directly; the other four use a shared helper file). The map-coordinate files open without code errors. *(The buttons exist and link to the map; you confirm each one focuses the correct location.)*

For each page, confirm on the live site:

| Page | Button on every card | Opens `map.php` | Correct location focused | Overall |
|---|---|---|---|---|
| Explore KL | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |
| Beyond KL | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |
| Medical Tourism | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |
| Places to Shop | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |
| Spa | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |
| Place to Stay | 👤 ✅ | 👤 ✅ | 👤 ✅ | ✅ |

- ✅ - [x] *(Code-verified by Claude: all six section pages contain "View on Map" buttons pointing to `map.php`.)*
- 👤 - [x] *(Confirmed working by User, 2026-07-06 — "view on map & map page works well".)*

**Notes / bugs found:**

---

## TC-B9 — Ebook Cover, Read, Download
**Files:** `ebook.php`, `ebook-details.php`, `ebook-track-download.php`

**✅ Already checked by Claude:** All three ebook pages open without code errors, and the `ebook` table has **45 ebooks** with columns for cover image, file name, view count, and download count. *(Storage + tracking exist; you confirm covers show and Read/Download actually work in a browser.)*

**What to do:**
1. Browse `ebook.php`, filter by a category.
2. Check cover thumbnails.
3. Open an ebook's details page.
4. Click "Read".
5. Click "Download".
6. Check a PDF-as-cover ebook (from TC-A4).
7. Test on a phone.

**Expected results (tick each):**
- 👤 - [x] Filtering by category shows the correct ebooks.
- 👤 - [x] Cover thumbnails load — no broken images, correct shape.
- 👤 - [x] The details page shows the correct title, description, and cover.
- 👤 - [x] "Read" opens the PDF correctly in the browser.
- 👤 - [ ] "Download" downloads the file, and the download is counted. *(⏳ testing now)*
- 👤 - [ ] A PDF-as-cover displays properly (not a blank/broken box).
- 👤 - [ ] Read and Download both work on a phone. *(pending mobile check — see TC-M)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked — *⏳ Partial: Read confirmed by User 2026-07-06; Download still being tested*
**Notes / bugs found:**

---

## TC-B10 — Blog List + Blog-Details Linking
**Files:** `blog.php`, `blog-details.php`

**✅ Already checked by Claude:** Both pages open without code errors and the `blog` table holds **576 posts**. *(You confirm new posts show and every link opens the right article.)*

**What to do:**
1. Browse `blog.php` after new posts are added (TC-A3).
2. Click each new post.
3. Test pagination / "load more" if present.
4. Test a very old / first-ever blog link.

**Expected results (tick each):**
- 👤 - [x] New posts appear in the correct order.
- 👤 - [x] Each new post opens the correct `blog-details.php` — no mismatched title/body.
- 👤 - [x] Pagination / "load more" works without duplicate or skipped posts.
- 👤 - [x] A very old blog link still opens correctly (no broken legacy links).

**Result:** [x] Pass  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06 ("blog is done")*
**Notes / bugs found:**

---

## TC-B11 — Map Page (All Sections/Subsections)
**Files:** `map.php`, `kltg_mapcoords.php`, `beyondkl_mapcoords.php`

**✅ Already checked by Claude:** The map page and both coordinate files open without code errors and the map page pulls in those coordinate sources. **What Claude cannot test:** whether the Google Maps tiles actually load (that needs a browser and a valid Maps key/quota) — that's your 👤 job.

**What to do:**
1. Open `map.php` directly (no extras in the address).
2. Arrive via a "View on Map" link from each section.
3. Check the Google Map itself.
4. Test on a phone.

**Expected results (tick each):**
- 👤 - [x] Opening the map directly shows a sensible default view.
- 👤 - [x] Each "View on Map" link centres the map on the correct location.
- 👤 - [x] The Google Map tiles load — no API-key/quota error on the map or in the browser console.
- 👤 - [ ] The map is usable on a phone (pinch/zoom, tap pins) and not cut off. *(pending mobile check — see TC-M)*

**Result:** [x] Pass (desktop)  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06 ("works well"); confirm on mobile*
**Notes / bugs found:**

---

## TC-B12 — Events: Linking + Category Filter
**Files:** `event.php`, `event-details.php`

**✅ Already checked by Claude:** Both pages open without code errors; the `event` table has **210 events** and an `event_category` column that drives the filter. *(The filter is backed correctly; you confirm every event lands in the right category and details pages match.)*

**What to do:**
1. Open `event.php`; try each category filter.
2. Click several events across different categories.
3. Confirm no event is mis-categorised.
4. Check an event whose date has passed.

**Expected results (tick each):**
- 👤 - [x] Each filter shows only events tagged with that category.
- 👤 - [x] Each event's details page shows the matching description/date/image (from TC-A12).
- 👤 - [x] Every event lands in its intended category (none wrongly uncategorised).
- 👤 - [ ] A past-dated event behaves as expected (note whether it's still listed or hidden). *(optional edge case)*

**Result:** [x] Pass  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06 ("event filter works")*
**Notes / bugs found:**

---

## TC-B13 — Merchandise Buy Flow + DuitNow QR Payment
**Files:** `merchandise.php`, `order.php`, admin Store Settings (`merchandise_settings`), Orders (`merchandise_orders`)

**✅ Already checked by Claude — important update:** The DuitNow QR payment flow is **already built in the code** (this was previously thought to be missing). Specifically: the `merchandise_settings` table stores a **payment QR image** and a **WhatsApp number**; the checkout page (`order.php`) displays that QR (or a "Payment QR is not set up yet" message if none is uploaded); it **requires** a payment receipt upload; it saves the order to the `merchandise_orders` table (which has customer name/email/phone/address + receipt image); and it hands off to WhatsApp via a `wa.me` link. There are already **3 orders** in the table. **So the likely remaining task is simply uploading the QR image in Admin → Store Settings, not building the feature.** Please verify the actual state on the live site.

**What to do:**
1. In Admin → Store Settings, confirm/upload the DuitNow QR image + WhatsApp number.
2. On `merchandise.php`, open a product → "Buy Now".
3. Look at the checkout page's QR block.
4. Fill in name/email/phone/address, upload a receipt image, submit.
5. Try submitting **without** a receipt.
6. Try uploading a non-image or oversized (>10MB) file as the receipt.
7. Check the WhatsApp hand-off.
8. Check Admin → Orders.

**Expected results (tick each):**
- 👤 - [ ] Store Settings saves the QR image and WhatsApp number. *(⏳ QR not uploaded yet)*
- 👤 - [x] "Buy Now" opens `order.php` with the correct product/price.
- 👤 - [ ] The checkout shows the **actual DuitNow QR image** (not the "not set up yet" message). *(⏳ QR not set up yet — this is the remaining step)*
- 👤 - [ ] Filling the form + uploading a receipt completes the purchase. *(re-test once QR is set up)*
- 👤 - [ ] Submitting **without** a receipt is blocked with a clear "please upload receipt" message.
- 👤 - [ ] A non-image / oversized receipt is rejected gracefully.
- 👤 - [ ] The WhatsApp hand-off opens `wa.me` with the order message pre-filled.
- 👤 - [ ] The new order appears in Admin → Orders with correct details and a viewable receipt.
- ✅ - [x] *(Code-verified by Claude: QR storage, required receipt upload, order saving, and WhatsApp hand-off are all implemented; 3 orders already recorded. The feature is built — it just needs the QR image uploaded in Store Settings.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked — *⏳ Partial: "Buy Now" flow confirmed by User 2026-07-06; DuitNow QR not yet set up (upload QR image in Admin → Store Settings, then finish the checkout tests)*
**Notes / bugs found:**

---

## TC-B14 — About Us FAQ: Chat Assistant + View Opportunities
**File:** `aboutus.php` (dual panel: FAQ + Bluedale)

**✅ Already checked by Claude:** The page opens without code errors; the FAQ panel's "Ask our Chat Assistant" button is wired to open the chat widget; and the "View Opportunities" button links to `https://bluedale.com.my/` and opens in a new tab. *(The buttons and links are correctly set; you confirm they behave right when clicked.)*

**What to do:**
1. Open `aboutus.php`, expand the FAQ panel.
2. Click "Ask our Chat Assistant".
3. Click "View Opportunities" in the Bluedale panel.
4. Confirm bluedale.com.my loads.
5. Test both buttons on a phone.

**Expected results (tick each):**
- 👤 - [x] FAQ items expand/collapse correctly.
- 👤 - [x] "Ask our Chat Assistant" opens the chatbot widget (cross-check TC-B2).
- 👤 - [x] "View Opportunities" opens `https://bluedale.com.my/` in a new tab.
- 👤 - [x] bluedale.com.my actually loads (no broken link on their end).
- 👤 - [ ] Both buttons work on a phone (chat opens; external link opens in a new tab/app). *(pending mobile check — see TC-M)*
- ✅ - [x] *(Code-verified by Claude: chat button wired to the chat widget; "View Opportunities" links to bluedale.com.my in a new tab.)*

**Result:** [x] Pass (desktop)  [ ] Fail  [ ] Blocked — *tested by User, 2026-07-06 ("about us button works"); confirm on mobile*
**Notes / bugs found:**

---

## TC-M — Mobile / Responsive Layout
**Applies to:** every public page.

**✅ Already checked by Claude — the site IS built for mobile.** I inspected the code and confirmed the foundations for mobile are all correctly in place:

| What was checked | Finding | Meaning in plain English |
|---|---|---|
| Mobile "viewport" tag in the page head (`header.php`) | ✅ Present (`width=device-width, initial-scale=1.0`) | This is the single most important setting — it tells phones to display the site at phone width instead of a shrunk-down desktop. Without it, a site looks tiny on mobile. It's there. |
| Bootstrap responsive framework | ✅ Loaded (`bootstrap.min.css` + grid) | The site uses a professional responsive layout system that rearranges columns to stack on small screens. |
| Number of "responsive rules" (media queries) across the site's CSS | ✅ 58 found | These are the instructions that resize/rearrange things at different screen widths. 58 is healthy coverage. |
| Responsive images | ✅ Yes | Older pages use Bootstrap's `img-fluid` (images shrink to fit); newer pages (merchandise, order, ebook, KL@Glance) use `width:100%` / `max-width` / `object-fit`; the events page CSS also uses `width:100%` + `object-fit:cover`. All standard responsive techniques. |
| Sideways-scroll safety net | ✅ `overflow-x:hidden` set globally | Helps stop the page from scrolling sideways on phones (a common mobile bug). |

**So: this is not a desktop-only website — it is configured for mobile.** However, "correctly configured" does **not** guarantee every page *looks* perfect on a real phone (an oversized image, a long word, or a wide table can still overflow). So the visual confirmation below is still your 👤 job.

**What to do:** Open each page on a real phone **and/or** in Chrome's device toolbar (press F12 → click the phone/tablet icon → pick "iPhone" or set width ~375px). Scroll each page top to bottom.

**Expected results (tick each):**
- 👤 - [ ] The homepage looks right on a phone — banner, menus, and text are readable, nothing overlaps.
- 👤 - [ ] No page scrolls **sideways** (you should only ever scroll up/down).
- 👤 - [ ] Images fit the screen — none are cut off, stretched, or spilling past the edge.
- 👤 - [ ] The navigation menu collapses into a working "hamburger" (☰) menu on phones.
- 👤 - [ ] Section pages (Explore KL, Beyond KL, Medical Tourism, Shop, Spa, Stay) stack neatly; cards and "View on Map" buttons are tappable.
- 👤 - [ ] Ebook covers, blog cards, event cards, and merchandise cards all display well on a phone.
- 👤 - [ ] The chatbot widget and the checkout page (`order.php`) are usable on a phone.
- 👤 - [ ] Forms (subscribe, advertise, contribute, order) are easy to fill on a phone.
- ✅ - [x] *(Code-verified by Claude: viewport tag, Bootstrap, 58 media queries, responsive images, and overflow-x guard are all present — the mobile foundation is correctly set up.)*

**Result:** [ ] Pass  [ ] Fail  [ ] Blocked — *⏳ Config verified by Claude 2026-07-06; visual check on a real phone still needed*
**Notes / bugs found:**

---

# Section C — Cross-Cutting Regression Checklist

Run this quick pass after each batch of changes and before go-live sign-off:

- 👤 - [ ] No error messages appear on any page you edited.
- 👤 - [ ] No red errors in the browser console on pages you touched (press F12 → Console).
- 👤 - [ ] Every page you touched looks right on a phone (~375px wide) and on a computer.
- 👤 - [ ] New images are reasonably sized (not multi-MB) so pages don't lag.
- 👤 - [ ] New uploads respect the site's upload size limit.
- 👤 - [ ] The site still loads after any database/login-related change (the shared `admin/functions.php` powers every page).
- 👤 - [ ] Newsletter/queue sending still works if any email code was changed.
- 👤 - [ ] The hidden DevPanel ad-hiding still works if `header.php` was changed.

---

# Appendix — Open Items to Confirm Before Sign-Off

1. **DuitNow QR (TC-B13):** The feature is already coded. The real remaining step is almost
   certainly just **uploading the QR image** in Admin → Store Settings on the live site — not
   building anything. Confirm on the live/production site.

2. **Duplicate-email database lock (`db_migration_email_dedupe.sql`):** This extra safeguard is
   **not yet applied** to the database (Claude confirmed the unique-email index is missing).
   Duplicate signups are already prevented by the program code, so this is a backup measure —
   but apply this SQL file (in phpMyAdmin) on the live database at/before go-live.

3. **Chatbot key on the live server (TC-B2):** The Gemini key exists **locally**, but earlier
   project notes say it was **missing from the deployment bundle**. Confirm the key is set on the
   live server, or the chatbot will not answer there.

4. **Two merchandise/order database migrations** were noted as pending on production in project
   notes. Confirm both are applied on the live server before running TC-A5 / TC-B13 there.

5. **If anything works locally but not on the live server (or vice-versa):** the usual cause is
   the database-connection settings. The live-serving database is `kltheguidecom_bluedale2_kltg`;
   a near-duplicate `bluedale2_kltg` also exists locally but is **not** the one the site uses —
   don't run migrations against the wrong one.

---

## Testing coverage summary

- **Claude tested automatically (code + database level):** all 30 public pages and 26 admin
  files open without code errors; the database connects; every required table exists and holds
  real content; and the "plumbing" for subscriptions, merchandise checkout + QR, enquiry forms,
  Insider→blog links, event category filtering, the chatbot, and the map buttons is correctly
  wired. See the summary table near the top.
- **You still need to test manually (marked 👤):** everything visual and interactive — real
  form submissions, email delivery, chatbot answers, image rendering, mobile layout, Google Maps
  loading, external links, file uploads/downloads, and the DuitNow QR at checkout.
