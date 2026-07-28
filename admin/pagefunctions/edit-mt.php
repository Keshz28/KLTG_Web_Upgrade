<?php
/* ============================================================================
 *                        pagefunctions/edit-mt.php
 *
 *   Add / edit / delete / reorder for the five Medical Tourism tables.
 *   Pairs with admin/edit-medical-tourism.php.
 *
 *   Now driven by cms_place_crud() (admin/functions.php). The five hand-written
 *   copies this replaces shared the same defects:
 *     - the image column was missing from every UPDATE, so "Save Changes" could
 *       never replace a picture;
 *     - SQL was built by string interpolation;
 *     - uploadimage() returned false when a file of that name already existed,
 *       inserting a row with an empty image and still reporting success;
 *     - no redirect after POST, so a refresh re-ran the add or the delete.
 *
 *   The four section-nav editors that used to live at the bottom of this file
 *   (explorekl / beyondkl / accommodation / medical_tourism) moved to
 *   pagefunctions/edit-sectionnav.php — they were never Medical Tourism specific.
 *
 *   Encoding note: these rows are urlencode()d and medical-tourism.php
 *   urldecode()s on output. Phone is stored raw, exactly as before.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

/** Field map shared by Healthcare, Dental, Dermatology and Ophthalmology. */
$mt_fields = [
    'title'       => ['name',        'urlencode'],
    'content'     => ['content',     'urlencode'],
    'location'    => ['location',    'urlencode'],
    'locationurl' => ['locationurl', 'urlencode'],
    'hours'       => ['hours',       'urlencode'],
    'phone'       => ['phone',       null],
    'mapcoords'   => ['mapcoords',   null],
];

// Healthcare
cms_place_crud($db, [
    'table' => 'medical_tourism_hc', 'folder' => 'medical_tourism/hc', 'label' => 'Healthcare',
    'add' => 'upload_mthc', 'edit' => 'editmthc', 'delete' => 'deletemthc',
    'file' => 'fileToUploadhc', 'id_field' => 'mthcid', 'img_field' => 'imagenamemthc',
    'orderup' => 'orderupMTH', 'orderdown' => 'orderdownMTH', 'order_id' => 'medical_tourism_hc_id',
    'fields' => $mt_fields,
]);

// Dental
cms_place_crud($db, [
    'table' => 'medical_tourism_dtl', 'folder' => 'medical_tourism/dtl', 'label' => 'Dental',
    'add' => 'upload_mtdtl', 'edit' => 'editmtDTL', 'delete' => 'deletemtDTL',
    'file' => 'fileToUploaddtl', 'id_field' => 'mtdtlid', 'img_field' => 'imagenamemtdtl',
    'orderup' => 'orderupMTDTL', 'orderdown' => 'orderdownMTDTL', 'order_id' => 'medical_tourism_dtl_id',
    'fields' => $mt_fields,
]);

// Dermatology
cms_place_crud($db, [
    'table' => 'medical_tourism_der', 'folder' => 'medical_tourism/der', 'label' => 'Dermatology',
    'add' => 'upload_mtder', 'edit' => 'editmtDER', 'delete' => 'deletemtDER',
    'file' => 'fileToUploadder', 'id_field' => 'mtderid', 'img_field' => 'imagenamemtder',
    'orderup' => 'orderupMTDER', 'orderdown' => 'orderdownMTDER', 'order_id' => 'medical_tourism_der_id',
    'fields' => $mt_fields,
]);

// Ophthalmology
cms_place_crud($db, [
    'table' => 'medical_tourism_oph', 'folder' => 'medical_tourism/oph', 'label' => 'Ophthalmology',
    'add' => 'upload_mtoph', 'edit' => 'editmtOPH', 'delete' => 'deletemtOPH',
    'file' => 'fileToUploadoph', 'id_field' => 'mtophid', 'img_field' => 'imagenamemtoph',
    'orderup' => 'orderupMTOPH', 'orderdown' => 'orderdownMTOPH', 'order_id' => 'medical_tourism_oph_id',
    'fields' => $mt_fields,
]);

// Plastic Surgery — carries two extra columns the other four don't have.
cms_place_crud($db, [
    'table' => 'medical_tourism_ps', 'folder' => 'medical_tourism/ps', 'label' => 'Plastic Surgery',
    'add' => 'upload_mtps', 'edit' => 'editmtPS', 'delete' => 'deletemtPS',
    'file' => 'fileToUploadps', 'id_field' => 'mtpsid', 'img_field' => 'imagenamemtps',
    'orderup' => 'orderupMTPS', 'orderdown' => 'orderdownMTPS', 'order_id' => 'medical_tourism_ps_id',
    'fields' => $mt_fields + [
        'websiteurl' => ['website', 'urlencode'],
        'article'    => ['article', 'urlencode'],
    ],
]);
