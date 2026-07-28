# Admin CRUD + Map Pin Fix — deployment guide

Covers the six sections: **Explore KL, Beyond KL, Medical Tourism, Places to Shop,
Spa, Place to Stay**.

- **Target:** `new.kltheguide.com.my` (docroot)
- **Database:** `kltheguidecom_bluedale2_kltg`

> ⚠️ That database is **shared with the main kltheguide.com.my site**. The schema
> changes below are additive (a new column) plus an id renumber, so the main
> site's existing pages keep working untouched. But if the main site has its own
> copy of `admin/`, that copy still contains all the bugs listed here — an editor
> using the old panel can still corrupt rows. Either point everyone at the new
> panel or deploy these files to both.

---

## 1. Run the SQL first — in this exact order

From cPanel → phpMyAdmin → database `kltheguidecom_bluedale2_kltg` → Import.

**Take a database backup before you start.**

| # | File | What it does | Destructive? |
|---|------|--------------|--------------|
| 1 | `admin/mapcoords_migration.sql` | Adds a `<table>_mapcoords` column to all 26 place tables | No — additive, re-runnable |
| 2 | `admin/mapcoords_seed.sql` | Fills in 259 exact coordinates | No — only writes the new column |
| 3 | `admin/primarykey_migration.sql` | **The important one.** Gives 15 tables a real PRIMARY KEY + AUTO_INCREMENT | Renumbers ids; no data lost |

| 4 | `admin/traveltips_migration.sql` | Creates the `traveltips` table | No — new table |
| 5 | `admin/traveltips_seed.sql` | Fills in the existing tips | No — only writes the new table |

Steps 4 and 5 are **not optional in this deploy**: the new `admin/functions.php`
and `travel-tips.php` both expect that table (see the warning in §2).

`admin/accommodation_dedupe.sql` is a **sixth, optional** file. It deletes rows,
its DELETEs are commented out, and it is not part of this deploy — see §5.

### Three things that can go wrong during the import

1. **phpMyAdmin aborts the whole import on the first error.** In
   `primarykey_migration.sql` an already-migrated table raises *"Multiple primary
   key defined"* — and everything **after** it silently never runs, leaving the
   migration half-applied. After importing, verify all 15 tables took it:
   ```sql
   SELECT TABLE_NAME, COLUMN_NAME, COLUMN_KEY, EXTRA
   FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND EXTRA = 'auto_increment'
     AND TABLE_NAME IN ('explorekl_hs','explorekl_pwor','explorekl_wte_sf',
       'explorekl_wte_c','explorekl_wte_r','medical_tourism_ps','spa',
       'accommodation_top','accommodation_h','accommodation_bh','accommodation_bks',
       'explorekl_nav','beyondkl_nav','accommodation_nav','medical_tourism_nav');
   ```
   You want **15 rows back**. Fewer means the import stopped early — re-run only
   the statements for the tables that are missing.

2. **`mapcoords_migration.sql` creates a temporary stored procedure**, so the DB
   user needs the `CREATE ROUTINE` privilege. If phpMyAdmin refuses with an access
   error, grant *All Privileges* to the user in cPanel → MySQL Databases, or add
   the 26 columns by hand with plain
   `ALTER TABLE <t> ADD COLUMN <t>_mapcoords VARCHAR(64) NULL DEFAULT NULL;`
   Verify afterwards — you want **26**:
   ```sql
   SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME LIKE '%\_mapcoords';
   ```

3. **A large import can return HTTP 502** in phpMyAdmin while actually succeeding.
   Check row counts before assuming it failed and re-importing.

All three SQL files have been verified against a local copy of this exact database:
the 26 mapcoords columns, all 15 primary keys, and the 259 seed coordinates apply
cleanly, and every column name matches what the PHP expects.

### Why step 3 matters most

Fifteen tables were created with `<prefix>_id int(11) NOT NULL` — no primary key,
no auto-increment. Nothing generated an id, so **every row ever added through the
CMS was saved with id 0**. Since the edit and delete modals identify a row purely
by that id:

- "Add New" seemed to work, but the row shared id 0 with every other added row
- the pen icon on any of them opened the same record
- "Save Changes" ran `UPDATE … WHERE id = 0` and rewrote **all** of them
- "Delete" ran `DELETE … WHERE id = 0` and removed **all** of them

Worst case found: all 10 Explore KL → Cafés sat on id 0.

Affected: `explorekl_hs`, `explorekl_pwor`, `explorekl_wte_sf`, `explorekl_wte_c`,
`explorekl_wte_r`, `medical_tourism_ps`, `spa`, all four `accommodation_*`, and all
four `*_nav` tables.

---

## 2. Then upload these files

> **A prebuilt archive of every file below already exists:**
> `D:\kltg_deploy_admincrud\kltg-admincrud-2026-07-28.tar.gz` (54 files, 139 KB).
> Upload + extract that and you cannot miss one. See §6 for the exact cPanel steps.

### New files (11)

```
admin/js/edita.js
admin/pagefunctions/edit-sectionnav.php
admin/pagefunctions/public-viewcount.php
admin/pagefunctions/edit-traveltips.php     ← REQUIRED by functions.php (see below)
admin/edit-traveltips.php                   ← REQUIRED by nav.php (see below)
admin/mapcoords_migration.sql
admin/mapcoords_seed.sql
admin/primarykey_migration.sql
admin/accommodation_dedupe.sql
admin/traveltips_migration.sql
admin/traveltips_seed.sql
```

`admin/js/edita.js` never existed even though `edit-accomodation.php` has always
loaded it — which is why **every edit button on Place to Stay did nothing**.

> ⚠️ **The travel-tips files are not optional here, even though they belong to a
> different piece of work.** `admin/functions.php` — which this deploy replaces —
> ends with `include 'pagefunctions/edit-traveltips.php';` (line 1905), and
> `admin/nav.php` — also in this deploy — has a sidebar link to
> `edit-traveltips.php`. Upload the new `functions.php` without the travel-tips
> handler and PHP emits an include warning on **every page of the whole site**,
> public and admin, because every page includes `functions.php`. With
> `display_errors` on that prints visible text above the page and can trigger
> "headers already sent", breaking redirects and the admin login. Ship them together.

### Changed — public pages (9)

```
explorekl.php
beyondkl.php
medical-tourism.php
where-to-shop.php
spa.php
accommodation.php
view_on_map_helper.php
travel-tips.php        ← now reads the `traveltips` table; pairs with the SQL above
footer.php             ← adds the E-book footer link
```

### Changed — admin core (2)

```
admin/functions.php
admin/nav.php          ← upload this one; it carries the Travel Tips link + menu cleanup
```

### Changed — admin editors (8)

```
admin/edit-explorekl.php
admin/edit-beyondkl.php
admin/edit-medical-tourism.php
admin/edit-places-to-shop.php
admin/edit-spa.php
admin/edit-accomodation.php
admin/edit-blog.php      ← removes the dead "Blog SITEMAP" card
admin/edit-event.php     ← retitled "Event", matching the new nav.php label
```

### Changed — admin handlers (19)

```
admin/pagefunctions/edit-explorekl-wtd.php
admin/pagefunctions/edit-explorekl-hs.php
admin/pagefunctions/edit-explorekl-kl4k.php
admin/pagefunctions/edit-explorekl-p.php
admin/pagefunctions/edit-explorekl-pwor.php
admin/pagefunctions/edit-explorekl-nl.php
admin/pagefunctions/edit-explorekl-ss.php
admin/pagefunctions/edit-explorekl-wte.php
admin/pagefunctions/edit-beyondkl-i.php
admin/pagefunctions/edit-beyondkl-hs.php
admin/pagefunctions/edit-beyondkl-w.php
admin/pagefunctions/edit-beyondkl-h.php
admin/pagefunctions/edit-beyondkl-es.php
admin/pagefunctions/edit-mt.php
admin/pagefunctions/edit-pts.php
admin/pagefunctions/edit-spa.php
admin/pagefunctions/edit-accomodation.php
admin/pagefunctions/edit-blog.php
admin/pagefunctions/edit-advertisement.php   ← surfaces DB errors instead of a false "success"
```

### Changed — admin JS (5)

```
admin/js/editexplorekl.js
admin/js/editbeyondkl.js
admin/js/editmt.js
admin/js/editpts.js
admin/js/editspa.js
```

**Total: 11 new + 43 changed = 54 files.**

---

## 3. Should you re-upload the whole six sections anyway?

**No — upload the list above, not the whole tree.** Two reasons:

1. `admin/functions.php` and `admin/nav.php` carry **both** this fix *and* the
   earlier travel-tips / advertisement / e-book-footer work. That is exactly why
   the travel-tips files are folded into the list above — the two sets of changes
   can no longer be shipped separately. A blanket re-upload from a *different*
   copy of the tree could quietly revert one half of it.
2. `admin/vendor/`, `assets/vendor/` and the ebook PDFs are large and unchanged.
   Re-uploading them wastes time and risks a half-finished transfer leaving the
   panel broken.

There is **no risk in re-uploading an unchanged file** — it's byte-identical.
The risk is re-uploading a *stale* one. Use the tarball in §6 and this cannot
go wrong.

**Never upload:** `.env`, `admin/.env`, `--settingsEnv/.env`, `admin/node_modules/`,
`assets/img/receipts/`, or the `*.md` guides. The prebuilt tarball already excludes
all of them (verified).

### Do NOT forget

`admin/functions.php` is the single most important file here — the new
`cms_place_crud()`, `cms_store_image()` and `mapcoords_from_post()` helpers live
in it, and the rewritten handlers call them. **If you upload the handlers without
`functions.php`, every one of the six sections will fatal with
"Call to undefined function cms_place_crud()".** Upload `functions.php` first.

---

## 4. After deploying — 5-minute smoke test

1. Log into `/admin/`, open **Place to Stay**. Click a pen icon — the edit modal
   should open (it never did before).
2. In that modal, use **Replace image**, save, and confirm the picture changed on
   the public page.
3. Add a new entry in **Spa**, then edit it, then delete it. Confirm only that one
   row is affected.
4. Open **Medical Tourism → Healthcare**, click a pen icon (this one also used to
   do nothing).
5. On a public page, click **View on Map** for a well-known place and check the
   pin lands on the right building.
6. **Load the public home page and look at the very top of it.** If you see a PHP
   warning about `pagefunctions/edit-traveltips.php`, that file didn't make it up —
   re-extract the archive. This is the one failure that affects the entire site
   rather than a single section, so check it first.
7. Open **Travel Tips** in the sidebar and confirm the editor lists items, then
   load public `travel-tips.php` and confirm the tips render (this needs SQL
   files 4 and 5 from §1).

If something looks wrong, the fastest check is the browser console (F12) — every
failure mode fixed here showed up there as a JavaScript error.

---

## 5. Optional: the Place to Stay duplicates

Separate from everything above. Every row in the four `accommodation_*` tables
exists **twice**, identical in every column — 94 rows, 47 unique. The public page
lists each hotel twice. This is old imported data, not a CMS bug.

`admin/accommodation_dedupe.sql` contains a preview query and the DELETE
statements **commented out**. Run the preview against production first, check the
numbers, then decide. Requires `primarykey_migration.sql` to have been applied
(without unique ids the DELETEs cannot tell the copies apart).

You can also just delete the extra copies from the admin panel now that the ids
are unique and delete actually works.

---

## 6. cPanel procedure for this deploy

`cPanel_Deployment_Guide.md` describes the **CRM** (`crm.kltheguide.com.my`), which
is a Laravel app. Only its *packaging and upload* half applies here. This site is
plain PHP — there is **no `npm run build`**, **no `vendor/` to ship** (it is already
on the server and unchanged), and **no `artisan migrate` cron job**. The SQL in §1
goes through phpMyAdmin by hand.

### Steps

1. **Back up the database first** — cPanel → phpMyAdmin → select
   `kltheguidecom_bluedale2_kltg` → Export → Go. Do not skip this; §1 step 3
   renumbers ids.
2. **Back up the files you are about to overwrite.** In File Manager, select the
   existing `admin/` folder and `Compress` it to a dated zip before extracting.
   Tar extraction overwrites in place and there is no undo.
3. Run the SQL from §1, **files 1 → 2 → 3 → 4 → 5, in that order**, and run the
   verification queries after step 3.
4. cPanel → **File Manager** → navigate to the `new.kltheguide.com.my` docroot
   (`/home/kltheguidecom/public_html/new.kltheguide.com.my/` — confirm the exact
   path in cPanel → Domains before uploading).
5. **Upload** `kltg-admincrud-2026-07-28.tar.gz` from `D:\kltg_deploy_admincrud\`.
6. Right-click it → **Extract** → into that same docroot. Paths inside the archive
   are docroot-relative (`admin/...`, `explorekl.php`, …), so they land correctly.
7. **Delete the `.tar.gz`** afterwards so it isn't publicly downloadable.
8. Hard-refresh, or test in **Incognito** — the admin JS files changed, and a
   cached old `editspa.js` / `editmt.js` will look like the fix didn't work.

### Notes carried over from the CRM guide that still apply

- **Tar extraction never deletes files.** This deploy removes nothing, so there is
  no manual-delete list. The nav entries dropped in `nav.php` (Email Campaign, Page
  Views, Ad Popup Reach, Voucher Claims Contest) only unlink those pages — the files
  stay on the server on purpose.
- **Don't assume an earlier package was uploaded.** If the panel misbehaves after
  this, check a file's timestamp in File Manager rather than trusting notes.
- `--force-local` was needed to build this tarball on Windows (drive letter), and
  was used.

### If it goes wrong

The single fastest rollback is to re-extract the `admin/` zip from step 2. The
database changes are additive and do **not** need rolling back — old code ignores
the `_mapcoords` columns, and the primary keys are harmless to code that doesn't
use them. Only the id renumbering is one-way, which is why step 1 exists.
