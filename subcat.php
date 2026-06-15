<?php
// C:\travel\subcat.php
// Subcategory Listing Page — prepared statements, SEO meta, lazy loading

include('function.php');
$catid = isset($_GET["catid"]) ? (int)$_GET["catid"] : 0;

// Get category name for title/breadcrumb
$cat_name = 'Destinations';
if ($catid > 0) {
    $cat_res = prepare_query($cn, "SELECT Cat_name FROM category WHERE Cat_id = ?", "i", [$catid]);
    if ($cat_res && $row = mysqli_fetch_assoc($cat_res)) {
        $cat_name = $row['Cat_name'];
    }
}

// Fetch all categories for sidebar
$all_cats = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_id");

// Fetch subcategories using prepared statement
$subcats = [];
if ($catid > 0) {
    $result = prepare_query($cn, 
        "SELECT Subcatid, Subcatname, Pic, Detail FROM subcategory WHERE Catid = ? ORDER BY Subcatid",
        "i", [$catid]
    );
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $subcats[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($cat_name); ?> | VoyageQuest Destinations</title>
    <meta name="description" content="Explore <?php echo h($cat_name); ?> travel packages with VoyageQuest. Find curated subcategories and book premium vacation experiences.">
    <meta property="og:title" content="<?php echo h($cat_name); ?> | VoyageQuest">
    <meta property="og:description" content="Browse <?php echo h($cat_name); ?> travel regions and packages.">
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
            <li aria-current="page" style="color:var(--text-white);"><?php echo h($cat_name); ?></li>
        </ol>
    </nav>

    <h1 class="ta-section-title" data-aos="fade-up">
        <?php echo h($cat_name); ?> <span>Regions</span>
    </h1>

    <div class="ta-grid-sidebar">
        <!-- Sidebar Category Navigation -->
        <aside class="ta-sidebar" data-aos="fade-right" aria-label="Travel categories">
            <h2 class="ta-sidebar-title" style="font-size:0.95rem;">All Categories</h2>
            <ul class="ta-sidebar-menu" role="list">
                <?php while ($cat = mysqli_fetch_assoc($all_cats)): ?>
                <li role="listitem">
                    <a href="subcat.php?catid=<?php echo (int)$cat['Cat_id']; ?>" 
                       class="ta-sidebar-link <?php echo ((int)$cat['Cat_id'] === $catid) ? 'active' : ''; ?>"
                       <?php echo ((int)$cat['Cat_id'] === $catid) ? 'aria-current="true"' : ''; ?>>
                        <?php echo h($cat['Cat_name']); ?>
                    </a>
                </li>
                <?php endwhile; ?>
            </ul>
        </aside>

        <!-- Subcategories Grid -->
        <div>
            <?php if (empty($subcats)): ?>
            <div class="ta-detail-card" role="status">
                <h3 style="text-align:center;color:var(--text-gray);margin-bottom:1rem;">No regions found in this category.</h3>
                <p style="text-align:center;color:var(--text-gray);">
                    <a href="category.php" class="btn-ta btn-ta-secondary" style="display:inline-block;margin-top:0.5rem;">Browse All Categories</a>
                </p>
            </div>
            <?php else: ?>
            <div class="ta-grid-3">
                <?php foreach ($subcats as $i => $sub):
                    $pseudo_rating  = (($sub['Subcatid'] * 3) % 2 == 0) ? 5 : 4;
                    $pseudo_reviews = number_format(($sub['Subcatid'] * 115) + 42);
                ?>
                <article class="ta-card" 
                         data-aos="fade-up" 
                         data-aos-delay="<?php echo ($i % 3) * 100; ?>"
                         aria-label="<?php echo h($sub['Subcatname']); ?>">
                    <div class="ta-card-img-wrapper">
                        <img src="Admin/subcatimages/<?php echo h($sub['Pic']); ?>"
                             alt="<?php echo h($sub['Subcatname']); ?>"
                             class="ta-card-img"
                             loading="lazy"
                             onerror="this.src='images/travelimage.jpg'">
                    </div>
                    <div class="ta-card-body">
                        <h2 class="ta-card-title" style="font-size:1.2rem;"><?php echo h($sub['Subcatname']); ?></h2>
                        
                        <div class="rating-container" aria-label="Rating: <?php echo $pseudo_rating; ?> out of 5">
                            <div class="rating-bubbles" aria-hidden="true">
                                <?php for ($j = 1; $j <= 5; $j++): ?>
                                <span class="bubble <?php echo ($j <= $pseudo_rating) ? 'filled' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-count"><?php echo $pseudo_reviews; ?> reviews</span>
                        </div>
                        
                        <p class="ta-card-text">
                            <?php echo h(substr($sub['Detail'], 0, 120)) . (strlen($sub['Detail']) > 120 ? '...' : ''); ?>
                        </p>
                        <div class="ta-card-footer">
                            <a href="package.php?subcatid=<?php echo (int)$sub['Subcatid']; ?>" 
                               class="btn-ta btn-ta-primary" 
                               style="width:100%;"
                               aria-label="View packages in <?php echo h($sub['Subcatname']); ?>">
                                View Packages →
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