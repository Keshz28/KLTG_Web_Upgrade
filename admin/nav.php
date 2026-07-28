<!-- ============================================================================
                                  nav.php

     Admin sidebar navigation menu (partial).

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
<!-- CSRF: the central guard in functions.php rejects any logged-in admin POST
     without a valid token. nav.php is included on every admin page, so we make
     the session token available here and auto-attach it to every POST form
     (and expose it as window.CSRF_TOKEN for AJAX calls like reorder.php). -->
<script>
    window.CSRF_TOKEN = <?php echo json_encode(csrf_token()); ?>;
    (function () {
        function addToken(form) {
            if (!form || (form.getAttribute('method') || '').toLowerCase() !== 'post') return;
            if (form.querySelector('input[name="csrf_token"]')) return;
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            input.value = window.CSRF_TOKEN || '';
            form.appendChild(input);
        }
        function injectAll() {
            document.querySelectorAll('form').forEach(addToken);
        }
        if (document.readyState !== 'loading') { injectAll(); }
        else { document.addEventListener('DOMContentLoaded', injectAll); }
        // Safety net for forms added/submitted later.
        document.addEventListener('submit', function (e) { addToken(e.target); }, true);
    })();
</script>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KLTG ADMIN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item" id="indexnav">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <!-- <div class="sidebar-heading">
                Interface
            </div> -->

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> -->

    <!-- Nav Item - Utilities Collapse Menu -->
    <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> -->

    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>


    <li class="nav-item" id="subnav">
        <a class="nav-link" href="sub.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Subscribers</span></a>
    </li>
    <li class="nav-item" id="blognav">
        <a class="nav-link" href="blogviews2.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Blog Views</span></a>
    </li>
    <li class="nav-item" id="ebooknav">
        <a class="nav-link" href="ebookviews.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>E-book Views</span></a>
    </li>
     <li class="nav-item" id="lpagenav">
        <a class="nav-link" href="landing-page.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>QR Code Landing Page</span></a>
    </li>

    <li class="nav-item" id="advertisementnav">
        <a class="nav-link" href="edit-advertisement.php">
            <i class="fas fa-fw fa-ad"></i>
            <span>Advertisement Popup</span></a>
    </li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item" id="editnav">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Edit Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>-->
                <a class="collapse-item" href="edit-index.php">Index</a>
                <a class="collapse-item" href="edit-highlights.php">Highlights</a>
                <a class="collapse-item" href="edit-klglance.php">KL @ A Glance</a>
                <a class="collapse-item" href="edit-traveltips.php">Travel Tips</a>
                <a class="collapse-item" href="edit-blog.php">Blog</a>
                <a class="collapse-item" href="edit-ebook.php">E-book</a>
                <a class="collapse-item" href="edit-merchandise.php">Merchandise</a>
                <a class="collapse-item" href="edit-explorekl.php">Explore KL</a>
                <a class="collapse-item" href="edit-beyondkl.php">Beyond KL</a>
                <a class="collapse-item" href="edit-medical-tourism.php">Medical Tourism</a>
                <a class="collapse-item" href="edit-places-to-shop.php">Places To Shop</a>
                <a class="collapse-item" href="edit-spa.php">Spa</a>
                <a class="collapse-item" href="edit-accomodation.php">Place To Stay</a>
                <a class="collapse-item" href="edit-event.php">Event</a>
                <a class="collapse-item" href="edit-voucher.php">Manage Vouchers</a>


            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <!-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> -->

    <!-- Nav Item - Tables -->
    <!-- <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

</ul>
<!-- End of Sidebar -->