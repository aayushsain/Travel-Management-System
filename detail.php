<?php
// C:\travel\detail.php
// Package Detail Page — uses prepared statements for all DB operations

include('function.php');
$pid = isset($_GET["pid"]) ? (int)$_GET["pid"] : 0;

// Fetch package using prepared statement — prevents SQL injection
$package_data = null;
if ($pid > 0) {
    $result = prepare_query($cn,
        "SELECT p.*, c.Cat_name, s.Subcatname
         FROM package p
         JOIN category c ON p.Category = c.Cat_id
         JOIN subcategory s ON p.Subcategory = s.Subcatid
         WHERE p.Packid = ?",
        "i", [$pid]
    );
    if ($result && mysqli_num_rows($result) > 0) {
        $package_data = mysqli_fetch_assoc($result);
    }
}

// Fetch categories for sidebar
$cat_result = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_id");

// Compute derived values if package found
$pseudo_rating  = null;
$pseudo_reviews = null;
$accentColor    = '#F4B942';

if ($package_data) {
    $pseudo_rating  = (($package_data['Packid'] * 5) % 2 == 0) ? 5 : 4;
    $pseudo_reviews = ($package_data['Packid'] * 218) + 84;
    
    // Per-destination accent color
    $packName = strtolower($package_data['Packname']);
    if (strpos($packName, 'italy') !== false)        $accentColor = '#d97706';
    elseif (strpos($packName, 'paris') !== false)    $accentColor = '#fde047';
    elseif (strpos($packName, 'switzerland') !== false) $accentColor = '#bfdbfe';
    elseif (strpos($packName, 'dubai') !== false)    $accentColor = '#eab308';
    elseif (strpos($packName, 'bali') !== false)     $accentColor = '#34d399';
    elseif (strpos($packName, 'canada') !== false)   $accentColor = '#ef4444';
    elseif (strpos($packName, 'india') !== false)    $accentColor = '#f97316';
}

// Page title for SEO
$page_title = $package_data 
    ? h($package_data['Packname']) . ' — ' . h($package_data['Cat_name']) . ' | VoyageQuest'
    : 'Package Details | VoyageQuest';
$page_desc = $package_data
    ? 'Book ' . h($package_data['Packname']) . ' starting from ₹' . number_format($package_data['Packprice']) . '. Expert guided tour in ' . h($package_data['Subcatname']) . '. Submit a booking enquiry today.'
    : 'Premium travel packages at VoyageQuest.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_desc; ?>">
    
    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_desc; ?>">
    <?php if ($package_data): ?>
    <meta property="og:image" content="Admin/packimages/<?php echo h($package_data['Pic1']); ?>">
    <?php endif; ?>
    <meta property="og:site_name" content="VoyageQuest">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
    <link rel="stylesheet" href="css/premium_detail.css?v=<?php echo filemtime('css/premium_detail.css'); ?>">
    
    <?php if ($package_data): ?>
    <style>
    /* Per-package cinematic background override */
    body {
        background-image: url('Admin/packimages/<?php echo h($package_data['Pic1']); ?>') !important;
        animation: cinematicPan 50s ease-in-out infinite alternate !important;
    }
    body::before {
        background: linear-gradient(
            135deg,
            rgba(8, 5, 0, 0.80) 0%,
            rgba(<?php
                if (strpos($packName,'italy')   !== false) echo '60,20,0';
                elseif (strpos($packName,'paris')   !== false) echo '30,20,50';
                elseif (strpos($packName,'dubai')   !== false) echo '40,25,0';
                elseif (strpos($packName,'swiss')   !== false) echo '0,20,60';
                elseif (strpos($packName,'bali')    !== false) echo '0,40,20';
                elseif (strpos($packName,'canada')  !== false) echo '40,5,5';
                else                                           echo '8,8,16';
            ?>, 0.55) 100%
        ) !important;
    }
    [data-theme="light"] body::before {
        background: linear-gradient(135deg, rgba(240,244,248,0.88) 0%, rgba(255,255,255,0.92) 100%) !important;
    }
    :root { --luxury-accent: <?php echo $accentColor; ?>; }
    </style>
    <?php endif; ?>
</head>
<body>
<?php include('top.php'); ?>

<?php if (!$package_data): ?>
<div class="ta-container" style="text-align: center; padding-top: 12rem;">
    <h2 style="color: var(--text-gray);">Package not found.</h2>
    <p style="color: var(--text-gray);">The package you are looking for does not exist or has been removed.</p>
    <a href="category.php" class="btn-ta btn-ta-primary" style="margin-top: 1rem; display: inline-block;">Browse All Packages</a>
</div>
<?php else:
$data = $package_data;
?>

<div class="luxury-detail-page">
    
    <!-- Hero Section -->
    <header class="luxury-hero">
        <!-- Parallax Motion Background -->
        <div class="luxury-hero-motion" style="background-image: url('Admin/packimages/<?php echo h($data['Pic1']); ?>');" aria-hidden="true"></div>
        
        <div class="luxury-hero-overlay" aria-hidden="true"></div>
        <div class="luxury-hero-content">
            <div style="display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(244,185,66,0.15); border: 1px solid rgba(244,185,66,0.3); border-radius: 30px; padding: 0.4rem 1rem; margin-bottom: 1.5rem; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--gold);" data-aos="fade-down">
                ✈ <?php echo h($data['Cat_name']); ?> &nbsp;›&nbsp; <?php echo h($data['Subcatname']); ?>
            </div>
            
            <h1 class="luxury-title" data-aos="fade-up"><?php echo h($data['Packname']); ?></h1>
            <p class="luxury-tagline" data-aos="fade-up" data-aos-delay="100">
                A world-class experience curated exclusively in <?php echo h($data['Subcatname']); ?>
            </p>
            
            <div class="luxury-hero-meta" data-aos="fade-up" data-aos-delay="200">
                <div class="meta-item">
                    <span class="meta-label">Starting Price</span>
                    <span class="meta-value">₹<?php echo number_format((double)$data['Packprice']); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Rating</span>
                    <span class="meta-value">★ 4.9/5 (<?php echo number_format($pseudo_reviews); ?>)</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Category</span>
                    <span class="meta-value"><?php echo h($data['Cat_name']); ?></span>
                </div>
            </div>

            <div class="luxury-hero-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="enquiry.php?pid=<?php echo (int)$data['Packid']; ?>" class="btn-luxury-primary">
                    Reserve My Journey ✈
                </a>
                <a href="#itinerary" class="btn-luxury-secondary">
                    Explore Itinerary ↓
                </a>
            </div>
        </div>
    </header>

    <!-- Content Layout -->
    <div class="luxury-container" id="itinerary">
        <div class="luxury-grid">
            
            <!-- Main Content -->
            <div class="luxury-main">
                
                <!-- Photo Gallery Masonry -->
                <section class="luxury-section">
                    <h2 class="luxury-section-title" data-aos="fade-up">A Glimpse of Your Journey</h2>
                    <div class="luxury-masonry">
                        <div class="masonry-item lm-large" data-aos="fade-up">
                            <img src="Admin/packimages/<?php echo h($data['Pic1']); ?>" 
                                 alt="<?php echo h($data['Packname']); ?> — Main View"
                                 loading="lazy"
                                 onerror="this.src='images/travelimage.jpg'">
                        </div>
                        <?php if (!empty($data['Pic2'])): ?>
                        <div class="masonry-item lm-tall" data-aos="fade-up" data-aos-delay="100">
                            <img src="Admin/packimages/<?php echo h($data['Pic2']); ?>" 
                                 alt="<?php echo h($data['Packname']); ?> — Gallery 2"
                                 loading="lazy"
                                 onerror="this.src='images/travelimage.jpg'">
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($data['Pic3'])): ?>
                        <div class="masonry-item lm-square" data-aos="fade-up" data-aos-delay="150">
                            <img src="Admin/packimages/<?php echo h($data['Pic3']); ?>" 
                                 alt="<?php echo h($data['Packname']); ?> — Gallery 3"
                                 loading="lazy"
                                 onerror="this.src='images/travelimage.jpg'">
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                
                <!-- Package Description -->
                <section class="luxury-section" id="description">
                    <h2 class="luxury-section-title" data-aos="fade-up">The Experience</h2>
                    <div class="luxury-typography-body" data-aos="fade-up" data-aos-delay="100">
                        <p><?php echo nl2br(h($data['Detail'])); ?></p>
                    </div>
                </section>

                <!-- What's Included -->
                <section class="luxury-section" data-aos="fade-up">
                    <h2 class="luxury-section-title">What's Included</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                        <?php
                        $inclusions = [
                            ['✈', 'Flight Tickets', 'Round-trip airfare included'],
                            ['🏨', 'Hotel Stays', 'Luxury accommodations'],
                            ['🍽️', 'Daily Meals', 'Breakfast & dinner included'],
                            ['🗺️', 'Expert Guide', 'Certified local guide'],
                            ['🚌', 'Transport', 'All local transfers'],
                            ['📋', 'Visa Support', 'Documentation assistance'],
                        ];
                        foreach ($inclusions as $item):
                        ?>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; transition: all 0.3s ease;"
                             onmouseover="this.style.borderColor='rgba(244,185,66,0.3)'; this.style.background='rgba(244,185,66,0.05)'"
                             onmouseout="this.style.borderColor='var(--border-light)'; this.style.background='rgba(255,255,255,0.03)'">
                            <span style="font-size: 1.5rem;"><?php echo $item[0]; ?></span>
                            <div>
                                <p style="font-weight: 700; margin: 0; font-size: 0.9rem;"><?php echo $item[1]; ?></p>
                                <p style="color: var(--text-gray); font-size: 0.8rem; margin: 0;"><?php echo $item[2]; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Rating & Reviews -->
                <section class="luxury-section" data-aos="fade-up">
                    <h2 class="luxury-section-title">Traveler Reviews</h2>
                    <div style="background: rgba(244,185,66,0.05); border: 1px solid rgba(244,185,66,0.15); border-radius: 20px; padding: 2rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
                            <div style="text-align: center;">
                                <div style="font-size: 3.5rem; font-weight: 900; color: var(--gold); line-height: 1;">4.9</div>
                                <div style="color: var(--gold); font-size: 1.2rem; margin: 0.25rem 0;">★★★★★</div>
                                <div style="font-size: 0.8rem; color: var(--text-gray);"><?php echo number_format($pseudo_reviews); ?> reviews</div>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <?php
                                $bars = [['Excellent', 87], ['Very Good', 9], ['Average', 3], ['Poor', 1]];
                                foreach ($bars as $b):
                                ?>
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.8rem; color: var(--text-gray); width: 80px; flex-shrink: 0;"><?php echo $b[0]; ?></span>
                                    <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                                        <div style="width: <?php echo $b[1]; ?>%; height: 100%; background: var(--gold); border-radius: 10px;"></div>
                                    </div>
                                    <span style="font-size: 0.8rem; color: var(--text-gray); width: 30px; text-align: right;"><?php echo $b[1]; ?>%</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sticky Booking Sidebar -->
            <div class="luxury-sidebar">
                <div class="luxury-booking-card sticky" data-aos="fade-left">
                    <h3 class="booking-price">
                        ₹<?php echo number_format((double)$data['Packprice']); ?>
                        <span class="booking-unit">per traveler</span>
                    </h3>
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <span style="color: var(--gold); font-size: 1rem;">★★★★★</span>
                        <span style="font-size: 0.85rem; color: var(--text-gray);">4.9 · <?php echo number_format($pseudo_reviews); ?> reviews</span>
                    </div>
                    
                    <div class="booking-meta">
                        <div class="bm-row">
                            <span>Availability</span>
                            <span style="color: #22c55e; font-weight: 700;">● Available</span>
                        </div>
                        <div class="bm-row">
                            <span>Category</span>
                            <span><?php echo h($data['Cat_name']); ?></span>
                        </div>
                        <div class="bm-row">
                            <span>Region</span>
                            <span><?php echo h($data['Subcatname']); ?></span>
                        </div>
                        <div class="bm-row">
                            <span>Package ID</span>
                            <span>#<?php echo (int)$data['Packid']; ?></span>
                        </div>
                    </div>
                    
                    <a href="enquiry.php?pid=<?php echo (int)$data['Packid']; ?>" 
                       class="btn-luxury-primary full-width"
                       style="margin-top: 1.5rem; display: block; text-align: center;">
                        Reserve Your Journey ✈
                    </a>
                    
                    <p style="text-align: center; color: var(--text-gray); font-size: 0.78rem; margin-top: 1rem;">
                        No payment now · Expert contacts you within 24h
                    </p>
                    
                    <!-- Trust Badges -->
                    <div style="display: flex; justify-content: center; gap: 1.5rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                        <div style="text-align: center;">
                            <div style="font-size: 1.2rem;">🔒</div>
                            <div style="font-size: 0.7rem; color: var(--text-gray); margin-top: 0.25rem;">Secure</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.2rem;">✅</div>
                            <div style="font-size: 0.7rem; color: var(--text-gray); margin-top: 0.25rem;">Verified</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.2rem;">⭐</div>
                            <div style="font-size: 0.7rem; color: var(--text-gray); margin-top: 0.25rem;">4.9 Rated</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.2rem;">💬</div>
                            <div style="font-size: 0.7rem; color: var(--text-gray); margin-top: 0.25rem;">24h Support</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const masonryImages = document.querySelectorAll(".luxury-masonry img");
    const heroMotion = document.querySelector(".luxury-hero-motion");

    masonryImages.forEach((img, index) => {
        // Highlight first image by default
        if (index === 0) {
            img.style.outline = "3px solid var(--gold)";
            img.style.outlineOffset = "4px";
        }

        img.style.cursor = "pointer";
        img.style.transition = "outline 0.3s ease, transform 0.3s ease";

        img.addEventListener("click", function() {
            masonryImages.forEach(i => {
                i.style.outline = "none";
                i.style.outlineOffset = "0";
            });
            this.style.outline = "3px solid var(--gold)";
            this.style.outlineOffset = "4px";

            // Update body and hero background to show clicked image
            document.body.style.setProperty("background-image", `url('${this.src}')`, "important");
            if (heroMotion) {
                heroMotion.style.backgroundImage = `url('${this.src}')`;
            }
        });
    });
});
</script>

<?php
mysqli_close($cn);
include('bottom.php');
?>
</body>
</html>
