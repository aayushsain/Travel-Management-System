<!-- C:\travel\bottom.php -->
<footer class="ta-footer" role="contentinfo">
    <div class="footer-inner">
        <!-- Footer Top -->
        <div class="footer-top">
            <!-- Brand Column -->
            <div class="footer-brand-col">
                <a href="index.php" class="footer-logo-link" aria-label="VoyageQuest home">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;stroke:var(--gold);" aria-hidden="true">
                        <polygon points="12 2 15 9 22 12 15 15 12 22 9 15 2 12 9 9" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <span class="footer-brand-name">Voyage<span style="color:var(--gold);">Quest</span></span>
                </a>
                <p class="footer-brand-desc">
                    Crafting extraordinary journeys for discerning travelers since 2015. Premium curated packages, expert guides, 24/7 support.
                </p>
                <div class="footer-social-links" aria-label="Social media links">
                    <a href="#" class="footer-social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="#" class="footer-social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                    <a href="#" class="footer-social-btn" aria-label="Twitter/X"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
                    <a href="#" class="footer-social-btn" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-links-col">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-link-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="category.php">Destinations</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                    <li><a href="index.php#plan-trip">Contact</a></li>
                    <li><a href="Admin/loginform.php">Admin Portal</a></li>
                </ul>
            </div>
            
            <!-- Travel Categories -->
            <div class="footer-links-col">
                <h4 class="footer-heading">Categories</h4>
                <ul class="footer-link-list">
                    <li><a href="subcat.php?catid=1">Family Tours</a></li>
                    <li><a href="subcat.php?catid=2">Religious Tours</a></li>
                    <li><a href="category.php">All Packages</a></li>
                    <li><a href="index.php#faq">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="footer-links-col">
                <h4 class="footer-heading">Contact</h4>
                <ul class="footer-link-list" style="gap: 0.85rem;">
                    <li style="display:flex;align-items:flex-start;gap:0.6rem;">
                        <i class="fa-solid fa-phone" style="color:var(--gold);margin-top:3px;font-size:0.8rem;"></i>
                        <span>+91-XXXX-XXXXXX</span>
                    </li>
                    <li style="display:flex;align-items:flex-start;gap:0.6rem;">
                        <i class="fa-solid fa-envelope" style="color:var(--gold);margin-top:3px;font-size:0.8rem;"></i>
                        <span>hello@voyagequest.com</span>
                    </li>
                    <li style="display:flex;align-items:flex-start;gap:0.6rem;">
                        <i class="fa-solid fa-clock" style="color:var(--gold);margin-top:3px;font-size:0.8rem;"></i>
                        <span>24/7 Support Available</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p style="margin:0;font-size:0.82rem;color:var(--text-gray);">
                &copy; 2026 VoyageQuest. All rights reserved. Crafted with ♥ for extraordinary travelers.
            </p>
            <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
                <a href="#" style="font-size:0.8rem;color:var(--text-gray);text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-gray)'">Privacy Policy</a>
                <a href="#" style="font-size:0.8rem;color:var(--text-gray);text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-gray)'">Terms of Service</a>
                <a href="#" style="font-size:0.8rem;color:var(--text-gray);text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-gray)'">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll back to top"
        style="position:fixed;bottom:2rem;right:2rem;width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#D8981A);color:#0A1F44;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.2rem;box-shadow:0 4px 20px rgba(244,185,66,0.35);opacity:0;transition:opacity 0.3s ease,transform 0.3s ease;z-index:999;">
    <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
</button>

<script>
// Back-to-top visibility
window.addEventListener('scroll', function() {
    const btn = document.getElementById('backToTop');
    if (btn) {
        if (window.scrollY > 500) {
            btn.style.opacity = '1';
            btn.style.transform = 'translateY(0)';
        } else {
            btn.style.opacity = '0';
            btn.style.transform = 'translateY(10px)';
        }
    }
}, { passive: true });
</script>