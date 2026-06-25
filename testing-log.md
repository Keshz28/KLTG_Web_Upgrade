# Testing Log — KL The Guide (kltheguide.com.my)
**Environment:** InfinityFree Hosting (Pre-Launch)
**Tester:** Sukesh

---

## Day 1 — 21 June 2026

### Summary

| # | Feature | Status | Action Taken |
|---|---------|--------|--------------|
| 1 | Landing Page Hero Video | Fixed (with caveat) | Converted MP4 → WebP |
| 2 | Banner | Pending Fix | Identified; needs admin + code review |
| 3 | KL @ A Glance — Scrolling Pages | Fixed | Adjusted code + added SQL; uploaded images manually |
| 4 | Exclusive Recommendations | Pass | No issues found |

### Findings

#### 1. Landing Page Hero Video — Fixed (with caveat)
**Issue:** Hero video was not playing. InfinityFree does not support serving `.mp4` files.
**Fix:** Replaced the MP4 with a WebP animated file.
**Remaining concern:** The WebP conversion resulted in a noticeable drop in visual quality — the video appears pixelated at current resolution. A better long-term solution is needed (e.g. embedding from an external host like YouTube or Cloudflare Stream).

---

#### 2. Banner — Pending Fix
**Issue:** Banner is not displaying or behaving correctly on the live environment.
**Status:** Needs further investigation via the admin dashboard. A code fix may be required.
**Next step:** Check admin banner settings; inspect banner-related PHP/JS for hosting-specific compatibility issues.

---

#### 3. KL @ A Glance — Scrolling Pages — Fixed
**Issue:** Section was stuck on the title/first slide and would not scroll to subsequent pages.
**Root cause:** Missing database entries and a code-side issue with the scrolling logic.
**Fix:**
- Adjusted the relevant PHP/JS code to correct the scroll behaviour.
- Added the required SQL entries and imported them via InfinityFree's SQL manager.
- Individually uploaded the required images for each scrolling page.

---

#### 4. Exclusive Recommendations — Pass
**Status:** Working correctly. All card links, destination pages, and animations are functioning as expected. No issues found.

---

### Day 1 Open Items

| Item | Priority |
|------|----------|
| Improve landing page video quality (pixelation) | Medium |
| Investigate and fix banner issue | High |

---

---

## Day 2 — 22 June 2026

### Summary

| # | Feature / Area | Status | Action Taken |
|---|----------------|--------|--------------|
| 1 | Getting Around KL | Pass | No issues found |
| 2 | Travel Tips | Pass | No issues found |
| 3 | Blog | Pass | No issues found |
| 4 | Admin Security — Session Timeout (server-side) | Verified | Confirmed working |
| 5 | Admin Security — JS Inactivity Timer | Verified | Confirmed working |
| 6 | Admin Security — Sensitive Page Gating | Verified | Confirmed working |
| 7 | Admin Dashboard — Banner Display | Fixed | Code structure corrected; images uploaded via FileZilla |
| 8 | Admin Dashboard — Post New Banner | Fixed | Fixed posting flow; confirmed new banner publishes correctly |

---

### Findings

#### 1. Getting Around KL — Pass
**Status:** All content, links, and connections working perfectly on the live environment. No issues found.

---

#### 2. Travel Tips — Pass
**Status:** All external website links are functioning correctly. No broken links found.

---

#### 3. Blog — Pass
**Status:** Blog is correctly connected to the Blogger extension and displaying the latest posts as expected. No issues found.

---

#### 4. Admin Security — Session Timeout (Server-Side) — Verified
**What was tested:** Server destroys the session after 30 minutes of inactivity and redirects to the login page with a timeout message.
**Result:** Confirmed working correctly as per the security fixes documented in `admin security.md` (Fix 10).

---

#### 5. Admin Security — JS Inactivity Timer — Verified
**What was tested:** JavaScript timer on every admin page auto-redirects after 30 minutes of no mouse or keyboard activity.
**Result:** Confirmed working correctly across admin pages tested.

---

#### 6. Admin Security — Sensitive Page Gating — Verified
**What was tested:** `export.php` and `vapid.php` are gated behind the admin login.
**Result:** Both pages correctly redirect unauthenticated users to the login page. Confirmed working.

---

#### 7. Admin Dashboard — Banner Display — Fixed
**Issue:** Banner was not showing on the live website, even though the banner list was being displayed correctly inside the admin dashboard.
**Investigation:** Images were uploaded to the server via FileZilla to rule out missing asset issues.
**Root cause:** The code structure for rendering the banner list did not correctly output all displayed items to the live frontend.
**Fix:** Corrected the code structure so that all banners listed in the admin dashboard are now correctly rendered on the live site.

---

#### 8. Admin Dashboard — Post New Banner — Fixed
**Issue:** After fixing the display issue, attempting to post a new banner from the admin dashboard resulted in an error — the banner could not be published.
**Fix:** Identified and corrected the posting flow.
**Confirmed behaviour:** A new banner must be published from the admin dashboard first before it will appear on the live site. This is the expected workflow.
**Note for cPanel launch:** All banner assets and DB entries will need to be re-uploaded and re-published during the final push to cPanel to ensure banners display correctly on production.

---

### Day 2 Open Items

| Item | Priority | Note |
|------|----------|------|
| Landing page video pixelation (carried from Day 1) | Medium | Consider external video host |
| Re-upload all assets + re-publish banners on cPanel | High | Required step during final production push |

---

---

## Cumulative Status

| Feature / Area | Day 1 | Day 2 |
|----------------|-------|-------|
| Landing Page Hero Video | Fixed (pixelation caveat) | Carried forward |
| Banner | Pending | Fixed |
| KL @ A Glance | Fixed | — |
| Exclusive Recommendations | Pass | — |
| Getting Around KL | — | Pass |
| Travel Tips | — | Pass |
| Blog | — | Pass |
| Admin Security | — | Verified |

---

---

## Testing Summary — All Days

| # | Feature / Area | Day Tested | Status | Remarks |
|---|----------------|------------|--------|---------|
| 1 | Landing Page Hero Video | Day 1 | ⚠️ Fixed (caveat) | MP4 → WebP; still pixelated — long-term fix needed |
| 2 | Banner (live display) | Day 1 → Day 2 | ✅ Fixed | Code structure corrected; assets re-uploaded via FileZilla |
| 3 | Post New Banner (admin) | Day 2 | ✅ Fixed | Must publish new banner first; re-upload required on cPanel |
| 4 | KL @ A Glance — Scrolling | Day 1 | ✅ Fixed | Code adjusted; SQL added; images uploaded manually |
| 5 | Exclusive Recommendations | Day 1 | ✅ Pass | Links, pages, and animations all working |
| 6 | Getting Around KL | Day 2 | ✅ Pass | All content and connections working |
| 7 | Travel Tips | Day 2 | ✅ Pass | All external links working |
| 8 | Blog | Day 2 | ✅ Pass | Connected to Blogger; latest posts displaying correctly |
| 9 | Admin Security — Session Timeout | Day 2 | ✅ Verified | Server destroys session after 30 min inactivity |
| 10 | Admin Security — JS Inactivity Timer | Day 2 | ✅ Verified | Auto-redirects after 30 min of no activity |
| 11 | Admin Security — Sensitive Page Gating | Day 2 | ✅ Verified | export.php and vapid.php gated behind login |

### Open Items

| # | Item | Priority | Notes |
|---|------|----------|-------|
| 1 | Landing page video pixelation | Medium | Consider embedding from external host (YouTube / Cloudflare Stream) |
| 2 | Re-upload assets + re-publish banners on cPanel | High | Required step during final production push |

---

---

## Day 3 — 23 June 2026

### Summary

| # | Feature / Area | Status | Action Taken |
|---|----------------|--------|--------------|
| 1 | Getting Around KL — Page Load Speed | Fixed | Reduced cache size via code to improve load time |
| 2 | Getting Around KL — Option Links | Pass | All linking works correctly |
| 3 | Getting Around KL — E-Hailing Services | Fixed | Code adjusted; unnecessary information removed |
| 4 | Explore KL — Historical Sites | Pass | Images display correctly; map links all working |
| 5 | Explore KL — What To Do | Partial (hosting issue) | Images missing due to symbol characters in folder names — InfinityFree limitation, not a code issue |
| 6 | Explore KL — Places of Worship, What To Eat, Night Life, KL 4 Kids, Sightseeing, Parks | Partial (hosting issue) | Most images working; some missing due to symbol characters in filenames — will work on cPanel |
| 7 | Beyond KL — All Sections | Pass | Links and images all working correctly |
| 8 | Medical Tourism — All Sections | Pass | Links and images all working correctly |
| 9 | Shop Like Locals — All Sections | Pass | Links and images working; 1 image missing due to wrong format — will work on cPanel |
| 10 | Spa Time — All Sections | Pass | Links and images all working correctly |
| 11 | Place to Stay — All Sections | Pass | Links and images all working correctly |
| 12 | Merchandise Page — Admin Panel | In Progress | New admin edit panel created with full edit functions; not yet tested end-to-end with dashboard |

---

### Findings

#### 1. Getting Around KL — Page Load Speed — Fixed
**Issue:** Page was taking too long to load due to a large number of images.
**Fix:** Reduced cache size via code changes to improve page load performance.

---

#### 2. Getting Around KL — Option Links — Pass
**Status:** All option links are working correctly. No issues found.

---

#### 3. Getting Around KL — E-Hailing Services — Fixed
**Issue:** E-hailing section contained unnecessary information and had a code issue.
**Fix:** Code corrected and unnecessary information removed. Section now displays cleanly.

---

#### 4. Explore KL — Historical Sites — Pass
**Status:** All images displaying correctly. All map deep-link buttons working as expected. No issues found.

---

#### 5. Explore KL — What To Do — Partial (Hosting Limitation)
**Issue:** Images not displaying for all options.
**Root cause:** Image folder names contain symbol characters. InfinityFree does not support symbol characters in directory/file names — this is a hosting limitation, not a code issue.
**Resolution:** Will work correctly on cPanel where this restriction does not apply. No code change needed.

---

#### 6. Explore KL — Places of Worship, What To Eat, Night Life, KL 4 Kids, Sightseeing, Parks — Partial (Hosting Limitation)
**Issue:** Some images across these sections are not displaying.
**Root cause:** Same as above — symbol characters in filenames are not supported by InfinityFree.
**Resolution:** Most images are working correctly where filenames use the correct format. Affected images will display properly on cPanel. No code change needed.

---

#### 7. Beyond KL — All Sections — Pass
**Status:** All sections tested. Links, images, and content all working correctly. No issues found.

---

#### 8. Medical Tourism — All Sections — Pass
**Status:** All sections tested. Links, images, and content all working correctly. No issues found.

---

#### 9. Shop Like Locals — All Sections — Pass
**Status:** All sections tested. Links and most images working correctly.
**Minor note:** 1 image not visible due to wrong file format. Will display correctly on cPanel.

---

#### 10. Spa Time — All Sections — Pass
**Status:** All sections tested. Links, images, and content all working correctly. No issues found.

---

#### 11. Place to Stay — All Sections — Pass
**Status:** All sections tested. Links, images, and content all working correctly. No issues found.

---

#### 12. Merchandise Page — Admin Panel — In Progress
**Issue:** No admin management option existed for the Merchandise page.
**Action taken:** Created a new admin panel for Merchandise with full edit functions.
**Status:** Admin panel created but not yet tested end-to-end with the live dashboard. Testing pending.

---

### Day 3 Open Items

| # | Item | Priority | Notes |
|---|------|----------|-------|
| 1 | Merchandise admin panel — end-to-end test | High | New panel created; needs full dashboard testing |
| 2 | Symbol-named image files (Explore KL sections) | Low | InfinityFree limitation only; will resolve on cPanel — no code fix needed |

---

---

## Testing Summary — All Days

| # | Feature / Area | Day Tested | Status | Remarks |
|---|----------------|------------|--------|---------|
| 1 | Landing Page Hero Video | Day 1 | ⚠️ Fixed (caveat) | MP4 → WebP; still pixelated — long-term fix needed |
| 2 | Banner (live display) | Day 1 → Day 2 | ✅ Fixed | Code structure corrected; assets re-uploaded via FileZilla |
| 3 | Post New Banner (admin) | Day 2 | ✅ Fixed | Must publish new banner first; re-upload required on cPanel |
| 4 | KL @ A Glance — Scrolling | Day 1 | ✅ Fixed | Code adjusted; SQL added; images uploaded manually |
| 5 | Exclusive Recommendations | Day 1 | ✅ Pass | Links, pages, and animations all working |
| 6 | Getting Around KL — Page Load Speed | Day 3 | ✅ Fixed | Cache size reduced via code |
| 7 | Getting Around KL — Option Links | Day 2 & 3 | ✅ Pass | All connections and links working |
| 8 | Getting Around KL — E-Hailing Services | Day 3 | ✅ Fixed | Code adjusted; unnecessary info removed |
| 9 | Travel Tips | Day 2 | ✅ Pass | All external links working |
| 10 | Blog | Day 2 | ✅ Pass | Connected to Blogger; latest posts displaying correctly |
| 11 | Explore KL — Historical Sites | Day 3 | ✅ Pass | Images and map links all working |
| 12 | Explore KL — What To Do | Day 3 | ⚠️ Partial | Symbol folder names unsupported on InfinityFree; will work on cPanel |
| 13 | Explore KL — Places of Worship, What To Eat, Night Life, KL 4 Kids, Sightseeing, Parks | Day 3 | ⚠️ Partial | Most images working; symbol filenames fail on InfinityFree only |
| 14 | Beyond KL — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 15 | Medical Tourism — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 16 | Shop Like Locals — All Sections | Day 3 | ✅ Pass | 1 image wrong format; will resolve on cPanel |
| 17 | Spa Time — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 18 | Place to Stay — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 19 | Merchandise Admin Panel | Day 3 | 🔄 In Progress | Panel created; end-to-end test pending |
| 20 | Admin Security — Session Timeout | Day 2 | ✅ Verified | Server destroys session after 30 min inactivity |
| 21 | Admin Security — JS Inactivity Timer | Day 2 | ✅ Verified | Auto-redirects after 30 min of no activity |
| 22 | Admin Security — Sensitive Page Gating | Day 2 | ✅ Verified | export.php and vapid.php gated behind login |

### Overall Open Items

| # | Item | Priority | Notes |
|---|------|----------|-------|
| 1 | Landing page video pixelation | Medium | Consider embedding from external host (YouTube / Cloudflare Stream) |
| 2 | Re-upload assets + re-publish banners on cPanel | High | Required step during final production push |
| 3 | Merchandise admin panel — end-to-end test | High | New panel created; needs full dashboard testing |
| 4 | Symbol-named image files (Explore KL sections) | Low | InfinityFree limitation only; will resolve on cPanel — no code fix needed |

---

---

## Day 4 — 24 June 2026

### Summary

| # | Feature / Area | Status | Action Taken |
|---|----------------|--------|--------------|
| 1 | Merchandise — Category Listing (Admin) | Pass | Created new category to test listing; works correctly |
| 2 | Merchandise — Product Publication (Admin) | Pass | Image, name, link, and price all publishing correctly |
| 3 | Event — Admin Dashboard Layout | Improved | Layout restructured for easier, more organised event entry |
| 4 | Event — Display on Website | Improved | Changed display method; filtering effect added in admin dashboard |
| 5 | Event — Monthly View in Admin | Improved | Events now shown month by month; dashboard cleaner and more readable |
| 6 | Ebook — Admin Dashboard Layout | Fixed & Tested | Full redesign; add, edit, and delete all tested and working correctly |
| 7 | Ebook — Add Category (Admin) | Fixed | New add-category section created; all category management now done within admin dashboard, no cPanel needed |

---

### Findings

#### 1. Merchandise — Category Listing (Admin) — Pass
**What was tested:** Created a new category from the admin dashboard to verify the category listing works end-to-end.
**Result:** Category listing works correctly. No issues found.

---

#### 2. Merchandise — Product Publication (Admin) — Pass
**What was tested:** Published a product and verified all fields — image, name, link, and price.
**Result:** All fields publishing and displaying correctly. No issues found.

---

#### 3. Event — Admin Dashboard Layout — Improved
**Issue:** Previous event entry layout was disorganised and harder to use.
**Fix:** Adjusted the admin dashboard layout to make event entry easier and more structured. (`admin/edit-event.php`)

---

#### 4. Event — Display on Website — Improved
**Change:** Altered how events are displayed on the public-facing page. A filtering effect has been added within the admin dashboard to give better control over what is shown.

---

#### 5. Event — Monthly View in Admin — Improved
**Change:** Events in the admin dashboard are now grouped and displayed month by month, making the dashboard cleaner and easier to manage.

---

#### 6. Ebook — Admin Dashboard Layout — Fixed & Tested
**Issue:** Previous ebook admin layout did not support full content management from within the dashboard.
**Fix:** Completely redesigned the admin dashboard layout for ebooks. Add, edit, and delete functions were all tested and confirmed working correctly.

---

#### 7. Ebook — Add Category (Admin) — Fixed
**Issue:** Adding ebook categories previously required direct cPanel access.
**Fix:** Created a dedicated add-category section inside the admin dashboard. All category management can now be done entirely within the admin panel without needing cPanel.

---

### Day 4 Open Items

| # | Item | Priority | Notes |
|---|------|----------|-------|
| 1 | Landing page video pixelation | Medium | Consider embedding from external host (YouTube / Cloudflare Stream) |
| 2 | Re-upload assets + re-publish banners on cPanel | High | Required step during final production push |
| 3 | Symbol-named image files (Explore KL sections) | Low | InfinityFree limitation only; will resolve on cPanel — no code fix needed |

---

---

## Testing Summary — All Days

| # | Feature / Area | Day Tested | Status | Remarks |
|---|----------------|------------|--------|---------|
| 1 | Landing Page Hero Video | Day 1 | ⚠️ Fixed (caveat) | MP4 → WebP; still pixelated — long-term fix needed |
| 2 | Banner (live display) | Day 1 → Day 2 | ✅ Fixed | Code structure corrected; assets re-uploaded via FileZilla |
| 3 | Post New Banner (admin) | Day 2 | ✅ Fixed | Must publish new banner first; re-upload required on cPanel |
| 4 | KL @ A Glance — Scrolling | Day 1 | ✅ Fixed | Code adjusted; SQL added; images uploaded manually |
| 5 | Exclusive Recommendations | Day 1 | ✅ Pass | Links, pages, and animations all working |
| 6 | Getting Around KL — Page Load Speed | Day 3 | ✅ Fixed | Cache size reduced via code |
| 7 | Getting Around KL — Option Links | Day 2 & 3 | ✅ Pass | All connections and links working |
| 8 | Getting Around KL — E-Hailing Services | Day 3 | ✅ Fixed | Code adjusted; unnecessary info removed |
| 9 | Travel Tips | Day 2 | ✅ Pass | All external links working |
| 10 | Blog | Day 2 | ✅ Pass | Connected to Blogger; latest posts displaying correctly |
| 11 | Explore KL — Historical Sites | Day 3 | ✅ Pass | Images and map links all working |
| 12 | Explore KL — What To Do | Day 3 | ⚠️ Partial | Symbol folder names unsupported on InfinityFree; will work on cPanel |
| 13 | Explore KL — Places of Worship, What To Eat, Night Life, KL 4 Kids, Sightseeing, Parks | Day 3 | ⚠️ Partial | Most images working; symbol filenames fail on InfinityFree only |
| 14 | Beyond KL — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 15 | Medical Tourism — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 16 | Shop Like Locals — All Sections | Day 3 | ✅ Pass | 1 image wrong format; will resolve on cPanel |
| 17 | Spa Time — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 18 | Place to Stay — All Sections | Day 3 | ✅ Pass | Links and images all working |
| 19 | Merchandise — Category Listing (Admin) | Day 4 | ✅ Pass | Category creation and listing working correctly |
| 20 | Merchandise — Product Publication (Admin) | Day 4 | ✅ Pass | Image, name, link, and price all publishing correctly |
| 21 | Merchandise Admin Panel (initial) | Day 3 → Day 4 | ✅ Complete | Panel created Day 3; fully tested Day 4 |
| 22 | Event — Admin Dashboard Layout | Day 4 | ✅ Improved | Restructured for easier and more organised entry |
| 23 | Event — Display & Filtering on Website | Day 4 | ✅ Improved | Filtering effect added; display method updated |
| 24 | Event — Monthly View in Admin | Day 4 | ✅ Improved | Events grouped by month; dashboard cleaner |
| 25 | Ebook — Admin Dashboard Layout | Day 4 | ✅ Fixed & Tested | Full redesign; add, edit, delete all working |
| 26 | Ebook — Add Category (Admin) | Day 4 | ✅ Fixed | Category management now fully in admin dashboard; no cPanel needed |
| 27 | Admin Security — Session Timeout | Day 2 | ✅ Verified | Server destroys session after 30 min inactivity |
| 28 | Admin Security — JS Inactivity Timer | Day 2 | ✅ Verified | Auto-redirects after 30 min of no activity |
| 29 | Admin Security — Sensitive Page Gating | Day 2 | ✅ Verified | export.php and vapid.php gated behind login |

### Overall Open Items

| # | Item | Priority | Notes |
|---|------|----------|-------|
| 1 | Landing page video pixelation | Medium | Consider embedding from external host (YouTube / Cloudflare Stream) |
| 2 | Re-upload assets + re-publish banners on cPanel | High | Required step during final production push |
| 3 | Symbol-named image files (Explore KL sections) | Low | InfinityFree limitation only; will resolve on cPanel — no code fix needed |
