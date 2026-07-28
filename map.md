# Places with no saved map coordinate

_Generated 28 July 2026 from the live content in `kltheguidecom_bluedale2_kltg`._

## Read this first — what the list actually means

**None of these places are missing from Google Maps.** They all exist there, and the
"View on Map" button on the website still works for every one of them.

What is missing is a **saved exact coordinate** (`lat,lng`) on the row. Without one, the
button falls back to asking Google to *search for the name*. For a famous landmark that
guess is fine. For a name like "Antipodean", "The Lemon Tree" or "Dr Ko Clinic" — chains,
common words, several branches — Google can land the pin on the wrong branch, the wrong
town, or somewhere unrelated.

| | Places |
|---|---|
| **Worth fixing** — a map button shows, but the pin is a guess | **51** |
| No map button appears at all (no address on the row) — nothing to do | 15 |

Everything else on the site — 258 places — now pins exactly.

Every public page only shows the map button when the row has an **address**, which is why
the second group needs nothing: those rows show no button to be wrong about.

## Why the 51 above could not be filled in automatically

The other coordinates were obtained by following the Google Maps link already saved on each
row and reading the map marker out of it. That only works when the saved link really points
at a pin. These rows failed for the reasons below.

| Reason | Places |
|---|---|
| g.page share link — carries no pin | 28 |
| The link field holds text, not a URL | 15 |
| New "share.google" link — Google blocks reading it | 5 |
| Points back at our own website | 1 |
| No link saved on the row | 1 |
| Not a Google Maps link | 1 |

## How to fix one — about 30 seconds each

1. Find the place in **Google Maps** in your browser.
2. **Right-click the exact spot.** The first item in the menu is a pair of numbers like
   `3.158021, 101.711667`. Click it to copy.
3. In the admin panel open the editor named below, find the row, click its **pen icon**.
4. Paste into the **Map Coordinates (lat,lng)** field and press **Save Changes**.

You can also paste the **whole Google Maps web address** into that field instead — the site
pulls the coordinates out of it for you.

There is no rush and no risk: a row left blank keeps behaving exactly as it does today, so
these can be done a few at a time.

---

# Group 1 — worth fixing (51)

These show a "View on Map" button whose pin is currently a guess.

## explorekl.php — 24 places

### Parks (1)

Admin: **edit-explorekl.php** → table *"Parks"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | Perdana Botanical Garden | Jalan Kebun Bunga, Tasik Perdana, Kuala Lumpur | g.page share link — carries no pin |

### What To Eat - Street Food (2)

Admin: **edit-explorekl.php** → table *"What To Eat Street Food"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | Petaling Street | Jalan Petaling, City Centre, 50000 Kuala Lumpur, Wilayah Pe… | g.page share link — carries no pin |
| 2 | Tapak Urban Street Dining | Persiaran Hampshire, Off, Jalan Ampang, 50450 Kuala Lumpur,… | Points back at our own website |

### What To Eat - Cafes (5)

Admin: **edit-explorekl.php** → table *"What To Eat Cafes"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | Monster. A Garden Cafe | No. 54, 1st Floor, Jalan Sultan, City Centre, 50000 Kuala L… | New "share.google" link — Google blocks reading it |
| 2 | White Land Cafe | 1-15, CENTRIO, 1, Jalan Pantai Murni, Pantai Hillpark, 5920… | New "share.google" link — Google blocks reading it |
| 3 | LostxFound Cafe | 37B, Medan Imbi, Pudu, 55100 Kuala Lumpur, Wilayah Persekut… | New "share.google" link — Google blocks reading it |
| 4 | AOOO Melbourne Cafe | 182-2, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur, … | New "share.google" link — Google blocks reading it |
| 5 | Antipodean | Menara Tan & Tan, 207, Jln Tun Razak, Kuala Lumpur, 50400 K… | New "share.google" link — Google blocks reading it |

### What To Eat - Restaurants (16)

Admin: **edit-explorekl.php** → table *"What To Eat Restaurant"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | Congkak | 24, Jalan Beremi, Off, Jalan Sultan Ismail, Bukit Bintang, … | g.page share link — carries no pin |
| 2 | Shanghai Restaurant | JW Marriott Kuala Lumpur, Level One, 183 Jalan Bukit Bintan… | g.page share link — carries no pin |
| 3 | Opium KL | 50, Changkat Bukit Bintang, 50200 Kuala Lumpur | g.page share link — carries no pin |
| 4 | El Cerdo | 43 & 45, Changkat Bukit Bintang, 50200 Kuala Lumpur | g.page share link — carries no pin |
| 5 | Namaste India Hartamas | No 6-G, Jalan 31/70a, Desa Sri Hartamas, Kuala Lumpur, Mala… | g.page share link — carries no pin |
| 6 | Gajaa at 8 | No.8 Lorong Maarof, Bangsar Park, 59000 Kuala Lumpur, Malaysia | g.page share link — carries no pin |
| 7 | Tandoor Grill | Level 3A, Berjaya Central Park, Jalan Ampang, 50250 Kuala L… | No link saved on the row |
| 8 | Woodlands | 55, Leboh Ampang, City Centre, 50100 Kuala Lumpur | g.page share link — carries no pin |
| 9 | Tarbush Restaurant | 138, Bukit Bintang Street, Bukit Bintang, 55100 Kuala Lumpur | g.page share link — carries no pin |
| 10 | Halab KL | 35, Jalan Berangan, Bukit Bintang, 50200 Kuala Lumpur | g.page share link — carries no pin |
| 11 | La Boca Latino Bar | Lot C3 . 10 . 03, 168, Bukit Bintang Street, Bukit Bintang,… | g.page share link — carries no pin |
| 12 | Sala Hartamas | 2 A-G-3A Galeria Hartamas, No 21, Jalan 26a/70a, Desa Sri H… | g.page share link — carries no pin |
| 13 | Burger on 16 | 16, Lorong 1/77a, Imbi, 55100 Kuala Lumpur | g.page share link — carries no pin |
| 14 | Giegit’s Burger | No 10, Jalan Solaris 3, Mont Kiara, 50480 Kuala Lumpur | Not a Google Maps link |
| 15 | Dining In The Dark KL | 50A, Changkat Bukit Bintang, 50200 Kuala Lumpur | g.page share link — carries no pin |
| 16 | Cantaloupe at Troika Sky Dining | Level 23A Tower B, The Troika, 19, Persiaran KLCC, 50450 Ku… | g.page share link — carries no pin |

## medical-tourism.php — 11 places

### Healthcare (1)

Admin: **edit-medical-tourism.php** → table *"Healthcare"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | kltheguide.com.my | kltheguide.com.my | The link field holds text, not a URL |

### Dental (6)

Admin: **edit-medical-tourism.php** → table *"Dental"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | DentalPro (Kuala Lumpur, Malaysia) | 12, Jalan Sri Semantan 1, Damansara Heights, 50490 Kuala Lu… | The link field holds text, not a URL |
| 2 | Whitesmile Dental Clinic | 07-51 Berjaya Times Square, 55100 Kuala Lumpur | The link field holds text, not a URL |
| 3 | Mr Dentiste Clinic | 16A, Jalan Awan Hijau, Taman Overseas Union, 58200 Kuala Lu… | The link field holds text, not a URL |
| 4 | Smile Avenue Dental Surgery Publika | A4-UG1-6, Level UG1, Block A4, Jalan Dutamas 1, Solaris Dut… | The link field holds text, not a URL |
| 5 | Lau Dental Clinic & Surgery Sri Petaling | 65-1, Jalan Radin Tengah, Sri Petaling, 57000 Kuala Lumpur | The link field holds text, not a URL |
| 6 | Dutamas Dental Clinic | A3-1-8, Publika, Jalan Dutamas 1, Solaris Dutamas, 50480 Ku… | The link field holds text, not a URL |

### Ophthalmology (4)

Admin: **edit-medical-tourism.php** → table *"Ophthalmology"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | International Specialist Eye Centre | Centrepoint South, Lingkaran Syed Putra, Mid Valley City, 5… | The link field holds text, not a URL |
| 2 | VISTA Eye Specialist | 5, Jalan Kerinchi, Bangsar South, 59200 Kuala Lumpur | The link field holds text, not a URL |
| 3 | Ikonik Eye Specialist Centre | Residensi Park, 9, 10, 11, Persiaran Jalil Utama, Bandar Bu… | The link field holds text, not a URL |
| 4 | KL Eye Specialist Centre | 73, Jalan Metro Perdana Barat 1, Taman Usahawan Kepong, 521… | The link field holds text, not a URL |

## spa.php — 4 places

### Spa (4)

Admin: **edit-spa.php** → table *"Spa"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | kltheguide.com.my | kltheguide.com.my | The link field holds text, not a URL |
| 2 | Ozmosis Health & Day Spa | 16-1, Jalan Telawi 2, Bangsar, 59100 Kuala Lumpur | The link field holds text, not a URL |
| 3 | Uroot Spa | LG1-5, Lower Ground 1, Arcoris, 10, Jalan Kiara, Mont Kiara… | The link field holds text, not a URL |
| 4 | Urban Retreat Spa KL | No 1, Lot 15, 2nd Floor, 1 Mont Kiara, Jalan Mont Kiara, Wi… | The link field holds text, not a URL |

## accommodation.php — 12 places

### Hotels (5)

Admin: **edit-accomodation.php** → table *"Hotels"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | Furama Hotel, Bukit Bintang | 136, Jalan Changkat Thambi Dollah, Bukit Bintang, 55100 Kua… | g.page share link — carries no pin |
| 2 | Mandarin Oriental, KL | Kuala Lumpur City Centre, 50088 Kuala Lumpur, Federal Terri… | g.page share link — carries no pin |
| 3 | Pavilion Hotel Kuala Lumpur | 170, Bukit Bintang St, 55100 Kuala Lumpur | g.page share link — carries no pin |
| 4 | Westin, Kuala Lumpur | 199, Bukit Bintang Street, Bukit Bintang, 55100 Kuala Lumpu… | g.page share link — carries no pin |
| 5 | Hilton, KL | 3, Jalan Stesen Sentral, Kuala Lumpur Sentral, 50470 Kuala … | g.page share link — carries no pin |

### Budget Hotels (2)

Admin: **edit-accomodation.php** → table *"Budget Hotels"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | 5 Bands Hotel | 66-1 & 66-2 ,Jalan rimbunan raya 1,, Laman Rimbunan, Kepong… | g.page share link — carries no pin |
| 2 | MoMo's Kuala Lumpur | 316, Jalan Tuanku Abdul Rahman, Chow Kit, 50100 Kuala Lumpur | g.page share link — carries no pin |

### Backpackers Lodge (5)

Admin: **edit-accomodation.php** → table *"Backpackers Lodge"*

| # | Place | Address shown on the site | Why it is blank |
|---|---|---|---|
| 1 | PODs The Backpackers Home & Cafe, Kuala Lumpur | G-6, 30, Jalan Thambipillay, Brickfields, 50470 Kuala Lumpur | g.page share link — carries no pin |
| 2 | The Bed KLCC | VORTEX KLCC, 12, Jalan Sultan Ismail, Kuala Lumpur, 50250 K… | g.page share link — carries no pin |
| 3 | Illuminate Boutique Hostel | 9, Jalan Maharajalela, Kampung Attap, 50150 Kuala Lumpur | g.page share link — carries no pin |
| 4 | The Explorers Guesthouse | 128, 130, Jalan Tun H S Lee, City Centre, 50000 Kuala Lumpur | g.page share link — carries no pin |
| 5 | Amethyst Love Guesthouse | 13, Jalan Rembia, Bukit Bintang, 50200 Kuala Lumpur | g.page share link — carries no pin |

---

# Group 2 — no map button shown (15)

These rows have no address, so the website never renders a "View on Map" button for them.
**Nothing needs doing.** They are listed only so the numbers add up.

If you *want* a map button on any of them, fill in **both** the Location (address) field and
the Map Coordinates field — the address is what makes the button appear.

## explorekl.php — 10 rows

### What To Do (6)

Admin: **edit-explorekl.php** → table *"What To Do"*

| # | Row |
|---|---|
| 1 | History, Culture & Divine Vibes Collide |
| 2 | A Food Adventure You Can’t Walk Away From |
| 3 | Nights Filled With Energy, Eats & Endless Fun |
| 4 | Big Smiles, Big Adventures, Zero Boredom |
| 5 | KL’s Creative Side in Full Colour |
| 6 | Let You Breathe, Play & Pose |

### Night Life (4)

Admin: **edit-explorekl.php** → table *"Night Life"*

| # | Row |
|---|---|
| 1 | Changkat, Bukit Bintang |
| 2 | Bangsar |
| 3 | Ampang |
| 4 | Petaling Street |

## accommodation.php — 5 rows

### Top Places To Stay (5)

Admin: **edit-accomodation.php** → table *"Top Places To Stay In KL"*

| # | Row |
|---|---|
| 1 | Bukit Bintang |
| 2 | Kuala Lumpur City Center – KLCC |
| 3 | Bangsar South |
| 4 | Chinatown (Petaling Street) |
| 5 | Brickfields |

---

## Three things worth knowing

**The "What To Do" rows are not places.** They are the six category teaser cards at the top
of Explore KL ("A Food Adventure You Can't Walk Away From", and so on). They have no address
and should not have one — leave them alone.

**"Top Places To Stay" are districts, not hotels.** Bukit Bintang, KLCC, Bangsar South,
Chinatown, Brickfields. If you give those a coordinate, pick a sensible centre — the main
LRT/MRT station or the best-known landmark — rather than trying to be exact. That list also
currently shows each district twice; see `admin/accommodation_dedupe.sql`.

**Two rows are placeholders, not real places.** Medical Tourism → Healthcare and Spa each
contain a row literally titled `kltheguide.com.my`, with that same text as its address.
They are leftover junk from an old import. Do not give them coordinates — delete them from
the admin panel instead (delete works correctly now). They are rows 1 in the Healthcare and
Spa tables of Group 1 above.
