<?php include('admin/functions.php');
$query = "SELECT tile1_title1, tile1_title2, tile1_title3 FROM indexpage";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result);
$tile1_title1 = $row['tile1_title1'];
$tile1_title2 = $row['tile1_title2'];
$tile1_title3 = $row['tile1_title3'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Travel Tips</title>

  <link rel="canonical" href="https://kltheguide.com.my/travel-tips.php/" />
  <meta name="description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia. Communication, safety, finance, packing, and logistics advice for tourists.">
  <meta name="keywords" content="KL travel tips, Kuala Lumpur tips, Malaysia travel advice, KL tourist guide, KL safety, KL currency, KL SIM card, KL accommodation">

  <!-- Open Graph -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/travel-tips.php" />
  <meta property="og:title" content="KL The Guide - Travel Tips" />
  <meta property="og:description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/travel-tips.php" />
  <meta property="twitter:title" content="KL The Guide - Travel Tips" />
  <meta property="twitter:description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* ===== Tabs Navigation Bar ===== */
    .klag-tab-bar {
      position: fixed;
      top: 70px;
      left: 0;
      right: 0;
      height: 120px;
      z-index: 150;
      display: flex;
    }

    .klag-tab {
      flex: 1;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      text-decoration: none;
      border-bottom: 4px solid transparent;
      transition: border-color .25s;
    }

    .klag-tab + .klag-tab { border-left: 1px solid rgba(255,255,255,.12); }

    .klag-tab.is-active { border-bottom-color: #0ea2bd; }

    .klag-tab__bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      filter: brightness(0.48);
      transition: filter .25s;
    }

    .klag-tab:hover .klag-tab__bg,
    .klag-tab.is-active .klag-tab__bg { filter: brightness(0.62); }

    .klag-tab__label {
      position: relative;
      z-index: 1;
      color: #fff;
      font-size: clamp(12px, 1.8vw, 17px);
      font-weight: 700;
      letter-spacing: 0.02em;
      text-align: center;
      padding: 0 12px;
      text-shadow: 0 2px 10px rgba(0,0,0,.7);
    }

    @media (max-width: 768px) {
      .klag-tab-bar { height: 90px; }
      .klag-tab__label { font-size: 12px; }
    }

    @media (max-width: 480px) {
      .klag-tab-bar { height: 76px; }
      .klag-tab__label { font-size: 10.5px; letter-spacing: 0; }
    }

    /* ===== Travel Tips FAQ ===== */
    #tt-section {
      position: relative;
      min-height: 100vh;
      padding: 200px 0 70px;
      overflow: hidden;
    }

    @media (max-width: 768px) {
      #tt-section { padding-top: 170px; }
    }

    @media (max-width: 480px) {
      #tt-section { padding-top: 156px; }
    }

    #tt-section .tt-bg {
      position: absolute;
      inset: 0;
      background-image: url('asset-backups/KLGlanceBackground.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      filter: brightness(0.7) saturate(1.05);
      z-index: 0;
    }

    #tt-section .tt-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(18, 8, 38, 0.28) 0%, rgba(8, 18, 48, 0.22) 100%);
      z-index: 1;
    }

    .tt-wrap {
      position: relative;
      z-index: 2;
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .tt-heading {
      text-align: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 2.4rem;
      font-weight: 700;
      margin-bottom: 42px;
      letter-spacing: 0.5px;
    }

    .tt-layout {
      display: flex;
      gap: 24px;
      align-items: flex-start;
    }

    /* ===== Left: Section Tabs ===== */
    .tt-tabs {
      flex: 0 0 265px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .tt-tab {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.13);
      color: rgba(255, 255, 255, 0.8);
      padding: 15px 18px;
      border-radius: 10px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      font-size: 0.87rem;
      font-weight: 500;
      line-height: 1.45;
      text-align: center;
      transition: background 0.25s, color 0.25s, border-color 0.25s, box-shadow 0.25s;
      outline: none;
    }

    .tt-tab:hover {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border-color: rgba(255, 255, 255, 0.22);
    }

    .tt-tab.active {
      background: linear-gradient(135deg, #7b2fbe 0%, #5c1d96 100%);
      border-color: #9b50de;
      color: #fff;
      font-weight: 600;
      box-shadow: 0 6px 22px rgba(123, 47, 190, 0.45);
    }

    /* ===== Right: Accordion Panel ===== */
    .tt-panel {
      flex: 1;
      background: rgba(0, 0, 0, 0.42);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      padding: 30px 36px;
      min-height: 440px;
    }

    .tt-content {
      display: none;
      animation: ttFade 0.3s ease;
    }

    .tt-content.active {
      display: block;
    }

    @keyframes ttFade {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== Accordion Items ===== */
    .tt-acc {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tt-acc:last-child {
      border-bottom: none;
    }

    .tt-acc-hd {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
      cursor: pointer;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      user-select: none;
      transition: color 0.2s;
    }

    .tt-acc-hd:hover {
      color: #c792ff;
    }

    .tt-acc-icon {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      border: 1.5px solid rgba(255, 255, 255, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-left: 14px;
      transition: transform 0.35s ease, border-color 0.25s;
    }

    .tt-acc-icon svg {
      width: 12px;
      height: 12px;
      fill: none;
      stroke: rgba(255, 255, 255, 0.7);
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
      transition: stroke 0.2s;
    }

    .tt-acc.open .tt-acc-icon {
      transform: rotate(180deg);
      border-color: #9b50de;
    }

    .tt-acc.open .tt-acc-icon svg {
      stroke: #c792ff;
    }

    .tt-acc-bd {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tt-acc.open .tt-acc-bd {
      max-height: 900px;
    }

    .tt-acc-inner {
      padding-bottom: 24px;
    }

    .tt-q {
      color: rgba(255, 255, 255, 0.92);
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      font-weight: 500;
      font-style: italic;
      margin-bottom: 12px;
    }

    .tt-a {
      color: rgba(255, 255, 255, 0.72);
      font-family: 'Open Sans', sans-serif;
      font-size: 0.875rem;
      line-height: 1.82;
      margin-bottom: 16px;
    }

    .tt-cta-row {
      display: flex;
      justify-content: flex-end;
    }

    .tt-cta {
      background: rgba(255, 255, 255, 0.92);
      color: #2d1b4e;
      padding: 8px 20px;
      border-radius: 6px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.8rem;
      font-weight: 700;
      border: none;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: background 0.2s, transform 0.15s;
      display: inline-block;
      text-decoration: none;
    }

    a.tt-cta:hover { color: #2d1b4e; text-decoration: none; }

    .tt-legal-list {
      color: rgba(255, 255, 255, 0.78);
      font-family: 'Open Sans', sans-serif;
      font-size: 0.875rem;
      line-height: 1.75;
      margin: 0 0 16px 0;
      padding-left: 18px;
    }
    .tt-legal-list li { margin-bottom: 8px; }
    .tt-legal-list strong { color: #c792ff; font-weight: 600; }
    .tt-legal-sub {
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      margin: 14px 0 10px;
    }

    .tt-cta:hover {
      background: #fff;
      transform: translateY(-1px);
    }

    .tt-cta:disabled {
      opacity: 0.65;
      cursor: wait;
      transform: none;
    }

    /* ===== Responsive ===== */
    @media (max-width: 900px) {
      .tt-layout {
        flex-direction: column;
      }

      .tt-tabs {
        flex: none;
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
      }

      .tt-tab {
        flex: 1 1 calc(50% - 4px);
        font-size: 0.8rem;
        padding: 12px 10px;
      }

      .tt-panel {
        padding: 24px 20px;
        width: 100%;
      }

      .tt-heading {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .tt-tab {
        flex: 1 1 100%;
      }

      .tt-heading {
        font-size: 1.65rem;
      }
    }
  </style>

</head>

<body class="has-video-hero">

  <?php include 'nav.php'; ?>

  <!-- ── Thumbnail tab bar ──────────────────────────────────── -->
  <div class="klag-tab-bar" role="navigation" aria-label="KL Highlights sections">

    <a class="klag-tab" href="kl-glance.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/kl@aglance.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title1), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab" href="getting-around-kl.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/gettingaroundkl.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title2), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab is-active" href="travel-tips.php" aria-current="page">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/traveltips.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?>
      </span>
    </a>

  </div>

  <main id="travel-tips-page">

    <!-- Travel Tips FAQ -->
    <section id="tt-section">
      <div class="tt-bg"></div>
      <div class="tt-overlay"></div>

      <div class="tt-wrap">
        <h2 class="tt-heading">Travel Tips</h2>

        <div class="tt-layout">

          <!-- LEFT: Section Tabs -->
          <nav class="tt-tabs" aria-label="Travel tip sections">
            <button class="tt-tab active" data-target="tt-a">Section A: Communication &amp; Connectivity</button>
            <button class="tt-tab" data-target="tt-b">Section B: Essential Information</button>
            <button class="tt-tab" data-target="tt-c">Section C: Finance &amp; Documents</button>
            <button class="tt-tab" data-target="tt-d">Section D: Packing &amp; Gear</button>
            <button class="tt-tab" data-target="tt-e">Section E: Logistics</button>
          </nav>

          <!-- RIGHT: Accordion Panel -->
          <div class="tt-panel">

            <!-- ── Section A: Communication & Connectivity ── -->
            <div class="tt-content active" id="tt-a">

              <div class="tt-acc open">
                <div class="tt-acc-hd">
                  Mobile Services
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Where is the most convenient place to purchase a local 5G prepaid SIM card upon arrival?"</p>
                    <p class="tt-a">You can find dedicated service provider kiosks from Maxis, CelcomDigi, and U Mobile immediately after exiting the international arrival gates at KLIA 1 &amp; KLIA 2. These counters operate 24 hours a day and offer tourist-specific data packages starting from RM15 for 7-day unlimited plans.</p>
                    <div class="tt-cta-row">
                      <button class="tt-cta tt-map-btn" data-map-query="Maxis CelcomDigi U Mobile SIM card shop">
                        <svg width="11" height="14" viewBox="0 0 11 14" fill="currentColor" style="margin-right:5px;vertical-align:-1px" aria-hidden="true"><path d="M5.5 0C3.02 0 1 2.02 1 4.5c0 3.37 4.5 9.5 4.5 9.5S10 7.87 10 4.5C10 2.02 7.98 0 5.5 0zm0 6.25a1.75 1.75 0 1 1 0-3.5 1.75 1.75 0 0 1 0 3.5z"/></svg>Find SIM Shops
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Calling Logistics
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"How do I make affordable international calls back home while in KL?"</p>
                    <p class="tt-a">The easiest method is to use internet-based apps like WhatsApp, Telegram, or FaceTime over your local data plan — these are free and widely used in Malaysia. For traditional calls, purchase a prepaid SIM with an international calling add-on from Maxis or CelcomDigi, offering rates from as low as RM0.09 per minute to most countries.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.nerdwallet.com/article/utilities/international-calling-plans" target="_blank" rel="noopener noreferrer">Compare Call Rates</a></div>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Online Access
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Is free public WiFi widely available across Kuala Lumpur?"</p>
                    <p class="tt-a">Yes! KL offers free WiFi under the 'KL Free WiFi' initiative at over 5,000 hotspots covering LRT/MRT stations, shopping malls, public parks, and major tourist attractions. Simply search for "KL Free WiFi" on your device. For faster, consistent connectivity, a local prepaid data SIM remains the most reliable option for extended stays.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.google.com/maps/search/free+wifi+near+me/" target="_blank" rel="noopener noreferrer">Find WiFi Hotspots</a></div>
                  </div>
                </div>
              </div>

            </div><!-- /tt-a -->

            <!-- ── Section B: Essential Information ── -->
            <div class="tt-content" id="tt-b">

              <div class="tt-acc open">
                <div class="tt-acc-hd">
                  Safety
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Which areas of KL should I be cautious in, and what are the most common tourist scams to avoid?"</p>
                    <p class="tt-a">KL is generally safe for tourists. Exercise extra caution in Chow Kit and parts of Bukit Bintang late at night. The most common scams include taxi overcharging (always use the Grab app instead), gem and jewellery scams near Masjid India, and "friendly strangers" offering unsolicited tours. Keep bags zipped and body-side in crowded areas like Petaling Street and the Central Market.</p>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Environment
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Are there any environmental laws or local practices I should be aware of as a visitor in KL?"</p>
                    <p class="tt-a">Malaysia enforces strict anti-littering laws with fines up to RM500. Smoking is banned in all air-conditioned public spaces, restaurants, bars, and within 3 metres of any food premises — penalties can reach RM10,000. Many malls and attractions have gone plastic-bag-free, so carrying a reusable tote is strongly recommended. Haze season (June–October) can occasionally affect air quality; check the daily API index before outdoor activities.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.doe.gov.my/" target="_blank" rel="noopener noreferrer">Read Local Rules</a></div>
                  </div>
                </div>
              </div>

            </div><!-- /tt-b -->

            <!-- ── Section C: Finance & Documents ── -->
            <div class="tt-content" id="tt-c">

              <div class="tt-acc open">
                <div class="tt-acc-hd">
                  Legal
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"What are the key laws tourists most commonly and unknowingly break in Malaysia?"</p>
                    <p class="tt-a">Drug possession carries a mandatory death penalty in Malaysia — never carry any controlled substances across the border or within the country. Public displays of affection can result in fines. Jaywalking is a ticketable offence. When visiting mosques or Hindu temples, dress modestly by covering shoulders and knees; many mosques such as Masjid Negara provide loaner robes free of charge at the entrance.</p>
                    <p class="tt-legal-sub">Key Local Laws and Cultural Norms</p>
                    <ul class="tt-legal-list">
                      <li><strong>Drugs:</strong> Malaysia has mandatory death penalties for drug trafficking and strict penalties for possession.</li>
                      <li><strong>Public Behavior:</strong> Public displays of affection (PDA) are frowned upon; public nudity or indecent clothing can lead to arrest.</li>
                      <li><strong>Alcohol/Smoking:</strong> Alcohol is not served everywhere, especially in rural areas. Smoking is prohibited in most public spaces.</li>
                      <li><strong>Dress Code:</strong> Dress conservatively, especially at religious sites. Covering shoulders and knees is advised.</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Currency
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Where is the best place to exchange foreign currency for the most competitive rates in KL?"</p>
                    <p class="tt-a">Licensed money changers inside major malls — such as Suria KLCC, Pavilion KL, and Mid Valley Megamall — consistently offer rates far superior to airport exchange counters or bank branches. Avoid hotel exchange desks. ATMs on the Visa or Mastercard network are widely available citywide and offer competitive mid-market rates. The Malaysian Ringgit (MYR / RM) is the sole legal tender.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.google.com/maps/search/money+changer+near+me/" target="_blank" rel="noopener noreferrer">Find Money Changers</a></div>
                  </div>
                </div>
              </div>

            </div><!-- /tt-c -->

            <!-- ── Section D: Packing & Gear ── -->
            <div class="tt-content" id="tt-d">

              <div class="tt-acc open">
                <div class="tt-acc-hd">
                  Apparel
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"What should I pack to wear in KL given its multicultural environment and tropical climate?"</p>
                    <p class="tt-a">Opt for lightweight, breathable fabrics like cotton or linen to cope with constant heat and humidity. Casual wear is perfectly acceptable in malls and tourist areas. Pack a light shawl or sarong for mosque and temple visits — shoulders and knees must be covered. Many mosques such as Masjid Negara provide loaner robes at the entrance at no charge. Comfortable closed-toe walking shoes are a must for KL's hilly terrain and tiled surfaces.</p>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Weather Protection
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"How do I best prepare for KL's intense sun and sudden heavy rainstorms?"</p>
                    <p class="tt-a">Always carry a compact umbrella or light rain poncho — heavy afternoon downpours between 3pm and 5pm are extremely common, particularly from October to March during the Northeast Monsoon season. Apply SPF50+ sunscreen daily, as UV Index levels regularly reach "Extreme" (11+). Stay well-hydrated; the combination of tropical heat and high humidity can cause fatigue and dehydration quickly.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.google.com/search?q=weather+in+kuala+lumpur" target="_blank" rel="noopener noreferrer">Check KL Weather</a></div>
                  </div>
                </div>
              </div>

              <div class="tt-acc">
                <div class="tt-acc-hd">
                  Health
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Do I need any vaccinations or special medications before travelling to Kuala Lumpur?"</p>
                    <p class="tt-a">No mandatory vaccinations are required to enter Malaysia for most nationalities. However, the WHO recommends being current on Hepatitis A &amp; B and Typhoid vaccines for travel to KL. Dengue fever is present year-round, so apply DEET-based mosquito repellent especially in parks and forested areas like FRIM or Bukit Nanas. Tap water is treated but bottled or filtered water is widely preferred by both residents and visitors.</p>
                  </div>
                </div>
              </div>

            </div><!-- /tt-d -->

            <!-- ── Section E: Logistics ── -->
            <div class="tt-content" id="tt-e">

              <div class="tt-acc open">
                <div class="tt-acc-hd">
                  Accommodation
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <p class="tt-q">"Which neighbourhood in KL is the best base for first-time visitors who plan to rely on public transport?"</p>
                    <p class="tt-a">The Golden Triangle area — spanning Bukit Bintang, KLCC, and Chow Kit — is ideal for first-time visitors. It provides direct access to multiple LRT, MRT, and Monorail lines, is walkable to iconic attractions including the Petronas Twin Towers, Berjaya Times Square, and Pavilion KL, and offers accommodation at every price point from budget guesthouses and hostels to international 5-star hotels.</p>
                    <div class="tt-cta-row"><a class="tt-cta" href="https://www.trivago.com.my/" target="_blank" rel="noopener noreferrer">Browse Hotels</a></div>
                  </div>
                </div>
              </div>

            </div><!-- /tt-e -->

          </div><!-- /tt-panel -->
        </div><!-- /tt-layout -->
      </div><!-- /tt-wrap -->
    </section>

  </main><!-- /#travel-tips-page -->

  <?php include 'footer.php'; ?>

  <script>
    (function () {
      'use strict';

      // Tab switching
      var tabs = document.querySelectorAll('.tt-tab');
      var contents = document.querySelectorAll('.tt-content');

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (t) { t.classList.remove('active'); });
          contents.forEach(function (c) { c.classList.remove('active'); });
          tab.classList.add('active');
          var target = document.getElementById(tab.dataset.target);
          if (target) { target.classList.add('active'); }
        });
      });

      // Accordion toggle
      document.querySelectorAll('.tt-acc-hd').forEach(function (hd) {
        hd.addEventListener('click', function () {
          hd.parentElement.classList.toggle('open');
        });
      });

      // Map CTA buttons — open Google Maps near user, fallback to KL city centre
      document.querySelectorAll('.tt-map-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var query   = encodeURIComponent(btn.dataset.mapQuery || 'shop');
          var fallLat = 3.1390, fallLng = 101.6869, zoom = 15;

          function openMap(lat, lng) {
            window.open(
              'https://www.google.com/maps/search/' + query + '/@' + lat + ',' + lng + ',' + zoom + 'z',
              '_blank', 'noopener,noreferrer'
            );
          }

          if (!navigator.geolocation) { openMap(fallLat, fallLng); return; }

          var origHTML = btn.innerHTML;
          btn.textContent = 'Locating…';
          btn.disabled = true;

          navigator.geolocation.getCurrentPosition(
            function (pos) {
              btn.innerHTML  = origHTML;
              btn.disabled   = false;
              openMap(pos.coords.latitude, pos.coords.longitude);
            },
            function () {
              btn.innerHTML  = origHTML;
              btn.disabled   = false;
              openMap(fallLat, fallLng);
            },
            { timeout: 8000, maximumAge: 60000 }
          );
        });
      });
    }());
  </script>

</body>
</html>
