<?php
/**
 * Shared "View on Map" button helper for medical-tourism / where-to-shop / spa /
 * accommodation pages. (Beyond KL and Explore KL still carry their own inline
 * copies for historical reasons — leave those alone unless consolidating.)
 *
 * Renders a button that deep-links to map.php, focusing the location. Pass:
 *   $title   — well-known place name (already urldecoded, as displayed)
 *   $address — street address (already urldecoded); ignored if it's a URL or a
 *              bare domain like "kltheguide.com.my"
 *   $coords  — optional "lat,lng" string. When present and valid, the button
 *              pins by EXACT coordinates (best accuracy). Look these up from
 *              kltg_mapcoords.php by trimmed urldecoded title.
 *
 * Without coords, falls back to a Google search of "<title>, <address>, Malaysia".
 * We anchor to Malaysia only — NEVER append "Kuala Lumpur", because many rows
 * are in Selangor / Petaling Jaya / Subang and the wrong anchor mislocates them.
 *
 * Returns '' (no button) for rows whose title is just a bare domain — that's
 * how a small number of placeholder/junk rows show up.
 *
 * MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 */
if (!function_exists('viewOnMapButton')) {
  function viewOnMapButton($title, $address = '', $coords = '')
  {
    $title  = trim((string) $title);
    $coords = trim((string) $coords);

    // Exact coordinates — preferred path, pins precisely.
    if ($coords !== '' && preg_match('~^-?[0-9.]+,-?[0-9.]+$~', $coords)) {
      return '<a class="btn btn-sm btn-primary view-on-map-btn mt-1 mb-2" '
        . 'href="map.php?q=' . urlencode($coords) . '">'
        . '<i class="bi bi-pin-map"></i> View on Map</a>';
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
