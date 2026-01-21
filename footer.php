<footer id="footer" class="footer">

    <div class="footer-content">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6">
                    <div class="footer-info">
                        <h3>KL The Guide</h3>
                        <p>
                            No.31-2, Block F2, Level, 2, Jalan PJU 1/42a, Dataran Prima, <br>
                            47301 Petaling Jaya, Selangor<br><br>
                            <strong>Phone:</strong>+6012-220 0622<br>
                            <strong>Email:</strong> <a class="text-reset"
                                href="mailto:enquiry@bluedale.com.my">enquiry@bluedale.com.my</a><br>
                            <a class="text-reset"
                                href="ebooklibrary.kltheguide.com.my" target="_blank">e-Book Library</a><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 footer-links">
                    <h4>Other Guides</h4>
                    <div class="row">
                        <div class="col-4 footer-links">

                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://www.klangvalley4locals.com.my/">Klang Valley 4 Locals</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://keningautheguide.com.my/">Keningau The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://www.tambunantheguide.com.my/">Tambunan The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://serembantheguide.com.my/">Seremban The Guide</a></li>
                            </ul>
                        </div>
                        <div class="col-4 footer-links">

                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a href="http://melakatheguide.com.my/">Melaka
                                        The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://www.uzbekistantheguide.com/">Uzbekistan The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="https://www.peraktheguide.com.my/">Perak The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="https://kualaselangortheguide.com.my/">Kuala Selangor The Guide</a></li>
                            </ul>
                        </div>
                        <div class="col-4 footer-links">

                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://www.taipingtheguide.com.my/">Taiping The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a href="http://www.tawautheguide.com.my">Tawau
                                        The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="https://www.huluselangortheguide.com.my/">Hulu Selangor The Guide</a></li>
                                <li><i class="bi bi-chevron-right"></i> <a
                                        href="http://kualalangattheguide.com.my/">Kuala Langat The Guide</a></li>
                            </ul>
                        </div>
                    </div>


                </div>



                <div class="col-lg-3 col-md-6 footer-newsletter">
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
      if (data.inserted && data.sent) {
        alert('Successfully subscribed! Confirmation email has been sent.');
      } else if (data.inserted && !data.sent) {
        alert('Successfully subscribed! Confirmation email is queued for delivery.');
      } else {
        alert('This email is already subscribed to our newsletter.');
      }
      e.target.reset();
    } else {
      alert(data.message || 'Subscription failed. Please try again.');
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