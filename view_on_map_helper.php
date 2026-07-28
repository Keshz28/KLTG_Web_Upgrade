<?php
/**
 * Shared "View on Map" button helper, used by every place section:
 * explorekl / beyondkl / medical-tourism / where-to-shop / spa / accommodation.
 *
 * Renders a button that deep-links to map.php, focusing the location.
 *
 * HOW THE PIN IS RESOLVED — best source first:
 *
 *   1. $coords — an exact "lat,lng". Pass the row's <prefix>_mapcoords column,
 *      which is editable from the admin panel (Map Coordinates field on every
 *      add/edit modal). This is the only source an editor can correct, so it
 *      always wins.
 *   2. The generated lookup files (kltg_mapcoords.php / beyondkl_mapcoords.php),
 *      keyed by exact title. Legacy fallback for rows whose coordinates haven't
 *      been filled in yet — pass one of those as $fallbackCoords.
 *   3. A Google search of "<title>, <address>, Malaysia". Last resort: it guesses,
 *      and for anything but a famous landmark it guesses wrong. Anchor to Malaysia
 *      only — NEVER append "Kuala Lumpur", because many rows are in Selangor /
 *      Petaling Jaya / Subang and the wrong anchor mislocates them outright.
 *
 * $title   — well-known place name (already urldecoded, as displayed)
 * $address — street address (already urldecoded); ignored if it's a URL or a
 *            bare domain like "kltheguide.com.my"
 *
 * Returns '' (no button) for rows whose title is just a bare domain — that's how
 * a small number of placeholder/junk rows show up.
 *
 * MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 */
if (!function_exists('viewOnMapButton')) {
  function viewOnMapButton($title, $address = '', $coords = '', $fallbackCoords = '')
  {
    $title = trim((string) $title);

    // Exact coordinates — preferred path, pins precisely.
    foreach ([$coords, $fallbackCoords] as $c) {
      $c = trim((string) $c);
      if ($c !== '' && preg_match('~^-?[0-9.]+,-?[0-9.]+$~', $c)) {
        return '<a class="btn btn-sm btn-primary view-on-map-btn mt-1 mb-2" '
          . 'href="map.php?q=' . urlencode($c) . '">'
          . '<i class="bi bi-pin-map"></i> View on Map</a>';
      }
    }

    // Skip rows where the title is just a bare domain (placeholder/junk data).
    if (preg_match('~^[a-z0-9.-]+\.[a-z]{2,}$~i', $title)) return '';

    $addr = trim((string) $address);
    if ($addr !== '' && preg_match('~^https?://~i', $addr)) $addr = '';
    if ($addr !== '' && preg_match('~^[a-z0-9.-]+\.[a-z]{2,}$~i', $addr)) $addr = '';

    $parts = array_filter([$title, $addr], static fn($p) => $p !== '');
    if (!$parts) return '';

    $query = implode(', ', $parts);
    if (stripos($query, 'malaysia') === false) $query .= ', Malaysia';

    return '<a class="btn btn-sm btn-primary view-on-map-btn mt-1 mb-2" '
      . 'href="map.php?q=' . urlencode($query) . '">'
      . '<i class="bi bi-pin-map"></i> View on Map</a>';
  }
}
