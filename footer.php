<?php
/* ============================================================================
 *                                footer.php
 *
 *   Shared site footer include.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
// Load active advertisement for popup
$_kltg_ad = null;
if (isset($db)) {
    $_kltg_ad_result = @mysqli_query($db, "SELECT id, image, link_url FROM advertisement WHERE is_active = 1 LIMIT 1");
    if ($_kltg_ad_result && mysqli_num_rows($_kltg_ad_result) > 0) {
        $_kltg_ad = mysqli_fetch_assoc($_kltg_ad_result);
    }
}
?>

<?php if ($_kltg_ad && !empty($_kltg_ad['image'])): ?>
<!-- Advertisement Popup -->
<div id="kltg-ad-overlay" aria-modal="true" role="dialog" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    z-index: 99999;
    align-items: center;
    justify-content: center;">
    <div id="kltg-ad-box" style="
        position: relative;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.4);
        max-width: 520px;
        width: 90%;
        overflow: hidden;">
        <!-- Close bar -->
        <div style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            background: #222;
            color: #fff;">
            <span style="font-size: 12px; letter-spacing: 0.5px; opacity: 0.8;">Advertisement</span>
            <button id="kltg-ad-close" aria-label="Close advertisement" style="
                background: none;
                color: #fff;
                border: none;
                font-size: 22px;
                line-height: 1;
                cursor: pointer;
                padding: 0 2px;">
                &times;
            </button>
        </div>
        <!-- Ad image -->
        <a id="kltg-ad-link" href="<?php echo htmlspecialchars($_kltg_ad['link_url'], ENT_QUOTES); ?>" target="_blank" rel="noopener noreferrer" style="display:block;">
            <img src="assets/img/advertisement/<?php echo htmlspecialchars($_kltg_ad['image'], ENT_QUOTES); ?>"
                 alt="Advertisement"
                 style="width:100%; display:block;">
        </a>
    </div>
</div>
<script>
(function () {
    var storageKey = 'kltg_ad_shown_<?php echo (int)$_kltg_ad['id']; ?>';
    if (!sessionStorage.getItem(storageKey)) {
        setTimeout(function () {
            var overlay = document.getElementById('kltg-ad-overlay');
            if (overlay) {
                overlay.style.display = 'flex';
                sessionStorage.setItem(storageKey, '1');
            }
        }, 1500);
    }

    function closeAd() {
        var overlay = document.getElementById('kltg-ad-overlay');
        if (overlay) overlay.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var closeBtn = document.getElementById('kltg-ad-close');
        var overlay  = document.getElementById('kltg-ad-overlay');
        var adLink   = document.getElementById('kltg-ad-link');

        if (closeBtn) closeBtn.addEventListener('click', closeAd);
        if (overlay)  overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeAd();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAd();
        });

        // Track ad clicks (fire-and-forget, opens in new tab so page stays)
        if (adLink) {
            adLink.addEventListener('click', function () {
                navigator.sendBeacon
                    ? navigator.sendBeacon('admin/track_ad_click.php')
                    : fetch('admin/track_ad_click.php', { method: 'POST' });
            });
        }
    });
}());
</script>
<?php endif; ?>

<footer id="footer" class="footer">

    <div class="footer-content">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="footer-info">
                        <h3>KL The Guide</h3>
                        <p>
                            No.31-2, Block F2, Level, 2, Jalan PJU 1/42a, Dataran Prima, <br>
                            47301 Petaling Jaya, Selangor<br><br>
                            <strong>Phone:</strong>+6012-220 0622<br>
                            <strong>Email:</strong> <a class="text-reset"
                                href="mailto:enquiry@bluedale.com.my">enquiry@bluedale.com.my</a><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 footer-links">
                    <h4>Other Guides</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="http://www.klangvalley4locals.com.my/">Klang Valley 4 Locals</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Enter your email to subscribe for any news or updates from KL The Guide</p>
                    <form id="subscribe-form">
                        <input type="email" name="email" id="emailsubscribe" required><input type="submit"
                            value="Subscribe" name="subscribe">
                    </form>
                    <div class="row mt-2">

                    <div class="col-6 footer-links">
                            <a href="advertisewithus.php" class="text-reset"><i class="bi bi-newspaper"></i> Advertise With Us</a>
                    </div>
                    <div class="col-6 footer-links">
                            <a href="contribute.php" class="text-reset"><i class="bi bi-pencil-square"></i> Contribute An Article</a>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-legal text-center">
        <div
            class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

            <div class="d-flex flex-column align-items-center align-items-lg-start">
                <div class="copyright">
                    &copy;2023 Copyright <strong><span>Bluedale Group Of Companies</span></strong>. Designed, developed
                    & maintained by Bluedale Group Of Companies
                </div>
                <div class="credits">

                    Designed by <a href="https://bluedale.com.my/">Bluedale</a>
                </div>
            </div>

            <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
                <a href="https://www.facebook.com/kltheguide/" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/kltheguide/" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.tiktok.com/@kltheguide" class="tiktok"><i class="bi bi-tiktok"></i></a>
                <a href="https://twitter.com/kltheguide" class="twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="https://www.youtube.com/watch?v=EDXEmnnwdr0&t=5s" class="youtube"><i
                        class="bi bi-youtube"></i></a>
            </div>

        </div>
    </div>

</footer><!-- End Footer -->

<!-- KL Travel Concierge Chatbot -->
<link rel="stylesheet" href="assets/css/chatbot.css">
<script src="assets/js/chatbot.js" defer></script>

<script>
document.querySelector('#subscribe-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const submitBtn = e.target.querySelector('input[type="submit"]');
  const originalText = submitBtn.value;
  submitBtn.value = 'Processing...';
  submitBtn.disabled = true;
  
  try {
    const fd = new FormData(e.target);
    const res = await fetch('admin/sub_handler.php?action=subscribe', {
      method: 'POST',
      body: fd
    });
    
    const data = await res.json().catch(() => ({}));
    
    if (data.ok) {
      if (data.status === 'duplicate') {
        alert('This email is already subscribed to our newsletter.');
      } else if (data.status === 'verification_sent') {
        alert('Almost there! Please check your inbox and click the confirmation link to complete your subscription.');
        e.target.reset();
      } else {
        alert('Please check your inbox and click the confirmation link to complete your subscription.');
        e.target.reset();
      }
    } else {
      alert(data.message || data.error || 'Subscription failed. Please try again.');
    }
  } catch (error) {
    alert('An error occurred. Please try again later.');
    console.error('Subscription error:', error);
  } finally {
    submitBtn.value = originalText;
    submitBtn.disabled = false;
  }
});
</script>