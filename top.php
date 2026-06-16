<?php
// C:\travel\top.php
// Shared header included on all public pages

if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($cn)) { include_once('function.php'); }

$currentPage = basename($_SERVER['PHP_SELF']);

// === DESTINATION-SPECIFIC THEME ENGINE ===
$cinematicImage = 'images/travelimage.jpg';
$overlayColor   = 'rgba(8, 8, 16, 0.70)';

if ($currentPage == 'index.php') {
    $cinematicImage = 'images/classic.jpg';
    $overlayColor   = 'rgba(5, 10, 20, 0.65)';
} elseif ($currentPage == 'category.php') {
    $cinematicImage = 'images/beach2.jpg';
    $overlayColor   = 'rgba(0, 20, 40, 0.68)';
} elseif ($currentPage == 'subcat.php') {
    $catid = isset($_GET['catid']) ? (int)$_GET['catid'] : 0;
    if ($catid == 2) {
        $cinematicImage = 'images/boudd.jpg';
        $overlayColor   = 'rgba(20, 10, 5, 0.72)';
    } else {
        $cinematicImage = 'images/beach2.jpg';
        $overlayColor   = 'rgba(5, 10, 20, 0.68)';
    }
} elseif ($currentPage == 'package.php') {
    $subcatid = isset($_GET['subcatid']) ? (int)$_GET['subcatid'] : 0;
    if ($subcatid > 0 && isset($cn)) {
        // Prepared statement to get subcategory image
        $subcat_res = prepare_query($cn, "SELECT Pic, Catid FROM subcategory WHERE Subcatid = ?", "i", [$subcatid]);
        if ($subcat_res && mysqli_num_rows($subcat_res) > 0) {
            $subcat_row = mysqli_fetch_assoc($subcat_res);
            $cinematicImage = 'Admin/subcatimages/' . h($subcat_row['Pic']);
            $overlayColor = ($subcat_row['Catid'] == 2) ? 'rgba(20, 10, 5, 0.72)' : 'rgba(5, 15, 25, 0.68)';
        }
    } else {
        $cinematicImage = 'images/beach2.jpg';
        $overlayColor   = 'rgba(5, 10, 20, 0.68)';
    }
} elseif ($currentPage == 'enquiry.php') {
    $cinematicImage = 'images/sl.jpg';
    $overlayColor   = 'rgba(8, 5, 20, 0.72)';
} elseif ($currentPage == 'detail.php' || $currentPage == 'aboutus.php') {
    $cinematicImage = 'images/travelimage.jpg';
    $overlayColor   = 'rgba(8, 8, 16, 0.72)';
}
?>
<style>
body {
    background-image: url('<?php echo $cinematicImage; ?>') !important;
    background-size: cover !important;
    background-position: center !important;
    background-attachment: fixed !important;
    background-color: #0a0a0a !important;
    animation: cinematicPan 60s ease-in-out infinite alternate !important;
}
@keyframes cinematicPan {
    0%   { background-position: 0% 0%;   background-size: 110%; }
    33%  { background-position: 30% 20%; background-size: 120%; }
    66%  { background-position: 70% 60%; background-size: 125%; }
    100% { background-position: 100% 100%; background-size: 115%; }
}
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: radial-gradient(circle at 50% 50%, rgba(10, 15, 30, 0.25) 0%, rgba(5, 7, 12, 0.88) 100%), <?php echo $overlayColor; ?>;
    z-index: 0;
    pointer-events: none;
}
body > * { position: relative; z-index: 1; }
<?php if (isset($_GET['screenshot'])): ?>
* {
    animation: none !important;
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
}
body::before {
    background: rgba(0, 0, 0, 0.15) !important;
}
<?php endif; ?>
</style>
<script>
(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const themeParam = urlParams.get('theme');
    const savedTheme = localStorage.getItem('theme');
    if (themeParam === 'light' || (themeParam !== 'dark' && savedTheme === 'light')) {
        document.documentElement.setAttribute('data-theme', 'light');
    } else {
        document.documentElement.removeAttribute('data-theme');
    }
})();
</script>

<!-- Scroll Progress Bar -->
<div class="scroll-progress-bar" id="scrollProgress" role="progressbar" aria-label="Page scroll progress"></div>

<header id="mainHeader" role="banner">
    <div class="header-container">
        <!-- Logo -->
        <a href="index.php" class="logo-container" aria-label="VoyageQuest Home">
            <div class="logo-icon-wrapper">
                <svg class="logo-ta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polygon points="12 2 15 9 22 12 15 15 12 22 9 15 2 12 9 9" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </div>
            <span class="logo-text">Voyage<span class="logo-text-gold">Quest</span></span>
        </a>
        
        <!-- Desktop Navigation Menu -->
        <nav class="nav-menu-wrapper" aria-label="Main navigation">
            <ul class="nav-links">
                <li><a href="index.php" class="nav-item-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" <?php echo ($currentPage == 'index.php') ? 'aria-current="page"' : ''; ?>>Discover</a></li>
                <li><a href="category.php" class="nav-item-link <?php echo in_array($currentPage, ['category.php','subcat.php','package.php','detail.php','enquiry.php']) ? 'active' : ''; ?>" <?php echo in_array($currentPage, ['category.php','subcat.php','package.php']) ? 'aria-current="page"' : ''; ?>>Destinations</a></li>
                <li><a href="aboutus.php" class="nav-item-link <?php echo ($currentPage == 'aboutus.php') ? 'active' : ''; ?>" <?php echo ($currentPage == 'aboutus.php') ? 'aria-current="page"' : ''; ?>>About</a></li>
                <li><a href="index.php#plan-trip" class="nav-item-link">Contact</a></li>
            </ul>
        </nav>

        <!-- Actions -->
        <div class="header-actions">
            <a href="Admin/loginform.php" class="btn-ta-login">Admin Portal</a>
            <button id="themeToggleBtn" class="btn-theme-toggle" aria-label="Toggle light/dark theme">
                <i class="fa-solid fa-moon" aria-hidden="true"></i>
            </button>
            <!-- Hamburger Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobileNavDrawer">
                <span class="bar" aria-hidden="true"></span>
                <span class="bar" aria-hidden="true"></span>
                <span class="bar" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <nav class="mobile-nav-drawer" id="mobileNavDrawer" aria-label="Mobile navigation" hidden>
        <ul class="mobile-nav-links">
            <li><a href="index.php" class="mobile-nav-item">Discover</a></li>
            <li><a href="category.php" class="mobile-nav-item">Destinations</a></li>
            <li><a href="aboutus.php" class="mobile-nav-item">About</a></li>
            <li><a href="index.php#plan-trip" class="mobile-nav-item">Contact</a></li>
            <li><a href="Admin/loginform.php" class="mobile-btn-login">Admin Portal</a></li>
        </ul>
    </nav>
</header>

<!-- CDNs -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js" defer></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize AOS scroll animations
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 900, easing: 'ease-out-cubic', once: true, offset: 80 });
    }

    // Scroll progress bar
    const progressBar = document.getElementById("scrollProgress");
    window.addEventListener("scroll", function() {
        if (progressBar) {
            const winScroll = document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            progressBar.style.width = height > 0 ? (winScroll / height * 100) + '%' : '0%';
        }
        const header = document.getElementById("mainHeader");
        if (header) {
            header.classList.toggle("scrolled", window.scrollY > 50);
        }
    }, { passive: true });

    // Vanilla Tilt on destination cards (only on desktop)
    if (typeof VanillaTilt !== 'undefined' && window.innerWidth > 991) {
        VanillaTilt.init(document.querySelectorAll(".ta-card"), {
            max: 8, speed: 400, glare: true, "max-glare": 0.15
        });
    }

    // GSAP entrance animations (migrated to native CSS keyframes in luxury_travel.css for 100% reliability and zero-JS rendering)
    /*
    if (typeof gsap !== 'undefined') {
        if (document.querySelector('.hero-ta-title')) {
            gsap.from('.hero-ta-title', { duration: 1.2, y: 50, opacity: 0, ease: 'power4.out' });
            gsap.from('.hero-ta-subtitle', { duration: 1.2, y: 30, opacity: 0, delay: 0.3, ease: 'power4.out' });
            gsap.from('.search-pill', { duration: 1, y: 20, opacity: 0, stagger: 0.1, delay: 0.5, ease: 'back.out(1.7)' });
        }
    }
    */

    // Theme toggle
    const themeToggleBtn = document.getElementById("themeToggleBtn");
    const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector("i") : null;
    
    function updateThemeIcon(theme) {
        if (!themeIcon) return;
        themeIcon.className = theme === 'light' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        themeIcon.style.color = theme === 'light' ? '#f59e0b' : '';
    }
    
    const activeTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    updateThemeIcon(activeTheme);
    
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function() {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            if (isLight) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'dark');
                updateThemeIcon('dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                updateThemeIcon('light');
            }
        });
    }

    // Mobile hamburger menu
    const mobileMenuToggle = document.getElementById("mobileMenuToggle");
    const mobileNavDrawer = document.getElementById("mobileNavDrawer");
    
    if (mobileMenuToggle && mobileNavDrawer) {
        mobileMenuToggle.addEventListener("click", function() {
            const isOpen = this.classList.toggle("active");
            mobileNavDrawer.classList.toggle("active", isOpen);
            this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen) {
                mobileNavDrawer.removeAttribute('hidden');
            } else {
                mobileNavDrawer.setAttribute('hidden', '');
            }
        });
        
        document.addEventListener("click", function(event) {
            if (!mobileMenuToggle.contains(event.target) && !mobileNavDrawer.contains(event.target)) {
                mobileMenuToggle.classList.remove("active");
                mobileNavDrawer.classList.remove("active");
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileNavDrawer.setAttribute('hidden', '');
            }
        });
    }
});
</script>
