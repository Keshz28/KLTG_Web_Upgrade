<?php
/* ============================================================================
 *                    pagefunctions/edit-accomodation.php
 *
 *   Add / edit / delete / reorder for the four Place To Stay tables.
 *   Pairs with admin/edit-accomodation.php.
 *
 *   Now driven by cms_place_crud() (admin/functions.php). The four hand-written
 *   copies this replaces shared the same defects:
 *     - the image column was missing from every UPDATE, so "Save Changes" could
 *       never replace a picture;
 *     - Budget Hotels' delete read $_POST['imagenameah'] instead of
 *       'imagenameabh', so it deleted the row but left the image file orphaned;
 *     - SQL was built by string interpolation;
 *     - uploadimage() returned false when a file of that name already existed,
 *       inserting a row with an empty image and still reporting success;
 *     - no redirect after POST, so a refresh re-ran the add or the delete.
 *
 *   Encoding note: these rows are urlencode()d and accommodation.php
 *   urldecode()s on output. Phone is stored raw, exactly as before.
 *
 *   The four *_nav handlers for this page live in edit-mt.php, which is where
 *   all four section-nav editors are grouped.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

/** Field map shared by all four accommodation tables. */
$accommodation_fields = [
    'title'       => ['name',        'urlencode'],
    'content'     => ['content',     'urlencode'],
    'location'    => ['location',    'urlencode'],
    'locationurl' => ['locationurl', 'urlencode'],
    'hours'       => ['hours',       'urlencode'],
    'phone'       => ['phone',       null],
    'mapcoords'   => ['mapcoords',   null],
];

// Top Places To Stay
cms_place_crud($db, [
    'table' => 'accommodation_top', 'folder' => 'accommodation/top', 'label' => 'Top Place To Stay',
    'add' => 'upload_atop', 'edit' => 'editatop', 'delete' => 'deleteatop',
    'file' => 'fileToUploadatop', 'id_field' => 'atopid', 'img_field' => 'imagenameatop',
    'orderup' => 'orderupATOP', 'orderdown' => 'orderdownATOP', 'order_id' => 'accommodation_top_id',
    'fields' => $accommodation_fields,
]);

// Hotels
cms_place_crud($db, [
    'table' => 'accommodation_h', 'folder' => 'accommodation/h', 'label' => 'Hotel',
    'add' => 'upload_ah', 'edit' => 'editah', 'delete' => 'deleteah',
    'file' => 'fileToUploadah', 'id_field' => 'ahid', 'img_field' => 'imagenameah',
    'orderup' => 'orderupAH', 'orderdown' => 'orderdownAH', 'order_id' => 'accommodation_h_id',
    'fields' => $accommodation_fields,
]);

// Budget Hotels
cms_place_crud($db, [
    'table' => 'accommodation_bh', 'folder' => 'accommodation/bh', 'label' => 'Budget Hotel',
    'add' => 'upload_abh', 'edit' => 'editabh', 'delete' => 'deleteabh',
    'file' => 'fileToUploadabh', 'id_field' => 'abhid', 'img_field' => 'imagenameabh',
    'orderup' => 'orderupABH', 'orderdown' => 'orderdownABH', 'order_id' => 'accommodation_bh_id',
    'fields' => $accommodation_fields,
]);

// Backpackers Lodge
cms_place_crud($db, [
    'table' => 'accommodation_bks', 'folder' => 'accommodation/bks', 'label' => 'Backpackers Lodge',
    'add' => 'upload_abks', 'edit' => 'editabks', 'delete' => 'deleteabks',
    'file' => 'fileToUploadabks', 'id_field' => 'abksid', 'img_field' => 'imagenameabks',
    'orderup' => 'orderupABKS', 'orderdown' => 'orderdownABKS', 'order_id' => 'accommodation_bks_id',
    'fields' => $accommodation_fields,
]);
