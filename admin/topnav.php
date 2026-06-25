<!-- ============================================================================
                                 topnav.php

     Admin top-bar partial (HTML only), included into admin pages.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
   <!-- Topbar -->
   <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>



<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">



    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <!-- <span class="badge badge-danger badge-counter">3+</span> -->
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">
                Alerts Center
            </h6>
            <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                </div>
            </a> -->
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">Decembe    r 7, 2019</div>
                    $290.29 has been deposited into your account!
                </div>
            </a>
            <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                </div>
            </a> -->
            <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> -->
        </div>
    </li>


    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
            <img class="img-profile rounded-circle"
                src="img/undraw_profile.svg">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <a class="dropdown-item" href="#">
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                Settings
            </a>
            <!-- <a class="dropdown-item" href="#">
                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                Activity Log
            </a> -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>

</ul>

<script>
(function () {
    var token = "<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>";
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form').forEach(function (form) {
            if (!form.querySelector('input[name="csrf_token"]')) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'csrf_token';
                inp.value = token;
                form.appendChild(inp);
            }
        });
    });
})();
</script>

<!-- ── Idle timeout: 30 min inactivity → redirect to login ─────────────── -->
<div id="idle-warning" style="display:none;position:fixed;top:0;left:0;right:0;z-index:9999;
     background:#c0392b;color:#fff;text-align:center;padding:14px 20px;
     font-size:15px;font-family:sans-serif;box-shadow:0 2px 6px rgba(0,0,0,.4);">
    You will be logged out in <strong id="idle-countdown">60</strong> seconds due to inactivity.&nbsp;
    <button onclick="resetIdleTimer()"
            style="margin-left:12px;padding:5px 16px;background:#fff;color:#c0392b;
                   border:none;border-radius:4px;cursor:pointer;font-weight:bold;">
        Stay logged in
    </button>
</div>

<script>
(function () {
    var IDLE_MS  = 30 * 60 * 1000;   // 30 minutes total
    var WARN_MS  =  1 * 60 * 1000;   // warning shown 1 minute before logout
    var idleTimer, warnTimer, countdownInterval;

    var banner    = document.getElementById('idle-warning');
    var countdown = document.getElementById('idle-countdown');

    function showWarning() {
        var secs = 60;
        countdown.textContent = secs;
        banner.style.display = 'block';
        countdownInterval = setInterval(function () {
            secs--;
            countdown.textContent = secs;
            if (secs <= 0) clearInterval(countdownInterval);
        }, 1000);
    }

    function hideWarning() {
        banner.style.display = 'none';
        clearInterval(countdownInterval);
    }

    window.resetIdleTimer = function () {
        clearTimeout(idleTimer);
        clearTimeout(warnTimer);
        hideWarning();
        warnTimer = setTimeout(showWarning, IDLE_MS - WARN_MS);
        idleTimer = setTimeout(function () {
            window.location.href = 'login.php?timeout=1';
        }, IDLE_MS);
    };

    ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart', 'click'].forEach(function (evt) {
        document.addEventListener(evt, resetIdleTimer, true);
    });

    resetIdleTimer(); // start the clock
})();
</script>

</nav>
<!-- End of Topbar -->
