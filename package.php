<?php
// C:\travel\package.php
// Package Listing Page — prepared statements, SEO, lazy loading

include('function.php');
$subcatid = isset($_GET["subcatid"]) ? (int)$_GET["subcatid"] : 0;

// Get subcategory and category info for titles
$subcat_name = 'Travel Packages';
$cat_name    = 'Destinations';
$subcat_catid = 0;
if ($subcatid > 0) {
    $sub_res = prepare_query($cn,
        "SELECT s.Subcatname, s.Catid, c.Cat_name FROM subcategory s JOIN category c ON s.Catid = c.Cat_id WHERE s.Subcatid = ?",
        "i", [$subcatid]
    );
    if ($sub_res && $sub_row = mysqli_fetch_assoc($sub_res)) {
        $subcat_name   = $sub_row['Subcatname'];
        $cat_name      = $sub_row['Cat_name'];
        $subcat_catid  = (int)$sub_row['Catid'];
    }
}

// Fetch all categories for sidebar
$all_cats = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_id");

// Fetch packages using prepared statement — prevents SQL injection
$packages = [];
if ($subcatid > 0) {
    $pkg_res = prepare_query($cn,
        "SELECT Packid, Packname, Packprice, Pic1, Detail FROM package WHERE Subcategory = ? ORDER BY Packid",
        "i", [$subcatid]
    );
    if ($pkg_res) {
        while ($row = mysqli_fetch_assoc($pkg_res)) {
            $packages[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($subcat_name); ?> Packages | VoyageQuest</title>
    <meta name="description" content="Browse premium <?php echo h($subcat_name); ?> travel packages with VoyageQuest. Compare prices, read reviews, and book your luxury vacation today.">
    <meta property="og:title" content="<?php echo h($subcat_name); ?> | VoyageQuest Packages">
    <meta property="og:description" content="Premium travel packages in <?php echo h($subcat_name); ?> — Book with VoyageQuest.">
    <meta property="og:site_name" content="VoyageQuest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
</head>
<body>
<?php include('top.php'); ?>

<main class="ta-container" role="main">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom: 1rem;" data-aos="fade-up">
        <ol style="display:flex;gap:0.5rem;list-style:none;padding:0;margin:0;font-size:0.85rem;color:var(--text-gray);flex-wrap:wrap;">
            <li><a href="index.php" style="color:var(--gold);text-decoration:none;">Home</a></li>
            <li style="opacity:0.5;">›</li>
            <li><a href="category.php" style="color:var(--gold);text-decoration:none;">Destinations</a></li>
            <li style="opacity:0.5;">›</li>
            <li><a href="subcat.php?catid=<?php echo $subcat_catid; ?>" style="color:var(--gold);text-decoration:none;"><?php echo h($cat_name); ?></a></li>
            <li style="opacity:0.5;">›</li>
            <li aria-current="page" style="color:var(--text-white);"><?php echo h($subcat_name); ?></li>
        </ol>
    </nav>

    <h1 class="ta-section-title" data-aos="fade-up">
        <?php echo h($subcat_name); ?> <span>Packages</span>
    </h1>

    <div class="ta-grid-sidebar">
        <!-- Sidebar Category Navigation -->
        <aside class="ta-sidebar" data-aos="fade-right" aria-label="Travel categories">
            <h2 class="ta-sidebar-title" style="font-size:0.95rem;">Categories</h2>
            <ul class="ta-sidebar-menu" role="list">
                <?php while ($cat = mysqli_fetch_assoc($all_cats)): ?>
                <li role="listitem">
                    <a href="subcat.php?catid=<?php echo (int)$cat['Cat_id']; ?>" 
                       class="ta-sidebar-link <?php echo ((int)$cat['Cat_id'] === $subcat_catid) ? 'active' : ''; ?>">
                        <?php echo h($cat['Cat_name']); ?>
                    </a>
                </li>
                <?php endwhile; ?>
            </ul>
        </aside>

        <!-- Packages Grid -->
        <div>
            <?php if (empty($packages)): ?>
            <div class="ta-detail-card" role="status">
                <h3 style="text-align:center;color:var(--text-gray);margin-bottom:1rem;">
                    No packages found in this region.
                </h3>
                <p style="text-align:center;color:var(--text-gray);margin-bottom:1.5rem;">
                    Check back soon — we're adding new packages regularly.
                </p>
                <div style="text-align:center;">
                    <a href="category.php" class="btn-ta btn-ta-primary" style="display:inline-block;">
                        Browse All Destinations
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Results Count -->
            <p style="color:var(--text-gray);font-size:0.9rem;margin-bottom:1.5rem;" data-aos="fade-up">
                Showing <strong style="color:var(--text-white);"><?php echo count($packages); ?></strong> 
                package<?php echo count($packages) !== 1 ? 's' : ''; ?> in <?php echo h($subcat_name); ?>
            </p>
            
            <div class="ta-grid-3">
                <?php foreach ($packages as $i => $pkg):
                    $pseudo_rating  = (($pkg['Packid'] * 5) % 2 == 0) ? 5 : 4;
                    $pseudo_reviews = number_format(($pkg['Packid'] * 218) + 84);
                ?>
                <article class="ta-card" 
                         data-aos="fade-up" 
                         data-aos-delay="<?php echo ($i % 3) * 100; ?>"
                         aria-label="<?php echo h($pkg['Packname']); ?>">
                    <div class="ta-card-img-wrapper">
                        <img src="Admin/packimages/<?php echo h($pkg['Pic1']); ?>"
                             alt="<?php echo h($pkg['Packname']); ?>"
                             class="ta-card-img"
                             loading="lazy"
                             onerror="this.src='images/travelimage.jpg'">
                        <!-- Price overlay -->
                        <div style="position:absolute;top:1rem;right:1rem;background:rgba(10,31,68,0.9);backdrop-filter:blur(10px);border:1px solid rgba(244,185,66,0.3);border-radius:20px;padding:0.35rem 0.9rem;font-size:0.85rem;font-weight:800;color:var(--gold);z-index:2;">
                            ₹<?php echo number_format((double)$pkg['Packprice']); ?>
                        </div>
                    </div>
                    <div class="ta-card-body">
                        <h2 class="ta-card-title" style="font-size:1.15rem;"><?php echo h($pkg['Packname']); ?></h2>
                        
                        <div class="rating-container" aria-label="Rating: <?php echo $pseudo_rating; ?> out of 5">
                            <div class="rating-bubbles" aria-hidden="true">
                                <?php for ($j = 1; $j <= 5; $j++): ?>
                                <span class="bubble <?php echo ($j <= $pseudo_rating) ? 'filled' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-count"><?php echo $pseudo_reviews; ?> reviews</span>
                        </div>

                        <p class="ta-card-text">
                            <?php echo h(substr($pkg['Detail'], 0, 110)) . (strlen($pkg['Detail']) > 110 ? '...' : ''); ?>
                        </p>
                        <div class="ta-card-footer">
                            <span class="ta-price">
                                ₹<?php echo number_format((double)$pkg['Packprice']); ?>
                                <span>/ traveler</span>
                            </span>
                            <a href="detail.php?pid=<?php echo (int)$pkg['Packid']; ?>" 
                               class="btn-ta btn-ta-primary"
                               aria-label="View details for <?php echo h($pkg['Packname']); ?>">
                                View Details
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
mysqli_close($cn);
include('bottom.php');
?>
</body>
</html>