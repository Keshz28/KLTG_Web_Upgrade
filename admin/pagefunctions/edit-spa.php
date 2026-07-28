<?php
/* ============================================================================
 *                       pagefunctions/edit-spa.php
 *
 *   Add / edit / delete / reorder for the Spa page. Pairs with admin/edit-spa.php.
 *
 *   Now driven by cms_place_crud() (admin/functions.php). The hand-written
 *   version this replaces built its SQL by string interpolation, left the image
 *   column out of the UPDATE (so "Save Changes" could never replace a picture),
 *   and never redirected after a POST, so refreshing the page re-ran the last
 *   add or delete.
 *
 *   Encoding note: spa rows are urlencode()d and spa.php urldecode()s on output.
 *   Phone is stored raw, exactly as before — do not "tidy" either without
 *   changing the public page to match.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

cms_place_crud($db, [
    'table'  => 'spa',
    'folder' => 'spa',
    'label'  => 'Spa',

    'add'    => 'upload_spa',
    'edit'   => 'editspa',
    'delete' => 'deletespa',

    'file'      => 'fileToUpload',
    'id_field'  => 'spaid',
    'img_field' => 'imagenamespa',

    'orderup'   => 'orderupSPA',
    'orderdown' => 'orderdownSPA',
    'order_id'  => 'spa_id',

    'fields' => [
        'title'       => ['name',        'urlencode'],
        'content'     => ['content',     'urlencode'],
        'location'    => ['location',    'urlencode'],
        'locationurl' => ['locationurl', 'urlencode'],
        'hours'       => ['hours',       'urlencode'],
        'phone'       => ['phone',       null],
        'mapcoords'   => ['mapcoords',   null],
    ],
]);
