<?php
// C:\travel\index.php
// VoyageQuest Homepage — Premium Travel Platform

include('function.php');

// Handle contact form submission using prepared statement
if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name    = trim($_POST["t1"] ?? '');
    $phno    = trim($_POST["t2"] ?? '');
    $email   = filter_var(trim($_POST["t3"] ?? ''), FILTER_VALIDATE_EMAIL) ? trim($_POST["t3"]) : '';
    $message = trim($_POST["t4"] ?? '');
    
    if (!$email) {
        echo "<script>alert('Please enter a valid email address.');</script>";
    } elseif (strlen($name) < 2) {
        echo "<script>alert('Please enter a valid name.');</script>";
    } else {
        $ok = prepare_exec($cn,
            "INSERT INTO contactus(Name,Phno,Email,Message) VALUES(?,?,?,?)",
            "ssss",
            [$name, $phno, $email, $message]
        );
        if ($ok) {
            echo "<script>alert('Thank you for contacting us! We will get back to you shortly.');</script>";
        }
    }
}

// Fetch featured packages for homepage cards
$featured_result = prepare_query($cn,
    "SELECT p.Packid, p.Packname, p.Packprice, p.Pic1, p.Detail, c.Cat_name, s.Subcatname
     FROM package p
     JOIN category c ON p.Category = c.Cat_id
     JOIN subcategory s ON p.Subcategory = s.Subcatid
     ORDER BY p.Packid DESC LIMIT 6",
    "", []
);
$featured_packages = [];
if ($featured_result) {
    while ($row = mysqli_fetch_assoc($featured_result)) {
        $featured_packages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyageQuest | Luxury Tour &amp; Travel Planner — Discover the World in Style</title>
    <meta name="description" content="VoyageQuest offers curated luxury travel packages, family tours, and religious pilgrimages. Compare prices, read reviews, and book premium vacation getaways tailored to you.">
    <meta name="keywords" content="luxury travel, family tours, religious tours, vacation packages, travel planner, holiday packages">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="VoyageQuest | Luxury Tour & Travel Planner">
    <meta property="og:description" content="Curated luxury travel packages for discerning travelers. Family tours, religious pilgrimages, and exclusive getaways.">
    <meta property="og:image" content="images/classic.jpg">
    <meta property="og:site_name" content="VoyageQuest">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="VoyageQuest | Luxury Travel">
    <meta name="twitter:description" content="Premium curated travel packages tailored to your comfort.">
    <meta name="twitter:image" content="images/classic.jpg">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "VoyageQuest",
        "description": "Premium travel agency offering curated luxury packages",
        "url": "http://localhost/travel/",
        "telephone": "+91-XXXXXXXXXX",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "25000"
        }
    }
    </script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
</head>
<body>

<?php include('top.php'); ?>

<!-- ===========================
     HERO SECTION
     =========================== -->
<section class="hero-ta" id="home" aria-label="Homepage hero">
    <!-- Airplane animation -->
    <div class="airplane-route" aria-hidden="true">
        <svg class="airplane-svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l4-1 4 1v-1.5L14 19v-5.5L21 16z"/>
        </svg>
    </div>
    
    <div class="hero-ta-content">
        <div style="display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(244,185,66,0.15); border: 1px solid rgba(244,185,66,0.3); border-radius: 30px; padding: 0.4rem 1.2rem; margin-bottom: 2rem; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gold);">
            ✨ Trusted by 25,000+ Travelers Worldwide
        </div>
        
        <h1 class="hero-ta-title">Craft Extraordinary<br>Journeys Across the Globe</h1>
        <p class="hero-ta-subtitle">Compare prices, read traveler reviews, and book premium vacation getaways tailored to your comfort — explore the world with elegance and confidence.</p>
        
        <!-- CTA Buttons -->
        <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
            <a href="category.php" class="btn-ta btn-ta-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 50px;">
                <i class="fa-solid fa-compass" style="margin-right: 0.6rem; color: var(--gold);"></i>Explore Destinations
            </a>
            <a href="#plan-trip" class="btn-ta btn-ta-secondary" style="font-size: 1.1rem; padding: 1rem 2.5rem; border-radius: 50px;">
                <i class="fa-solid fa-paper-plane" style="margin-right: 0.6rem; color: var(--gold);"></i>Plan My Trip
            </a>
        </div>
        
        <!-- Category Navigation Pills -->
        <div class="search-pills-container" role="navigation" aria-label="Travel categories">
            <a href="category.php" class="search-pill active" aria-label="Explore All Packages">
                <i class="fa-solid fa-suitcase-rolling" style="font-size: 1.2rem;"></i>
                <span>Explore Packages</span>
            </a>
        </div>
    </div>
</section>

<!-- ===========================
     STATISTICS / COUNTERS
     =========================== -->
<section class="ta-container" id="stats" aria-label="Statistics">
    <div class="stats-showcase" data-aos="fade-up">
        <div class="stat-showcase-grid">
            <div class="stat-showcase-item">
                <div class="stat-showcase-number counter-animate" data-target="25000" data-suffix="+">0</div>
                <div class="stat-showcase-label">Happy Travelers</div>
            </div>
            <div class="stat-showcase-item">
                <div class="stat-showcase-number counter-animate" data-target="150" data-suffix="+">0</div>
                <div class="stat-showcase-label">Destinations</div>
            </div>
            <div class="stat-showcase-item">
                <div class="stat-showcase-number" style="color: var(--gold);">4.9<span style="font-size: 1.5rem; font-weight: 400;">/5</span></div>
                <div class="stat-showcase-label">Average Rating</div>
            </div>
            <div class="stat-showcase-item">
                <div class="stat-showcase-number counter-animate" data-target="10" data-suffix="+">0</div>
                <div class="stat-showcase-label">Years Experience</div>
            </div>
        </div>
    </div>
</section>

<!-- ===========================
     ABOUT SECTION (Bento Grid)
     =========================== -->
<section class="ta-container" id="about" aria-label="About VoyageQuest">
    <h2 class="ta-section-title" data-aos="fade-up">About <span>VoyageQuest</span></h2>
    <p style="color: var(--text-gray); max-width: 600px; margin-bottom: 3rem; font-size: 1.05rem; line-height: 1.8;" data-aos="fade-up" data-aos-delay="100">
        We are a premium travel consultancy dedicated to crafting extraordinary journeys. From spiritual pilgrimages to luxury family holidays, every experience is meticulously curated.
    </p>
    <div class="bento-grid">
        <div class="bento-item" data-aos="fade-up" data-aos-delay="100">
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--gold); margin-bottom: 0.75rem;">Our Vision</h3>
                <p style="color: var(--text-gray); font-size: 0.95rem; line-height: 1.7;">To make travel accessible, secure, and unforgettable — creating bespoke journeys that connect people with local cultures and nature's finest landscapes.</p>
            </div>
            <div style="font-size: 2.5rem; align-self: flex-end; margin-top: 1rem;">🌍</div>
        </div>
        <div class="bento-item" data-aos="fade-up" data-aos-delay="200">
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 0.75rem;">Our Mission</h3>
                <p style="color: var(--text-gray); font-size: 0.95rem; line-height: 1.7;">To deliver curated travel experiences that support local communities, preserve cultural heritage, and offer world-class luxury accommodations to every traveler.</p>
            </div>
            <div style="font-size: 2.5rem; align-self: flex-end; margin-top: 1rem;">🧭</div>
        </div>
        <div class="bento-item" data-aos="fade-up" data-aos-delay="300">
            <div style="font-size: 2.5rem; color: var(--gold); margin-bottom: 1rem;">🗺️</div>
            <div>
                <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem;">Curated Itineraries</h3>
                <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.7;">We partner exclusively with luxury resorts, premium air carriers, and expert local guides for an unparalleled experience.</p>
            </div>
        </div>
        <div class="bento-item" data-aos="fade-up" data-aos-delay="400">
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--gold); margin-bottom: 0.75rem;">Unforgettable Journeys</h3>
                <p style="color: var(--text-gray); font-size: 0.95rem; line-height: 1.7;">Your experience is our priority. We partner exclusively with certified operators, 24/7 helpline services, and premium transport routes.</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: 1rem;">
                <a href="aboutus.php" class="btn-ta btn-ta-secondary" style="font-size: 0.85rem; padding: 0.5rem 1.2rem; border-radius: 30px;">Read More</a>
                <span style="font-size: 2rem;">✈️</span>
            </div>
        </div>
    </div>
</section>

<!-- ===========================
     WHY CHOOSE US
     =========================== -->
<section class="ta-container" id="why-us" aria-label="Why Choose VoyageQuest">
    <h2 class="ta-section-title" style="text-align: center;" data-aos="fade-up">Why Choose <span>Us</span></h2>
    <p style="text-align: center; color: var(--text-gray); max-width: 550px; margin: 0 auto 4rem; font-size: 1rem; line-height: 1.8;" data-aos="fade-up" data-aos-delay="100">
        We go beyond booking — we craft experiences that stay with you for a lifetime.
    </p>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <?php
        $why_items = [
            ['🏆', '#F4B942', 'Award-Winning Service', "Recognized as one of India's top travel consultancies for 5 consecutive years."],
            ['🔒', '#22c55e', 'Secure Booking', "Your personal data and payments are protected with bank-grade encryption."],
            ['🌐', '#0ea5e9', 'Global Network', "Access to 150+ destinations with hand-picked partners across 40+ countries."],
            ['💬', '#a78bfa', '24/7 Support', "Our expert travel advisors are available around the clock, every day of the year."],
            ['💎', '#F4B942', 'Luxury Standards', "Every hotel, resort, and guide partner meets our strict 4.5+ quality threshold."],
            ['♻️', '#34d399', 'Sustainable Travel', "We support eco-tourism and responsible travel practices for a better planet."],
        ];
        foreach ($why_items as $i => $item):
        ?>
        <div class="why-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100 + 100; ?>"
             style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-light); border-radius: 20px; padding: 2rem; transition: all 0.4s ease; position: relative; overflow: hidden;">
            <div style="font-size: 2.2rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: <?php echo $item[1]; ?>15; border-radius: 16px; border: 1px solid <?php echo $item[1]; ?>30;">
                <?php echo $item[0]; ?>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-white);"><?php echo $item[2]; ?></h3>
            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.7; margin: 0;"><?php echo $item[3]; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===========================
     FEATURED PACKAGES / GALLERY
     =========================== -->
<section class="ta-container" id="gallery" aria-label="Featured packages">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="ta-section-title" style="margin-bottom: 0.5rem;" data-aos="fade-up">
                Featured <span>Packages</span>
            </h2>
            <p style="color: var(--text-gray); font-size: 0.95rem;" data-aos="fade-up" data-aos-delay="100">
                Hand-picked luxury experiences for discerning travelers
            </p>
        </div>
        <a href="category.php" class="btn-ta btn-ta-secondary" data-aos="fade-up" style="padding: 0.7rem 1.5rem; border-radius: 30px; white-space: nowrap;">
            View All Packages →
        </a>
    </div>

    <?php if (!empty($featured_packages)): ?>
    <div class="ta-grid-3" style="margin-top: 3rem;">
        <?php
        $gallery_images = ['z1.jpg', 'z2.jpg', 'z3.jpg', 'z4.jpg', 'z5.jpg', 'z6.jpg'];
        $gallery_titles = ['Tropical Escapes', 'Historic Monuments', 'Alpine Adventures', 'Urban Discoveries', 'Cultural Journeys', 'Desert Safaris'];
        $ratings = [5, 5, 4, 5, 4, 5];
        $review_counts = ['2,340', '12,500', '980', '4,210', '1,720', '3,550'];
        
        foreach ($featured_packages as $i => $pkg):
            $pkg_rating  = ($pkg['Packid'] * 5) % 2 == 0 ? 5 : 4;
            $pkg_reviews = number_format(($pkg['Packid'] * 218) + 84);
        ?>
        <article class="ta-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100; ?>" aria-label="<?php echo h($pkg['Packname']); ?>">
            <div class="ta-card-img-wrapper">
                <img src="Admin/packimages/<?php echo h($pkg['Pic1']); ?>"
                     alt="<?php echo h($pkg['Packname']); ?>"
                     class="ta-card-img"
                     loading="lazy"
                     onerror="this.src='images/<?php echo $gallery_images[$i % 6]; ?>'">
                <!-- Price Tag -->
                <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(10,31,68,0.9); backdrop-filter: blur(10px); border: 1px solid rgba(244,185,66,0.3); border-radius: 20px; padding: 0.35rem 0.9rem; font-size: 0.85rem; font-weight: 800; color: var(--gold); z-index: 2;">
                    ₹<?php echo number_format((double)$pkg['Packprice']); ?>
                </div>
                <!-- Category Tag -->
                <div style="position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); border-radius: 20px; padding: 0.3rem 0.8rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.9); z-index: 2;">
                    <?php echo h($pkg['Cat_name']); ?>
                </div>
            </div>
            <div class="ta-card-body">
                <p style="font-size: 0.75rem; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 0.4rem;">
                    📍 <?php echo h($pkg['Subcatname']); ?>
                </p>
                <h3 class="ta-card-title"><?php echo h($pkg['Packname']); ?></h3>
                
                <!-- Rating -->
                <div class="rating-container" style="margin: 0.75rem 0;">
                    <div class="rating-bubbles">
                        <?php for ($j = 1; $j <= 5; $j++): ?>
                        <span class="bubble <?php echo ($j <= $pkg_rating) ? 'filled' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-count"><?php echo $pkg_reviews; ?> reviews</span>
                </div>
                
                <p class="ta-card-text"><?php echo h(substr($pkg['Detail'], 0, 100)) . (strlen($pkg['Detail']) > 100 ? '...' : ''); ?></p>
                
                <div class="ta-card-footer">
                    <a href="detail.php?pid=<?php echo (int)$pkg['Packid']; ?>" 
                       class="btn-ta btn-ta-primary" 
                       style="width: 100%;"
                       aria-label="View details for <?php echo h($pkg['Packname']); ?>">
                        View Details
                    </a>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Fallback: Static gallery cards when no DB packages available -->
    <div class="ta-grid-3" style="margin-top: 3rem;">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <article class="ta-card" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100; ?>">
            <div class="ta-card-img-wrapper">
                <img src="images/<?php echo $gallery_images[$i]; ?>" 
                     alt="<?php echo $gallery_titles[$i]; ?>" 
                     class="ta-card-img"
                     loading="lazy">
            </div>
            <div class="ta-card-body">
                <h3 class="ta-card-title"><?php echo $gallery_titles[$i]; ?></h3>
                <div class="rating-container" style="margin: 0.75rem 0;">
                    <div class="rating-bubbles">
                        <?php for ($j = 1; $j <= 5; $j++): ?>
                        <span class="bubble <?php echo ($j <= $ratings[$i]) ? 'filled' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-count"><?php echo $review_counts[$i]; ?> reviews</span>
                </div>
                <p class="ta-card-text">Embark on a signature luxury escape with fully arranged accommodations, expert local guides, and top-tier amenities.</p>
                <div class="ta-card-footer">
                    <a href="category.php" class="btn-ta btn-ta-primary" style="width: 100%;">View Deals</a>
                </div>
            </div>
        </article>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</section>

<!-- ===========================
     TRAVEL CATEGORIES
     =========================== -->
<section class="ta-container" id="categories" aria-label="Travel categories">
    <h2 class="ta-section-title" style="text-align: center;" data-aos="fade-up">Explore by <span>Category</span></h2>
    <p style="text-align: center; color: var(--text-gray); margin-bottom: 3rem; font-size: 1rem;" data-aos="fade-up" data-aos-delay="100">
        Whatever journey you seek, we have the perfect itinerary
    </p>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <a href="subcat.php?catid=1" class="category-feature-card" data-aos="fade-right"
           style="display: block; border-radius: 24px; overflow: hidden; position: relative; min-height: 320px; text-decoration: none; transition: all 0.4s ease;">
            <img src="images/beach3.jpg" alt="Family Tours" loading="lazy"
                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; transition: transform 0.6s ease;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(5,10,20,0.95) 0%, rgba(5,10,20,0.3) 60%, transparent 100%);"></div>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold); margin-bottom: 0.5rem; font-weight: 700;">Category</p>
                <h3 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; line-height: 1.2;">Family<br>Tours</h3>
                <p style="color: rgba(255,255,255,0.75); font-size: 0.9rem; margin-bottom: 1rem;">Beach resorts, mountain retreats, and adventure parks designed for families.</p>
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(244,185,66,0.2); border: 1px solid rgba(244,185,66,0.4); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; color: var(--gold);">
                    Explore →
                </span>
            </div>
        </a>
        
        <a href="subcat.php?catid=2" class="category-feature-card" data-aos="fade-left"
           style="display: block; border-radius: 24px; overflow: hidden; position: relative; min-height: 320px; text-decoration: none; transition: all 0.4s ease;">
            <img src="images/boudd.jpg" alt="Religious Tours" loading="lazy"
                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; transition: transform 0.6s ease;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(20,10,5,0.95) 0%, rgba(20,10,5,0.3) 60%, transparent 100%);"></div>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #f97316; margin-bottom: 0.5rem; font-weight: 700;">Category</p>
                <h3 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; line-height: 1.2;">Religious<br>Tours</h3>
                <p style="color: rgba(255,255,255,0.75); font-size: 0.9rem; margin-bottom: 1rem;">Sacred pilgrimage routes, temple tours, and spiritual retreats across India and beyond.</p>
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(249,115,22,0.2); border: 1px solid rgba(249,115,22,0.4); padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; color: #f97316;">
                    Explore →
                </span>
            </div>
        </a>
    </div>
</section>

<!-- ===========================
     TESTIMONIALS
     =========================== -->
<section class="ta-container" id="testimonials" aria-label="Customer testimonials">
    <h2 class="ta-section-title" style="text-align: center;" data-aos="fade-up">What Travelers <span>Say</span></h2>
    <p style="text-align: center; color: var(--text-gray); margin-bottom: 4rem; font-size: 1rem;" data-aos="fade-up" data-aos-delay="100">
        Real stories from real adventurers who trusted VoyageQuest
    </p>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php
        $testimonials = [
            ['Priya Sharma', 'Family Tour to Canada', 'Our Canada family holiday was absolutely magical. The team at VoyageQuest took care of every detail — hotels, flights, guided tours. Our kids still talk about it!', 5, 'PS'],
            ['Rahul Mehta', 'Religious Tour — Haridwar', 'The Haridwar spiritual tour was a deeply moving experience. VoyageQuest found us the perfect accommodations near the ghats. Highly recommended for pilgrims.', 5, 'RM'],
            ['Ananya Kapoor', 'Italy Family Holiday', 'Italy exceeded every expectation. The itinerary was perfectly balanced between culture, food, and family fun. VoyageQuest made it effortless and luxurious.', 5, 'AK'],
        ];
        foreach ($testimonials as $i => $t):
        ?>
        <div class="ta-detail-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 150; ?>" 
             style="position: relative; border-radius: 24px; padding: 2rem;">
            <!-- Quote mark -->
            <div style="position: absolute; top: -0.5rem; left: 1.5rem; font-size: 4rem; color: var(--gold); opacity: 0.2; font-family: Georgia, serif; line-height: 1;">"</div>
            
            <!-- Stars -->
            <div style="display: flex; gap: 0.25rem; margin-bottom: 1rem;">
                <?php for ($s = 0; $s < $t[3]; $s++): ?>
                <span style="color: var(--gold); font-size: 1rem;">★</span>
                <?php endfor; ?>
            </div>
            
            <p style="color: var(--text-gray); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem; font-style: italic;">
                "<?php echo $t[2]; ?>"
            </p>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), #D8981A); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; color: #0a1f44; flex-shrink: 0;">
                    <?php echo $t[4]; ?>
                </div>
                <div>
                    <p style="font-weight: 800; font-size: 0.95rem; margin: 0; color: var(--text-white);"><?php echo $t[0]; ?></p>
                    <p style="font-size: 0.8rem; color: var(--gold); margin: 0; font-weight: 600;"><?php echo $t[1]; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===========================
     FAQ SECTION
     =========================== -->
<section class="ta-container" id="faq" aria-label="Frequently asked questions">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2 class="ta-section-title" style="text-align: center;" data-aos="fade-up">Frequently Asked <span>Questions</span></h2>
        <p style="text-align: center; color: var(--text-gray); margin-bottom: 3rem; font-size: 1rem;" data-aos="fade-up" data-aos-delay="100">
            Everything you need to know before your journey
        </p>
        
        <div class="faq-accordion" data-aos="fade-up" data-aos-delay="200">
            <?php
            $faqs = [
                ['How do I book a package?', 'Browse our packages, click "View Details", and then click "Reserve Your Journey". Fill in your travel details and our team will contact you within 24 hours with a customized quote and booking confirmation.'],
                ['Are flights included in the package price?', 'Some packages include round-trip airfare while others are land-only. Each package clearly states what is included. Our team will clarify all inclusions during the consultation call.'],
                ['Can I customize the itinerary?', 'Absolutely. All our packages are flexible and can be tailored to your specific needs, preferences, travel dates, and budget. Just mention your requirements in the enquiry form.'],
                ['What is the cancellation policy?', 'Our standard cancellation policy allows free cancellation up to 30 days before the travel date. For cancellations within 30 days, a partial refund applies. Terms vary by package.'],
                ['Is travel insurance included?', 'Travel insurance is not included by default but is highly recommended. We can arrange comprehensive travel insurance as an add-on for a nominal fee. Ask your travel advisor.'],
                ['How do I make payment?', 'We accept bank transfers, UPI, credit/debit cards, and EMI options. No payment is required at the enquiry stage — we only collect deposits after finalizing your itinerary.'],
            ];
            foreach ($faqs as $i => $faq):
            ?>
            <div class="faq-item" style="border-bottom: 1px solid var(--border-light); padding: 1.5rem 0;">
                <button class="faq-trigger" 
                        onclick="toggleFAQ(this)"
                        aria-expanded="false"
                        id="faq-btn-<?php echo $i; ?>"
                        aria-controls="faq-content-<?php echo $i; ?>"
                        style="width: 100%; background: none; border: none; padding: 0; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 1rem; text-align: left;">
                    <span style="font-weight: 700; font-size: 1rem; color: var(--text-white); line-height: 1.4;"><?php echo $faq[0]; ?></span>
                    <span class="faq-icon" style="color: var(--gold); font-size: 1.2rem; flex-shrink: 0; transition: transform 0.3s ease;">+</span>
                </button>
                <div class="faq-content" id="faq-content-<?php echo $i; ?>"
                     style="max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease;" 
                     role="region" aria-labelledby="faq-btn-<?php echo $i; ?>">
                    <p style="color: var(--text-gray); font-size: 0.95rem; line-height: 1.8; margin: 0; padding-top: 1rem;"><?php echo $faq[1]; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===========================
     CONTACT / PLAN YOUR TRIP
     =========================== -->
<section class="ta-container" id="plan-trip" aria-label="Plan your trip contact form">
    <h2 class="ta-section-title" style="text-align: center;" data-aos="fade-up">Plan Your <span>Trip</span></h2>
    <p style="text-align: center; color: var(--text-gray); margin-bottom: 3rem; max-width: 600px; margin-inline: auto; font-size: 1rem; line-height: 1.7;" data-aos="fade-up" data-aos-delay="100">
        Our travel experts will help you design the perfect journey. Tell us your dream destination and we'll handle everything else.
    </p>
    
    <div class="ta-form-card" data-aos="zoom-in">
        <form method="post" id="contactForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="contact-form-grid">
                <div class="ta-form-group">
                    <label class="ta-form-label" for="t1">Your Name *</label>
                    <input class="ta-form-control" id="t1" name="t1" 
                           placeholder="Enter Your Name" type="text" 
                           required minlength="2" maxlength="50"
                           autocomplete="name">
                </div>
                <div class="ta-form-group">
                    <label class="ta-form-label" for="t2">Contact Number *</label>
                    <input class="ta-form-control" id="t2" name="t2" 
                           placeholder="Enter Contact No" type="tel" 
                           required pattern="[0-9]{10,12}" 
                           title="Please enter a 10-12 digit phone number"
                           autocomplete="tel">
                </div>
            </div>
            
            <div class="ta-form-group">
                <label class="ta-form-label" for="t3">Email Address *</label>
                <input class="ta-form-control" id="t3" name="t3" 
                       placeholder="Enter Email Address" type="email" 
                       required autocomplete="email">
            </div>
            
            <div class="ta-form-group">
                <label class="ta-form-label" for="t4">Message / Travel Requirements *</label>
                <textarea class="ta-form-control" id="t4" name="t4" 
                          placeholder="Tell us about your dream destination, travel dates, group size, budget, and any special requirements..."
                          required rows="4"></textarea>
            </div>
            
            <button class="btn-ta btn-ta-primary" 
                    style="width: 100%; padding: 1rem; border-radius: 30px; font-size: 1.05rem; margin-top: 1rem;" 
                    type="submit" name="sbmt"
                    aria-label="Send trip planning message">
                ✈ Send Message — Get Free Quote
            </button>
            
            <p style="text-align: center; color: var(--text-gray); font-size: 0.8rem; margin-top: 1rem;">
                🔒 Your information is secure and will never be shared with third parties.
            </p>
        </form>
    </div>
</section>

<!-- ===========================
     NEWSLETTER SECTION
     =========================== -->
<section class="ta-container" id="newsletter" aria-label="Newsletter signup">
    <div style="background: linear-gradient(135deg, rgba(244,185,66,0.08) 0%, rgba(10,31,68,0.4) 100%); border: 1px solid rgba(244,185,66,0.2); border-radius: 32px; padding: 4rem 3rem; text-align: center; position: relative; overflow: hidden;" data-aos="fade-up">
        <!-- Background decoration -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(244,185,66,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(14,165,233,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 1;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">📬</div>
            <h2 style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: -0.5px;">
                Get <span style="color: var(--gold);">Exclusive</span> Travel Deals
            </h2>
            <p style="color: var(--text-gray); max-width: 500px; margin: 0 auto 2rem; font-size: 1rem; line-height: 1.7;">
                Subscribe to our newsletter for early access to flash sales, destination guides, and curated travel inspiration.
            </p>
            
            <form class="newsletter-form" onsubmit="handleNewsletter(event)" 
                  style="display: flex; max-width: 480px; margin: 0 auto; gap: 0.75rem; flex-wrap: wrap;">
                <input type="email" 
                       placeholder="Enter your email address" 
                       required
                       style="flex: 1; min-width: 220px; padding: 0.9rem 1.5rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 40px; color: var(--text-white); font-size: 0.95rem; outline: none; font-family: var(--font-sans);"
                       onfocus="this.style.borderColor='rgba(244,185,66,0.4)'"
                       onblur="this.style.borderColor='rgba(255,255,255,0.12)'"
                       id="newsletter-email"
                       aria-label="Email address for newsletter">
                <button type="submit" 
                        class="btn-ta btn-ta-primary" 
                        style="border-radius: 40px; padding: 0.9rem 1.8rem; white-space: nowrap;"
                        aria-label="Subscribe to newsletter">
                    Subscribe
                </button>
            </form>
            
            <p id="newsletter-success" style="display: none; color: #22c55e; margin-top: 1rem; font-weight: 600;">
                ✅ You're subscribed! Watch your inbox for exclusive deals.
            </p>
            <p style="font-size: 0.8rem; color: var(--text-gray); margin-top: 1rem; opacity: 0.7;">
                No spam, ever. Unsubscribe anytime.
            </p>
        </div>
    </div>
</section>

<?php include('bottom.php'); ?>

<style>
/* FAQ & Contact Grid responsive */
@media (max-width: 640px) {
    .contact-form-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Category feature card hover effect */
.category-feature-card:hover img {
    transform: scale(1.06);
}

/* Scroll-to section smooth behavior */
html { scroll-behavior: smooth; }

/* Stats showcase */
.stats-showcase {
    background: linear-gradient(135deg, rgba(244,185,66,0.06) 0%, rgba(10,31,68,0.3) 100%);
    border: 1px solid rgba(244,185,66,0.15);
    border-radius: 28px;
    padding: 3rem 2rem;
}
.stat-showcase-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    text-align: center;
}
.stat-showcase-number {
    font-family: var(--font-serif);
    font-size: 3rem;
    font-weight: 900;
    color: var(--gold);
    line-height: 1;
    margin-bottom: 0.5rem;
    letter-spacing: -1px;
}
.stat-showcase-label {
    font-size: 0.85rem;
    color: var(--text-gray);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
}
@media (max-width: 768px) {
    .stat-showcase-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .stat-showcase-number { font-size: 2.2rem; }
}
@media (max-width: 400px) {
    .stat-showcase-grid { grid-template-columns: 1fr 1fr; gap: 1rem; }
}
</style>

<script>
// FAQ accordion
function toggleFAQ(btn) {
    const content = document.getElementById(btn.getAttribute('aria-controls'));
    const icon = btn.querySelector('.faq-icon');
    const isOpen = btn.getAttribute('aria-expanded') === 'true';
    
    // Close all others
    document.querySelectorAll('.faq-trigger').forEach(b => {
        if (b !== btn) {
            b.setAttribute('aria-expanded', 'false');
            document.getElementById(b.getAttribute('aria-controls')).style.maxHeight = '0';
            b.querySelector('.faq-icon').textContent = '+';
            b.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
        }
    });
    
    if (isOpen) {
        btn.setAttribute('aria-expanded', 'false');
        content.style.maxHeight = '0';
        icon.textContent = '+';
        icon.style.transform = 'rotate(0deg)';
    } else {
        btn.setAttribute('aria-expanded', 'true');
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.textContent = '×';
        icon.style.transform = 'rotate(90deg)';
    }
}

// Newsletter mock subscription
function handleNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email').value;
    if (email) {
        document.querySelector('.newsletter-form').style.display = 'none';
        document.getElementById('newsletter-success').style.display = 'block';
    }
}

// Animated counter for statistics
document.addEventListener("DOMContentLoaded", function() {
    const counters = document.querySelectorAll('.counter-animate');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                const target = parseInt(entry.target.getAttribute('data-target'));
                const suffix = entry.target.getAttribute('data-suffix') || '';
                const duration = 1500;
                const step = Math.ceil(target / (duration / 16));
                let current = 0;
                
                const timer = setInterval(() => {
                    current = Math.min(current + step, target);
                    entry.target.textContent = current.toLocaleString() + suffix;
                    if (current >= target) clearInterval(timer);
                }, 16);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(c => observer.observe(c));
});
</script>

</body>
</html>
