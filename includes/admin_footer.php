<!-- Admin Footer -->
<footer class="admin-footer-section">

    <div class="container">

        <div class="row gy-5">

            <!-- BRAND & STATS -->
            <div class="col-lg-5">

                <h2 class="footer-logo">IoT Smart Store</h2>

                <p class="footer-description">
                    Admin Panel - Manage and monitor your IoT store efficiently.
                </p>

                <!-- Histats -->
                <div class="stats-badge">
                    <i class="bi bi-graph-up"></i>
                    <span>Visitor Stats:</span>

                    <!-- IMPORTANT: only ONE id allowed -->
                    <div id="histats_counter" style="margin-top: 3px;"></div>
                </div>

            </div>

            <!-- QUICK LINKS -->
            <div class="col-lg-3 col-md-6">

                <h5 class="footer-title">Quick Links</h5>

                <ul class="footer-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="visitors.php">Visitor Analytics</a></li>
                    <li><a href="<?= BASE_URL ?>" target="_blank">View Store</a></li>
                </ul>

            </div>

            <!-- ADMIN INFO -->
            <div class="col-lg-4 col-md-6">

                <h5 class="footer-title">Admin Info</h5>

                <div class="footer-contact">
                    <div><i class="bi bi-shield-lock"></i> Secure Admin Panel</div>
                    <div><i class="bi bi-clock-history"></i> Last Login: Today</div>
                    <div><i class="bi bi-database"></i> Real-time Data Sync</div>
                </div>

            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <div class="row align-items-center">

                <div class="col-md-6 text-md-start text-center">
                    © 2026 IoT Smart Store Admin Panel
                </div>

                <div class="col-md-6 text-md-end text-center">
                    <a href="<?= PAGES_URL ?>logout.php" class="text-danger text-decoration-none">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>

            </div>
        </div>

    </div>

</footer>

<!-- Styles -->
<style>
.admin-footer-section {
    background: linear-gradient(180deg, #0f172a, #020617);
    color: #e2e8f0;
    padding: 70px 0 30px;
    margin-top: 60px;
}

.footer-logo {
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
}

.footer-description {
    color: #94a3b8;
    line-height: 1.7;
}

.footer-title {
    color: white;
    font-weight: 600;
    margin-bottom: 20px;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: #94a3b8;
    text-decoration: none;
}

.footer-links a:hover {
    color: #60a5fa;
}

.footer-contact div {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    color: #cbd5e1;
}

.footer-divider {
    border-color: rgba(255,255,255,0.08);
    margin: 40px 0 20px;
}

.footer-bottom {
    color: #94a3b8;
    font-size: 0.9rem;
}

.stats-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,5026696,4,2038,130,60,00011000']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?5026696&101" alt="free web stats" border="0"></a></noscript>
<!-- Histats.com  END  -->

<noscript>
    <img src="//sstatic1.histats.com/0.gif?5026696&101" alt="stats">
</noscript>

</body>
</html>