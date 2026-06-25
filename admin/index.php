<?php
/* ============================================================================
 *                                index.php
 *
 *   Admin DASHBOARD — landing page after login; shows pageview stats.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('functions.php') ;

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


$query = "SELECT  url, sum(views) ,dateent FROM `pageview` GROUP BY url ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
    // echo $row['url'];
    $url = $row['url'];
    $views = $row['sum(views)'];
    $views2 = 0;
    // echo '<br>';

    $query2 = "INSERT INTO pageview2 (url, views, views2) 
    VALUES('$url', '$views', '$views2') ON DUPLICATE KEY UPDATE views2=views2 , views=$views";
    mysqli_query($db, $query2);

    // echo $query2;
    // echo '<br>';


}

$totalpageviews= 0;
$totalblogviews= 0;
$totalebookviews= 0;

$query = "SELECT  sum(views)+sum(views2), dateent  FROM `pageview2` WHERE url NOT LIKE '%blog-%' AND  url NOT LIKE '%ebook-%' ORDER BY sum(views)+sum(views2) DESC ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
$totalpageviews += $row['sum(views)+sum(views2)'];
}

$query2 = "SELECT  blog_view+blog_view2   FROM `blog` ORDER BY blog_id DESC  ";
$result2 = mysqli_query($db, $query2);
while ($row2 = mysqli_fetch_assoc($result2)) {
$totalblogviews += $row2['blog_view+blog_view2'];
}

$query3 = "SELECT  ebook_view+ebook_view2   FROM `ebook`  ";
$result3 = mysqli_query($db, $query3);
while ($row3 = mysqli_fetch_assoc($result3)) {
$totalebookviews += $row3['ebook_view+ebook_view2'];
}


$allrows = array();
$query4 = "SELECT  sum(views), DATE_FORMAT(dateent, '%Y-%m-%d') as date  FROM `pageview`
WHERE url NOT LIKE '%blog-%' AND  url NOT LIKE '%ebook-%'
GROUP BY DATE_FORMAT(dateent, '%Y-%m-%d')
ORDER BY DATE_FORMAT(dateent, '%Y-%m-%d') DESC LIMIT 7";
$result4 = mysqli_query($db, $query4);
while ($row4 = mysqli_fetch_assoc($result4)) {
    $allrows[] = [
        'views' => $row4['sum(views)'],
        'date' => $row4['date'],
    ];
}

// Top pages by views
$top_pages = [];
$result_tp = mysqli_query($db, "SELECT url, (views + views2) AS total FROM pageview2 WHERE url NOT LIKE '%blog-%' AND url NOT LIKE '%ebook-%' ORDER BY total DESC LIMIT 7");
if ($result_tp) {
    while ($r = mysqli_fetch_assoc($result_tp)) { $top_pages[] = $r; }
}

// Top blog posts by views
$top_blogs = [];
$result_tb = mysqli_query($db, "SELECT blog_title, (blog_view + blog_view2) AS total FROM blog ORDER BY total DESC LIMIT 7");
if ($result_tb) {
    while ($r = mysqli_fetch_assoc($result_tb)) { $top_blogs[] = $r; }
}

// Content counts
$cnt_blog = 0; $cnt_ebook = 0; $cnt_klglance = 0; $cnt_sub = 0;
$r = mysqli_query($db, "SELECT COUNT(*) as cnt FROM blog");    if ($r) $cnt_blog    = (int)mysqli_fetch_assoc($r)['cnt'];
$r = mysqli_query($db, "SELECT COUNT(*) as cnt FROM ebook");   if ($r) $cnt_ebook   = (int)mysqli_fetch_assoc($r)['cnt'];
$r = mysqli_query($db, "SELECT COUNT(*) as cnt FROM klglance"); if ($r) $cnt_klglance = (int)mysqli_fetch_assoc($r)['cnt'];
$r = mysqli_query($db, "SELECT COUNT(*) as cnt FROM emailsub"); if ($r) $cnt_sub     = (int)mysqli_fetch_assoc($r)['cnt'];

// Recent blog posts for activity feed
$recent_blogs = [];
$r = mysqli_query($db, "SELECT blog_title, blog_timestamp FROM blog ORDER BY blog_id DESC LIMIT 5");
if ($r) { while ($row = mysqli_fetch_assoc($r)) { $recent_blogs[] = $row; } }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <link href="css/sb-admin-2.css" rel="stylesheet">
    <style>
        body { background: #f0f2f8; }

        /* ── Gradient stat cards ── */
        .stat-card { border: none; border-radius: 16px; color: #fff; position: relative; overflow: hidden; transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.18) !important; }
        .stat-card .card-body { padding: 1.6rem 1.5rem; }
        .stat-card .stat-label { font-size: .7rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; opacity: .82; margin-bottom: 6px; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
        .stat-card .stat-sub { font-size: .78rem; opacity: .78; }
        .stat-card .stat-icon { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); font-size: 3.4rem; opacity: .16; }
        .card-blue   { background: linear-gradient(135deg, #4e73df 0%, #2553c7 100%); }
        .card-green  { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
        .card-orange { background: linear-gradient(135deg, #f6a623 0%, #c27800 100%); }

        /* ── Chart cards ── */
        .chart-card { border: none; border-radius: 16px; }
        .chart-card .card-header { border-radius: 16px 16px 0 0 !important; background: #fff; border-bottom: 1px solid #f0f0f5; font-weight: 700; color: #3a3b45; }

        /* ── Quick Actions ── */
        .qa-grid { display: grid; grid-template-columns: repeat(6,1fr); gap: 14px; margin-bottom: 28px; }
        .qa-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 18px 8px; border-radius: 14px; background: #fff; border: none; cursor: pointer; text-decoration: none; color: #444; font-size: .72rem; font-weight: 700; box-shadow: 0 2px 10px rgba(0,0,0,.06); transition: transform .18s, box-shadow .18s; text-align: center; gap: 10px; }
        .qa-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,.12); color: #333; text-decoration: none; }
        .qa-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff; }
        .ic-blue   { background: linear-gradient(135deg,#4e73df,#2553c7); }
        .ic-green  { background: linear-gradient(135deg,#1cc88a,#13855c); }
        .ic-purple { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
        .ic-teal   { background: linear-gradient(135deg,#06b6d4,#0e7490); }
        .ic-orange { background: linear-gradient(135deg,#f6a623,#c27800); }
        .ic-pink   { background: linear-gradient(135deg,#ec4899,#be185d); }

        /* ── Leaderboard cards ── */
        .lb-card { border: none; border-radius: 16px; }
        .lb-card .card-header { background: #fff; border-bottom: 1px solid #f0f0f5; border-radius: 16px 16px 0 0 !important; font-weight: 700; color: #3a3b45; display: flex; align-items: center; justify-content: space-between; padding: .85rem 1.25rem; }
        .lb-row { display: flex; align-items: center; gap: 12px; padding: 10px 20px; border-bottom: 1px solid #f5f5fb; }
        .lb-row:last-child { border-bottom: none; }
        .lb-rank { width: 24px; height: 24px; border-radius: 50%; background: #f0f2f8; display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 800; color: #6c757d; flex-shrink: 0; }
        .lb-rank.gold   { background: #fef08a; color: #854d0e; }
        .lb-rank.silver { background: #e2e8f0; color: #475569; }
        .lb-rank.bronze { background: #fed7aa; color: #92400e; }
        .lb-title { flex: 1; font-size: .84rem; font-weight: 600; color: #3a3b45; min-width: 0; }
        .lb-title span { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lb-bar-wrap { width: 100px; flex-shrink: 0; }
        .lb-bar { height: 6px; border-radius: 3px; background: #eee; overflow: hidden; }
        .lb-bar-fill { height: 100%; border-radius: 3px; }
        .bar-blue  { background: linear-gradient(90deg,#4e73df,#2553c7); }
        .bar-green { background: linear-gradient(90deg,#1cc88a,#13855c); }
        .lb-count { font-size: .82rem; font-weight: 800; color: #3a3b45; width: 64px; text-align: right; flex-shrink: 0; }

        /* ── Content summary ── */
        .cs-card { border: none; border-radius: 16px; }
        .cs-card .card-header { background: #fff; border-bottom: 1px solid #f0f0f5; border-radius: 16px 16px 0 0 !important; font-weight: 700; color: #3a3b45; }
        .cs-item { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid #f5f5fb; }
        .cs-item:last-child { border-bottom: none; }
        .cs-dot { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; flex-shrink: 0; }
        .cs-label { font-size: .84rem; font-weight: 600; color: #3a3b45; flex: 1; }
        .cs-count { font-size: 1.3rem; font-weight: 800; color: #3a3b45; }

        /* ── Section label ── */
        .section-label { font-size: .68rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #b7b9cc; margin: 0 0 10px; }
    </style>
</head>

<body id="page-top">
<div id="wrapper">

    <?php include('nav.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('topnav.php'); ?>

            <div class="container-fluid pb-4">

                <!-- Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard</h1>
                        <p class="mb-0 text-muted" style="font-size:.83rem;">Welcome back — here's what's happening with KL The Guide.</p>
                    </div>
                    <span class="badge badge-light border px-3 py-2 d-none d-sm-inline-block" style="font-size:.78rem;">
                        <i class="fas fa-calendar-alt mr-1 text-primary"></i> <?php echo date('d M Y'); ?> &nbsp;|&nbsp; <span id="liveclock" class="font-weight-bold"></span>
                    </span>
                </div>

                <!-- ── Stat Cards ── -->
                <div class="row mb-2">
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card stat-card card-blue shadow h-100">
                            <div class="card-body">
                                <div class="stat-label">Total Page Views</div>
                                <div class="stat-value" id="sc-total"><?php echo number_format($totalpageviews + $totalblogviews + $totalebookviews); ?></div>
                                <div class="stat-sub">All pages combined &mdash; <?php echo date('d M Y'); ?></div>
                            </div>
                            <i class="fas fa-eye stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card stat-card card-green shadow h-100">
                            <div class="card-body">
                                <div class="stat-label">Total Blog Views</div>
                                <div class="stat-value" id="sc-blog"><?php echo number_format($totalblogviews); ?></div>
                                <div class="stat-sub">Across all blog posts</div>
                            </div>
                            <i class="fas fa-comments stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card stat-card card-orange shadow h-100">
                            <div class="card-body">
                                <div class="stat-label">Total E-book Views</div>
                                <div class="stat-value" id="sc-ebook"><?php echo number_format($totalebookviews); ?></div>
                                <div class="stat-sub">Across all e-books</div>
                            </div>
                            <i class="fas fa-book stat-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- ── Charts ── -->
                <div class="row mb-2">
                    <div class="col-xl-8 col-lg-7 mb-4">
                        <div class="card chart-card shadow h-100">
                            <div class="card-header py-3">
                                <span><i class="fas fa-chart-line mr-2 text-primary"></i>Page Views — Last 7 Days</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 mb-4">
                        <div class="card chart-card shadow h-100">
                            <div class="card-header py-3">
                                <span><i class="fas fa-chart-pie mr-2 text-primary"></i>Views Breakdown</span>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <div class="chart-pie pt-2 pb-2" style="width:100%;max-width:220px;">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                                <div class="mt-3 text-center small">
                                    <span class="mr-2"><i class="fas fa-circle text-primary"></i> Pages</span>
                                    <span class="mr-2"><i class="fas fa-circle text-success"></i> Blog</span>
                                    <span><i class="fas fa-circle" style="color:#f6a623"></i> E-Book</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Quick Actions ── -->
                <p class="section-label">Quick Actions</p>
                <div class="qa-grid">
                    <a href="edit-index.php"           class="qa-btn"><div class="qa-icon ic-blue"  ><i class="fas fa-home"></i></div>Edit Homepage</a>
                    <a href="edit-klglance.php"         class="qa-btn"><div class="qa-icon ic-teal"  ><i class="fas fa-landmark"></i></div>KL @ A Glance</a>
                    <a href="edit-blog.php"             class="qa-btn"><div class="qa-icon ic-green" ><i class="fas fa-pen-nib"></i></div>Edit Blog</a>
                    <a href="edit-explorekl.php"        class="qa-btn"><div class="qa-icon ic-purple"><i class="fas fa-map-marked-alt"></i></div>Explore KL</a>
                    <a href="sub.php"                   class="qa-btn"><div class="qa-icon ic-orange"><i class="fas fa-users"></i></div>Subscribers</a>
                    <a href="emailcampaign.php"         class="qa-btn"><div class="qa-icon ic-pink"  ><i class="fas fa-paper-plane"></i></div>Email Campaign</a>
                </div>

                <!-- ── Leaderboards + Content Summary ── -->
                <div class="row">

                    <!-- Top Pages -->
                    <div class="col-lg-4 mb-4">
                        <div class="card lb-card shadow h-100">
                            <div class="card-header">
                                <span><i class="fas fa-trophy mr-2 text-warning"></i>Top Pages</span>
                                <a href="pageviews.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <?php
                                $medals = ['gold','silver','bronze'];
                                $maxP = !empty($top_pages) ? max(1, (int)$top_pages[0]['total']) : 1;
                                foreach ($top_pages as $i => $tp):
                                    $lbl = rtrim(basename($tp['url']), '/');
                                    $lbl = preg_replace('/\.php$/i', '', $lbl);
                                    $lbl = $lbl === '' ? 'Home' : ucwords(str_replace(['-','_'], ' ', $lbl));
                                    $pct = round((int)$tp['total'] / $maxP * 100);
                                ?>
                                <div class="lb-row">
                                    <div class="lb-rank <?php echo $medals[$i] ?? ''; ?>"><?php echo $i+1; ?></div>
                                    <div class="lb-title"><span title="<?php echo htmlspecialchars($lbl, ENT_QUOTES); ?>"><?php echo htmlspecialchars($lbl); ?></span></div>
                                    <div class="lb-bar-wrap"><div class="lb-bar"><div class="lb-bar-fill bar-blue" style="width:<?php echo $pct; ?>%"></div></div></div>
                                    <div class="lb-count"><?php echo number_format((int)$tp['total']); ?></div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($top_pages)): ?><div class="p-4 text-center text-muted">No data yet</div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Top Blog Posts -->
                    <div class="col-lg-4 mb-4">
                        <div class="card lb-card shadow h-100">
                            <div class="card-header">
                                <span><i class="fas fa-fire mr-2 text-danger"></i>Top Blog Posts</span>
                                <a href="blogviews2.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <?php
                                $maxB = !empty($top_blogs) ? max(1, (int)$top_blogs[0]['total']) : 1;
                                foreach ($top_blogs as $i => $tb):
                                    $pct = round((int)$tb['total'] / $maxB * 100);
                                    $title = urldecode($tb['blog_title']);
                                ?>
                                <div class="lb-row">
                                    <div class="lb-rank <?php echo $medals[$i] ?? ''; ?>"><?php echo $i+1; ?></div>
                                    <div class="lb-title"><span title="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>"><?php echo htmlspecialchars($title); ?></span></div>
                                    <div class="lb-bar-wrap"><div class="lb-bar"><div class="lb-bar-fill bar-green" style="width:<?php echo $pct; ?>%"></div></div></div>
                                    <div class="lb-count"><?php echo number_format((int)$tb['total']); ?></div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($top_blogs)): ?><div class="p-4 text-center text-muted">No data yet</div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Content Summary + Recent Posts -->
                    <div class="col-lg-4 mb-4 d-flex flex-column" style="gap:24px;">

                        <!-- Content Counts -->
                        <div class="card cs-card shadow">
                            <div class="card-header py-3">
                                <span><i class="fas fa-layer-group mr-2 text-info"></i>Content Overview</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="cs-item">
                                    <div class="cs-dot ic-green"><i class="fas fa-pen-nib"></i></div>
                                    <div class="cs-label">Blog Posts</div>
                                    <div class="cs-count"><?php echo number_format($cnt_blog); ?></div>
                                </div>
                                <div class="cs-item">
                                    <div class="cs-dot ic-orange"><i class="fas fa-book-open"></i></div>
                                    <div class="cs-label">E-books</div>
                                    <div class="cs-count"><?php echo number_format($cnt_ebook); ?></div>
                                </div>
                                <div class="cs-item">
                                    <div class="cs-dot ic-teal"><i class="fas fa-landmark"></i></div>
                                    <div class="cs-label">KL @ A Glance Slides</div>
                                    <div class="cs-count"><?php echo number_format($cnt_klglance); ?></div>
                                </div>
                                <div class="cs-item">
                                    <div class="cs-dot ic-purple"><i class="fas fa-users"></i></div>
                                    <div class="cs-label">Email Subscribers</div>
                                    <div class="cs-count"><?php echo number_format($cnt_sub); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Blog Posts -->
                        <div class="card cs-card shadow flex-grow-1">
                            <div class="card-header py-3">
                                <span><i class="fas fa-clock mr-2 text-secondary"></i>Recent Blog Posts</span>
                            </div>
                            <div class="card-body p-0">
                                <?php foreach ($recent_blogs as $rb):
                                    $ts = $rb['blog_timestamp'] ? date('d M Y', strtotime($rb['blog_timestamp'])) : '';
                                    $rt = urldecode($rb['blog_title']);
                                ?>
                                <div class="lb-row" style="gap:10px;">
                                    <div class="cs-dot ic-green" style="width:32px;height:32px;border-radius:8px;font-size:.8rem;flex-shrink:0;"><i class="fas fa-pen"></i></div>
                                    <div style="min-width:0;flex:1;">
                                        <div style="font-size:.82rem;font-weight:600;color:#3a3b45;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?php echo htmlspecialchars($rt, ENT_QUOTES); ?>"><?php echo htmlspecialchars($rt); ?></div>
                                        <?php if ($ts): ?><div style="font-size:.7rem;color:#b7b9cc;"><?php echo $ts; ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($recent_blogs)): ?><div class="p-4 text-center text-muted">No posts yet</div><?php endif; ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; KL The Guide</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="?logout=1">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/demo/chart-area-demo.js"></script>
<script>
    document.getElementById("indexnav").classList.add('active');

    // Live clock
    function tick() {
        var d = new Date(), h = d.getHours(), m = d.getMinutes(), s = d.getSeconds();
        var ap = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12;
        var el = document.getElementById('liveclock');
        if (el) el.textContent = h + ':' + (m<10?'0':'')+m + ':' + (s<10?'0':'')+s + ' ' + ap;
    }
    tick(); setInterval(tick, 1000);

    // Count-up animation on the stat cards
    function countUp(el, target) {
        var raw = target.replace(/,/g, '');
        var num = parseInt(raw, 10);
        if (isNaN(num) || num === 0) return;
        var step = Math.ceil(num / 60), cur = 0;
        var t = setInterval(function () {
            cur += step;
            if (cur >= num) { cur = num; clearInterval(t); }
            el.textContent = cur.toLocaleString();
        }, 16);
    }
    ['sc-total','sc-blog','sc-ebook'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) countUp(el, el.textContent);
    });

    function number_format(n) {
        n = (n+'').replace(',','').replace(' ','');
        return isFinite(+n) ? (+n).toLocaleString() : '0';
    }

    // Doughnut chart
    new Chart(document.getElementById('myPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pages','Blog','E-Book'],
            datasets: [{
                data: [<?php echo (int)$totalpageviews ?>, <?php echo (int)$totalblogviews ?>, <?php echo (int)$totalebookviews ?>],
                backgroundColor: ['#4e73df','#1cc88a','#f6a623'],
                hoverBackgroundColor: ['#2e59d9','#17a673','#c27800'],
                hoverBorderColor: 'rgba(234,236,244,1)'
            }]
        },
        options: {
            maintainAspectRatio: false, legend: { display: false }, cutoutPercentage: 78,
            tooltips: { backgroundColor:'#fff', bodyFontColor:'#858796', borderColor:'#dddfeb', borderWidth:1, xPadding:12, yPadding:12, displayColors:false, caretPadding:8 }
        }
    });

    // Line chart — real 7-day data
    new Chart(document.getElementById('myAreaChart'), {
        type: 'line',
        data: {
            labels: [<?php
                $labels = []; $vals = [];
                for ($i = 6; $i >= 0; $i--) {
                    $labels[] = '"' . ($allrows[$i]['date'] ?? '') . '"';
                    $vals[]   = (int)($allrows[$i]['views'] ?? 0);
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Views', lineTension: 0.3,
                backgroundColor: 'rgba(78,115,223,0.05)',
                borderColor: 'rgba(78,115,223,1)',
                pointRadius: 3, pointBackgroundColor: 'rgba(78,115,223,1)',
                pointBorderColor: 'rgba(78,115,223,1)', pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78,115,223,1)',
                pointHoverBorderColor: 'rgba(78,115,223,1)',
                pointHitRadius: 10, pointBorderWidth: 2,
                data: [<?php echo implode(',', $vals); ?>]
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left:10, right:25, top:25, bottom:0 } },
            scales: {
                xAxes: [{ gridLines: { display:false, drawBorder:false }, ticks: { maxTicksLimit:7 } }],
                yAxes: [{ ticks: { maxTicksLimit:5, padding:10, callback: function(v){ return number_format(v); } },
                    gridLines: { color:'rgb(234,236,244)', zeroLineColor:'rgb(234,236,244)', drawBorder:false, borderDash:[2], zeroLineBorderDash:[2] } }]
            },
            legend: { display: false },
            tooltips: {
                backgroundColor:'#fff', bodyFontColor:'#858796', titleFontColor:'#6e707e',
                titleFontSize:14, titleMarginBottom:10, borderColor:'#dddfeb', borderWidth:1,
                xPadding:15, yPadding:15, displayColors:false, intersect:false, mode:'index', caretPadding:10,
                callbacks: { label: function(item, data) { return data.datasets[item.datasetIndex].label+': '+number_format(item.yLabel); } }
            }
        }
    });
</script>
</body>
</html>