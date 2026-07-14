# KLTG App — Backend API Reference (for the Flutter app)

> **Read this first.** The earlier `DYNAMIC_CONTENT_HANDOFF.md` is outdated. It asks you
> to *build* new endpoints (`fetch_spa`, `fetch_shop`, …). **Do not build those — they
> already exist** under different action names. The backend is already dynamic: when the
> website admin edits content, it writes to the same MySQL tables these endpoints read
> from, so the app gets fresh content automatically with no app release.
>
> **Your job is app-side only:** replace the hardcoded Dart lists with HTTP calls to the
> endpoints documented below.

---

## How the API works

One endpoint, action selected by a POST body key (same as the existing `voucher.dart`):

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

const String kApi = 'https://www.kltheguide.com.my/admin/functions.php';

Future<List<dynamic>> fetchList(String action, {String? category}) async {
  final body = {action: 'true'};
  if (category != null) body['category'] = category;   // only some actions need this
  final res = await http.post(Uri.parse(kApi), body: body);
  if (res.statusCode != 200) throw Exception('API $action failed: ${res.statusCode}');
  return jsonDecode(res.body) as List<dynamic>;        // always a JSON array
}
```

- The response is **always a JSON array of objects**.
- `image` fields come back as **full absolute URLs** already — use directly in `Image.network(...)`.
- All actions are **POST**. A GET returns nothing.

### Rendering pattern (use for every screen)

```dart
FutureBuilder<List<dynamic>>(
  future: fetchList('appSpa'),
  builder: (context, snap) {
    if (snap.hasError) return Center(child: Text('${snap.error}'));
    if (!snap.hasData) return const Center(child: CircularProgressIndicator());
    final items = snap.data!;
    return ListView.builder(
      itemCount: items.length,
      itemBuilder: (_, i) {
        final it = items[i];
        return ListTile(
          leading: Image.network(it['image'], width: 64, fit: BoxFit.cover),
          title: Text(it['title'] ?? ''),
          subtitle: Text(it['location'] ?? ''),
          // it['content'], it['hours'], it['locationurl'], it['phone'], it['website']
        );
      },
    );
  },
)
```

---

## Endpoint catalog

For each app screen: the action name(s) to POST, whether a `category` param is required,
and the JSON fields each object returns.

### `lib/spa.dart`
| Action | category? | Returns |
|---|---|---|
| `appSpa` | no | title, content, image, location, locationurl, hours, phone* |

### `lib/shop.dart`
| Action | category? | Returns |
|---|---|---|
| `appShop` | no | title, content, image, location, locationurl, hours, phone*, website |

### `lib/stay.dart` (Accommodations — call all four, one per tab)
| Action | category? | Section | Returns |
|---|---|---|---|
| `appStay_top` | no | Top picks | title, content, image, location, locationurl, hours, phone* |
| `appStay_h`   | no | Hotels | same |
| `appStay_bh`  | no | Budget hotels | same |
| `appStay_bks` | no | Backpacker hostels | same |

### `lib/medicaltourism.dart` (call all five, one per category tab)
| Action | category? | Section | Returns |
|---|---|---|---|
| `appMedicalT_hc`  | no | Healthcare / hospitals | title, content, image, location, locationurl, hours, phone* |
| `appMedicalT_dtl` | no | Dental | same |
| `appMedicalT_der` | no | Dermatology | same |
| `appMedicalT_oph` | no | Ophthalmology | same |
| `appMedicalT_ps`  | no | Plastic surgery | same |

### `lib/beyondkl.dart` (call all five, one per category tab)
| Action | category? | Section | Returns |
|---|---|---|---|
| `appBeyondKL_i`  | no | Islands | title, content, image, location |
| `appBeyondKL_hs` | no | Hill stations | same |
| `appBeyondKL_w`  | no | Waterfalls | same |
| `appBeyondKL_h`  | no | Hiking | same |
| `appBeyondKL_es` | no | Extreme sports | same |

> Note: Beyond KL objects only return **title, content, image, location** (no hours/phone/website).

### `lib/explorekl.dart` (the big one — many actions)
| Action | category? | Section | Returns |
|---|---|---|---|
| `appExploreKL_WTD`    | no  | What to do | title, content, image |
| `appExploreKL_HS`     | no  | Historical sites | title, content, image, location, locationurl, hours, phone* |
| `appExploreKL_P`      | no  | Parks | title, content, content2, image, location, locationurl, hours, phone, website |
| `appExploreKL_KL4K`   | no  | KL for kids / family | title, content, content2, image, location, locationurl, hours, phone, website |
| `appExploreKL_PWOR`   | **yes** | Places of worship | title, content, image, location, locationurl, hours, website, phone* |
| `appExploreKL_WTE_SF` | no  | Where to eat – street food | title, content, image, location, locationurl |
| `appExploreKL_WTE_C`  | no  | Where to eat – cafes | title, content**, image, location, locationurl, hours, website, phone* |
| `appExploreKL_WTE_R`  | no  | Where to eat – restaurants | title, content**, image, location, locationurl, hours, website, phone* |
| `appExploreKL_NL`     | **yes** | Nightlife | title, content, image, location, locationurl, hours, website, phone* |
| `appExploreKL_SS`     | **yes** | Sightseeing | title, content, content2, image, location, locationurl, hours, website, phone* |

The three actions marked **category required** need a `category` value that matches the
value stored in the website admin for that section. Confirm the exact strings with the
website owner (they're the values in the `*_category` DB column), e.g. PWOR ≈ mosque /
temple / church.

---

## ⚠️ Known backend data issues (website side will fix; design around these)

These are bugs in `functions.php` that make some advertised fields come back **empty**:

1. **`phone` is empty on the actions marked `phone*`** above — the handler outputs a
   `phone` key but the SQL never selects the phone column. Until the website side fixes
   it, treat `phone` as possibly-empty on those actions (the ones NOT marked are fine).
2. **`content` is always empty on `appExploreKL_WTE_C` and `appExploreKL_WTE_R`** (marked
   `content**`) — the handler hardcodes `content => ''`. Don't rely on description text
   for cafes/restaurants yet.
3. `website` is **not returned at all** by the Stay and Medical actions — don't show a
   website field on those screens.

> The website developer is fixing #1 and #2 in `functions.php`. None of these block you —
> just guard every field with `?? ''` / null checks (the JSON key may be missing or blank).

---

## These app files already fetch dynamically — leave them as-is
- `lib/blog_page.dart` — Blogger API
- `lib/ebook_page.dart` — `functions.php` action `appEbook` (needs `category`)
- `lib/voucher.dart` — `functions.php` action `fetch_vouchers`
