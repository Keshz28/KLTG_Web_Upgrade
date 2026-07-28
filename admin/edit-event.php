<?php
/* ============================================================================
 *                              edit-event.php
 *
 *   Admin editor — events (redesigned as a month-grouped "schedule" board).
 *
 *   - Prominent "Add Event" button + filter bar (year / month / category).
 *   - Events render as image cards grouped under month headings.
 *   - Default view: current month onward (matches the public page + app feed).
 *   - Drag a card to reorder within its month (POSTs to reorder.php, table=event).
 *
 *   Add / edit / delete / reorder are all still handled by
 *   pagefunctions/edit-ev.php (included via functions.php). The hidden data
 *   spans on each card feed editmodalev() in js/editevent.js, unchanged.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('functions.php');

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit;
}

/* ---- Lookups -------------------------------------------------------------- */
$monthNames = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
$eventCategories = ['Holiday', 'Exhibition', 'Nightlife', 'Food', 'Happening', 'Entertainment'];

$curYear  = (int) date('Y');
$curMonth = (int) date('n');

/* ---- Filters (GET) -------------------------------------------------------- */
$fyear     = (isset($_GET['fyear'])     && $_GET['fyear']     !== '') ? $_GET['fyear']     : 'all';
$fmonth    = (isset($_GET['fmonth'])    && $_GET['fmonth']    !== '') ? $_GET['fmonth']    : 'all';
$fcategory = (isset($_GET['fcategory']) && $_GET['fcategory'] !== '') ? $_GET['fcategory'] : 'all';
$showpast  = isset($_GET['showpast']);

$conds = [];
if ($fyear !== 'all')     { $conds[] = "event_year = " . (int) $fyear; }
if ($fmonth !== 'all')    { $conds[] = "event_month = " . (int) $fmonth; }
if ($fcategory !== 'all') { $conds[] = "event_category = '" . mysqli_real_escape_string($db, $fcategory) . "'"; }

// When the admin hasn't narrowed to a specific year/month (and isn't asking for
// history), default to "current month onward" so you land on upcoming events.
$applyUpcoming = ($fyear === 'all' && $fmonth === 'all' && !$showpast);
if ($applyUpcoming) {
    $conds[] = "(event_year > $curYear OR (event_year = $curYear AND event_month >= $curMonth))";
}

$where = $conds ? ('WHERE ' . implode(' AND ', $conds)) : '';
$query = "SELECT * FROM event $where ORDER BY event_year ASC, event_month ASC, event_order DESC";
$result = mysqli_query($db, $query);

$grouped = [];
$total   = 0;
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $grouped[(int) $row['event_year']][(int) $row['event_month']][] = $row;
        $total++;
    }
}
ksort($grouped);

// Distinct years for the filter dropdown (+ guarantee current & next year).
$years = [];
$yres = mysqli_query($db, "SELECT DISTINCT event_year FROM event ORDER BY event_year ASC");
if ($yres) {
    while ($yr = mysqli_fetch_assoc($yres)) { $years[] = (int) $yr['event_year']; }
}
foreach ([$curYear, $curYear + 1] as $y) { if (!in_array($y, $years, true)) { $years[] = $y; } }
sort($years);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Event</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Schedule board styles (scoped under #sched) -->
    <style>
        #sched .sched-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        #sched .sched-add {
            box-shadow: 0 .25rem .6rem rgba(78, 115, 223, .35);
            font-weight: 700;
            letter-spacing: .2px;
        }
        #sched .sched-add i { margin-right: .45rem; }

        #sched .sched-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: .6rem;
        }
        #sched .sched-filters .form-group { margin-bottom: 0; }
        #sched .sched-filters label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #858796;
            margin-bottom: .15rem;
            font-weight: 700;
        }
        #sched .sched-filters select { min-width: 130px; }
        #sched .sched-pastcheck { display: flex; align-items: center; gap: .35rem; padding-bottom: .5rem; }

        #sched .sched-summary {
            color: #6e707e;
            font-size: .9rem;
            margin: 1.1rem .15rem .35rem;
        }

        /* Month heading bar */
        #sched .sched-month {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin: 1.6rem 0 .9rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid #e3e6f0;
        }
        #sched .sched-month h4 {
            margin: 0;
            font-weight: 800;
            color: #3a3b45;
            font-size: 1.15rem;
        }
        #sched .sched-month .badge {
            background: #4e73df;
            color: #fff;
            font-size: .72rem;
            padding: .35em .6em;
        }
        #sched .sched-month .sched-past-tag {
            margin-left: auto;
            font-size: .72rem;
            color: #b58105;
            background: #fff3cd;
            padding: .2em .55em;
            border-radius: .3rem;
            font-weight: 700;
        }

        /* Card grid */
        #sched .sched-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(235px, 1fr));
            gap: 1.1rem;
        }
        #sched .sched-card {
            position: relative;
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: .6rem;
            overflow: hidden;
            box-shadow: 0 .15rem .45rem rgba(58, 59, 69, .08);
            transition: box-shadow .15s ease, transform .15s ease;
            display: flex;
            flex-direction: column;
        }
        #sched .sched-card:hover {
            box-shadow: 0 .5rem 1.1rem rgba(58, 59, 69, .18);
            transform: translateY(-2px);
        }
        #sched .sched-card.sortable-ghost { opacity: .4; }
        #sched .sched-card.sortable-chosen { box-shadow: 0 .6rem 1.3rem rgba(58, 59, 69, .25); }

        #sched .sched-thumb {
            position: relative;
            height: 140px;
            background: #f8f9fc;
        }
        #sched .sched-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        #sched .sched-chip {
            position: absolute;
            top: .55rem;
            left: .55rem;
            background: rgba(0, 0, 0, .68);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            padding: .22em .55em;
            border-radius: .3rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        #sched .sched-grip {
            position: absolute;
            top: .5rem;
            right: .5rem;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .9);
            border-radius: .35rem;
            color: #858796;
            cursor: grab;
            box-shadow: 0 .1rem .3rem rgba(0, 0, 0, .15);
        }
        #sched .sched-grip:active { cursor: grabbing; }

        #sched .sched-body {
            padding: .75rem .85rem .55rem;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
        #sched .sched-title {
            font-weight: 700;
            color: #3a3b45;
            font-size: .95rem;
            line-height: 1.25;
            margin-bottom: .4rem;
        }
        #sched .sched-meta {
            font-size: .78rem;
            color: #6e707e;
            display: flex;
            align-items: flex-start;
            gap: .4rem;
            margin-bottom: .25rem;
        }
        #sched .sched-meta i { color: #4e73df; margin-top: .15rem; width: 13px; text-align: center; }

        #sched .sched-foot {
            border-top: 1px solid #eceef5;
            padding: .5rem .85rem;
            display: flex;
            gap: .4rem;
        }
        #sched .sched-foot .btn { flex: 1; font-size: .78rem; padding: .3rem .4rem; }

        /* Empty state */
        #sched .sched-empty {
            text-align: center;
            padding: 3.5rem 1rem;
            color: #858796;
        }
        #sched .sched-empty i { font-size: 2.6rem; color: #d1d3e2; margin-bottom: .8rem; }
        #sched .sched-empty h5 { color: #5a5c69; font-weight: 700; }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include('nav.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topnav.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid" id="sched">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Event</h1>
                    <p class="mb-4 text-muted">Plan and schedule the events shown on the public Events page. Add, edit and reorder them by month below.</p>

                    <!-- Toolbar: Add + Filters -->
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <div class="sched-toolbar">
                                <button class="btn btn-primary btn-lg sched-add" type="button" data-toggle="modal" data-target="#exampleModal2">
                                    <i class="fas fa-plus-circle"></i> Add Event
                                </button>

                                <form class="sched-filters" method="get" action="edit-event.php">
                                    <div class="form-group">
                                        <label for="fyear">Year</label>
                                        <select class="form-control" id="fyear" name="fyear">
                                            <option value="all"<?= $fyear === 'all' ? ' selected' : '' ?>>All years</option>
                                            <?php foreach ($years as $y): ?>
                                                <option value="<?= $y ?>"<?= ((string) $fyear === (string) $y) ? ' selected' : '' ?>><?= $y ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fmonth">Month</label>
                                        <select class="form-control" id="fmonth" name="fmonth">
                                            <option value="all"<?= $fmonth === 'all' ? ' selected' : '' ?>>All months</option>
                                            <?php foreach ($monthNames as $num => $nm): ?>
                                                <option value="<?= $num ?>"<?= ((string) $fmonth === (string) $num) ? ' selected' : '' ?>><?= $nm ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fcategory">Category</label>
                                        <select class="form-control" id="fcategory" name="fcategory">
                                            <option value="all"<?= $fcategory === 'all' ? ' selected' : '' ?>>All categories</option>
                                            <?php foreach ($eventCategories as $cat): ?>
                                                <option value="<?= htmlspecialchars($cat) ?>"<?= ($fcategory === $cat) ? ' selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="sched-pastcheck">
                                        <input type="checkbox" id="showpast" name="showpast" value="1"<?= $showpast ? ' checked' : '' ?>>
                                        <label for="showpast" class="mb-0" style="text-transform:none;letter-spacing:0;color:#6e707e;font-weight:600;">Include past</label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i> Apply</button>
                                        <a href="edit-event.php" class="btn btn-link text-muted">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Summary line -->
                    <div class="sched-summary">
                        <?php if ($applyUpcoming): ?>
                            Showing <strong><?= $total ?></strong> upcoming event<?= $total === 1 ? '' : 's' ?> (current month onward).
                        <?php else: ?>
                            Showing <strong><?= $total ?></strong> event<?= $total === 1 ? '' : 's' ?> for the selected filters.
                        <?php endif; ?>
                    </div>

                    <?php if ($total === 0): ?>
                        <div class="card shadow">
                            <div class="sched-empty">
                                <div><i class="fas fa-calendar-times"></i></div>
                                <h5>No events to show</h5>
                                <p class="mb-3">There are no events for the current selection.<br>Try a different month/year, include past events, or add a new one.</p>
                                <button class="btn btn-primary sched-add" type="button" data-toggle="modal" data-target="#exampleModal2">
                                    <i class="fas fa-plus-circle"></i> Add Event
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($grouped as $gy => $monthsArr): ?>
                            <?php ksort($monthsArr); ?>
                            <?php foreach ($monthsArr as $gm => $evs): ?>
                                <?php
                                $isPast = ($gy < $curYear) || ($gy === $curYear && $gm < $curMonth);
                                $count  = count($evs);
                                ?>
                                <div class="sched-month">
                                    <h4><?= $monthNames[$gm] ?? '' ?> <?= $gy ?></h4>
                                    <span class="badge"><?= $count ?> event<?= $count === 1 ? '' : 's' ?></span>
                                    <?php if ($isPast): ?><span class="sched-past-tag">Past</span><?php endif; ?>
                                </div>

                                <div class="sched-grid" data-sched-grid>
                                    <?php foreach ($evs as $ev): ?>
                                        <?php
                                        $id      = (int) $ev['event_id'];
                                        $title   = urldecode($ev['event_title']);
                                        $imgFile = $ev['event_image']
                                            ? '../assets/img/event/' . $ev['event_image']
                                            : '../assets/img/event/comingsoon.png';
                                        $cat     = $ev['event_category'];
                                        $dayText = trim(str_replace('/', ', ', urldecode($ev['event_day'])));
                                        $locText = trim(str_replace('/', ', ', urldecode($ev['event_location'])));
                                        ?>
                                        <div class="sched-card" data-reorder-id="<?= $id ?>">
                                            <div class="sched-thumb">
                                                <img src="<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($title) ?>"
                                                    loading="lazy" onerror="this.src='../assets/img/event/comingsoon.png';">
                                                <?php if ($cat): ?>
                                                    <span class="sched-chip"><?= htmlspecialchars($cat) ?></span>
                                                <?php endif; ?>
                                                <span class="sched-grip" title="Drag to reorder within this month"><i class="fas fa-grip-vertical"></i></span>
                                            </div>
                                            <div class="sched-body">
                                                <div class="sched-title"><?= htmlspecialchars($title) ?></div>
                                                <?php if ($dayText !== ''): ?>
                                                    <div class="sched-meta"><i class="fas fa-calendar-day"></i><span><?= htmlspecialchars($dayText) ?> &middot; <?= $monthNames[$gm] ?? '' ?> <?= $gy ?></span></div>
                                                <?php else: ?>
                                                    <div class="sched-meta"><i class="fas fa-calendar-day"></i><span><?= $monthNames[$gm] ?? '' ?> <?= $gy ?></span></div>
                                                <?php endif; ?>
                                                <?php if ($locText !== ''): ?>
                                                    <div class="sched-meta"><i class="fas fa-map-marker-alt"></i><span><?= htmlspecialchars($locText) ?></span></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="sched-foot">
                                                <button type="button" class="btn btn-outline-primary" onclick="editmodalev(<?= $id ?>);">
                                                    <i class="fas fa-pen"></i> Edit
                                                </button>
                                            </div>

                                            <!-- Hidden data feed for editmodalev() — keep ids in sync with js/editevent.js -->
                                            <div style="display:none">
                                                <span id="orderev-<?= $id ?>"><?= htmlspecialchars($ev['event_order']) ?></span>
                                                <span id="nameev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_title'])) ?></span>
                                                <span id="filenameev-<?= $id ?>"><?= htmlspecialchars($ev['event_image']) ?></span>
                                                <span id="locationev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_location'])) ?></span>
                                                <span id="locationurlev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_locationurl'])) ?></span>
                                                <span id="contentev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_content'])) ?></span>
                                                <span id="content2ev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_content2'])) ?></span>
                                                <span id="hoursev-<?= $id ?>"><?= htmlspecialchars(urldecode($ev['event_hours'])) ?></span>
                                                <span id="phoneev-<?= $id ?>"><?= htmlspecialchars($ev['event_phone']) ?></span>
                                                <span id="dayev-<?= $id ?>"><?= htmlspecialchars($ev['event_day']) ?></span>
                                                <span id="monthev-<?= $id ?>"><?= htmlspecialchars($ev['event_month']) ?></span>
                                                <span id="yearev-<?= $id ?>"><?= htmlspecialchars($ev['event_year']) ?></span>
                                                <span id="websiteev-<?= $id ?>"><?= htmlspecialchars($ev['event_website']) ?></span>
                                                <span id="categoryev-<?= $id ?>"><?= htmlspecialchars($ev['event_category']) ?></span>
                                                <span id="facebookev-<?= $id ?>"><?= htmlspecialchars($ev['event_facebook']) ?></span>
                                                <span id="instagramev-<?= $id ?>"><?= htmlspecialchars($ev['event_instagram']) ?></span>
                                                <span id="tiktokev-<?= $id ?>"><?= htmlspecialchars($ev['event_tiktok']) ?></span>
                                                <span id="youtubeev-<?= $id ?>"><?= htmlspecialchars($ev['event_youtube']) ?></span>
                                                <span id="twitterev-<?= $id ?>"><?= htmlspecialchars($ev['event_twitter']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Reorder status -->
                    <div id="schedReorderStatus" class="small mt-3"></div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Event-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Event</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#ev" method="post" enctype="multipart/form-data" id="ev">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <label for="nameev">Title</label>
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Title (Do Not Use &quot; ' &quot; (single quotes) instead use &quot; ` &quot; (backtick))"
                                name="name">
                        </div>
                        <div class="form-group">
                        <label for="locationev">Location</label>
                            <textarea type="text" class="form-control form-control-user" id="location"
                                placeholder="Location" name="location" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="locationurlev">Location URL</label>
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                        <label for="contentev">Content</label>
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="content2ev">Content2</label>
                            <textarea type="text" class="form-control form-control-user" id="content2"
                                placeholder="Content2" name="content2" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="hoursev">Operating Hours (Put "/" for new line )</label>
                            <textarea type="text" class="form-control form-control-user" id="hours"
                                placeholder="Operating Hours" name="hours" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="phoneev">Phone (Put "/" for new line )</label>
                            <textarea type="text" class="form-control form-control-user" id="phone" placeholder="Phone"
                                name="phone" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="dayev">Date</label>
                            <textarea type="text" class="form-control form-control-user" id="day" placeholder="Date"
                                name="day" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="monthev">Month</label>
                            <textarea type="text" class="form-control form-control-user" id="month" placeholder="Month (set Month as Digits (Exp: 1, 2, 3... etc))"
                                name="month" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="yearev">Year</label>
                            <textarea type="text" class="form-control form-control-user" id="year" placeholder="Year (set Year is Numerical form (Exp: 2020, 2021, 2022... etc))"
                                name="year" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="categoryev">Category</label>
                            <textarea type="text" class="form-control form-control-user" id="category" placeholder="Category ( Holiday, Exhibition, Nightlife, Food, Happening, Entertainment )"
                                name="category" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="websiteev">Website</label>
                            <textarea type="text" class="form-control form-control-user" id="website" placeholder="Website Url"
                                name="website" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="facebookev">Facebook</label>
                            <textarea type="text" class="form-control form-control-user" id="facebook" placeholder="Facebook Url"
                                name="facebook" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="instagramev">Instagram</label>
                            <textarea type="text" class="form-control form-control-user" id="instagram" placeholder="Instagram Url"
                                name="instagram" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="tiktokev">Tiktok</label>
                            <textarea type="text" class="form-control form-control-user" id="tiktok" placeholder="Tiktok Url"
                                name="tiktok" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="youtubeev">Youtube</label>
                            <textarea type="text" class="form-control form-control-user" id="youtube" placeholder="Youtube Url"
                                name="youtube" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="twitterev">Twitter</label>
                            <textarea type="text" class="form-control form-control-user" id="twitter" placeholder="Twitter Url"
                                name="twitter" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadev" name="fileToUploadev">
                        </div>
                    </div>
                    <input class="form-control" id="eventid" name="eventid" hidden></input>
                    <input class="form-control" id="imagenameev" name="imagenameev" hidden></input>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_ev">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal Event -->
<div class="modal fade" id="editevmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="?edit" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="tagdiv2">
                    <div class="form-group">
                        <label for="event_id">Id</label>
                        <input type="text" class="form-control" id="event_id" name="event_id" readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" id="titleev" placeholder="Title" name="title">
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="locationev" placeholder="Location" name="location" rows="1"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="locationurlev" placeholder="Location URL" name="locationurl" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="contentev" placeholder="Content" name="content" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="content2ev" placeholder="Content2" name="content2" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="hoursev" placeholder="Operating Hours" name="hours" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="phoneev" placeholder="Phone" name="phone" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="number" class="form-control form-control-user" id="orderev" placeholder="Order" name="order" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="dayev" placeholder="Day" name="day" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="monthev" placeholder="Month" name="month" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="yearev" placeholder="Year" name="year" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="categoryev" placeholder="Category" name="category" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="websiteev" placeholder="Website" name="website" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="facebookev" placeholder="Facebook" name="facebook" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="instagramev" placeholder="Instagram" name="instagram" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="tiktokev" placeholder="Tiktok" name="tiktok" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="youtubeev" placeholder="Youtube" name="youtube" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="twitterev" placeholder="Twitter" name="twitter" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Select image to upload :</label>
                        <input type="file" class="form-control-file" id="fileToUploadev" name="fileToUploadev">
                    </div>
                    <input type="hidden" id="imagenameev" name="imagename">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="deleteev">Delete</button>
                    <button class="btn btn-primary" type="submit" name="editev">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- Toast -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="toast-header">
                <img src="../assets/img/favicon-32x32.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto">Bluedale</strong>
                <!-- <small>11 mins ago</small> -->
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" id="toast-body">
                Test
            </div>
        </div>
    </div>
    <!-- End Toast -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/editevent.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <?php include('errors2.php'); ?>

    <!-- Drag-to-reorder cards within a month -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        (function () {
            if (!window.Sortable) { return; }
            var statusEl = document.getElementById('schedReorderStatus');

            function setStatus(msg, cls) {
                if (!statusEl) { return; }
                statusEl.textContent = msg;
                statusEl.className = 'small mt-3 ' + cls;
            }

            document.querySelectorAll('[data-sched-grid]').forEach(function (grid) {
                Sortable.create(grid, {
                    handle: '.sched-grip',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function () {
                        // Cards top-to-bottom. reorder.php numbers the first id = 1,
                        // and the public page sorts event_order DESC, so reverse the
                        // list to give the top card the highest order (shown first).
                        var ids = Array.prototype.map.call(grid.children, function (el) {
                            return el.getAttribute('data-reorder-id');
                        }).filter(Boolean).reverse();
                        if (!ids.length) { return; }

                        setStatus('Saving order…', 'text-muted');

                        var body = new URLSearchParams();
                        body.append('table', 'event');
                        ids.forEach(function (id) { body.append('ids[]', id); });
                        if (window.CSRF_TOKEN) { body.append('csrf_token', window.CSRF_TOKEN); }

                        fetch('reorder.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: body.toString()
                        })
                        .then(function (r) { return r.json(); })
                        .then(function (res) {
                            if (res && res.success) {
                                setStatus('✓ Order saved', 'text-success');
                            } else {
                                setStatus('Could not save order: ' + ((res && res.error) || 'error'), 'text-danger');
                            }
                        })
                        .catch(function () {
                            setStatus('Could not save order (network error).', 'text-danger');
                        });
                    }
                });
            });
        })();
    </script>
</body>

</html>
