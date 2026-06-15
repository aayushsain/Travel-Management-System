<?php
// C:\travel\category.php
include('function.php');

$search_q = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_budget = isset($_GET['budget']) ? (int)$_GET['budget'] : 0;
$search_results = [];
$is_search = ($search_q !== '' || $search_budget > 0);

if ($is_search) {
    $cn = makeconnection();
    $sql = "SELECT p.Packid, p.Packname, p.Packprice, p.Pic1, p.Detail, c.Cat_name, s.Subcatname 
            FROM package p 
            JOIN category c ON p.Category = c.Cat_id 
            JOIN subcategory s ON p.Subcategory = s.Subcatid 
            WHERE 1=1";
    $types = "";
    $params = [];
    
    if ($search_q !== '') {
        $sql .= " AND (p.Packname LIKE ? OR p.Detail LIKE ?)";
        $types .= "ss";
        $like_term = "%" . $search_q . "%";
        $params[] = $like_term;
        $params[] = $like_term;
    }
    if ($search_budget > 0 && $search_budget < 300000) {
        $sql .= " AND p.Packprice <= ?";
        $types .= "d";
        $params[] = (double)$search_budget;
    }
    
    $sql .= " ORDER BY p.Packprice ASC";
    
    $search_res = prepare_query($cn, $sql, $types, $params);
    if ($search_res) {
        while ($row = mysqli_fetch_assoc($search_res)) {
            $search_results[] = $row;
        }
    }
    mysqli_close($cn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations | VoyageQuest — Explore Travel Categories</title>
    <meta name="description" content="Explore VoyageQuest travel categories including Family Tours and Religious Tours. Find curated subcategories and packages tailored to every traveler.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
    
    <style>
    /* Premium Category Cards Styling */
    .premium-cat-card {
        display: flex;
        flex-direction: column;
        padding: 2.5rem 2rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Hover effects */
    .premium-cat-card:hover {
        transform: translateY(-5px);
        background: rgba(244, 185, 66, 0.04);
        border-color: rgba(244, 185, 66, 0.35);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 
                    0 0 25px rgba(244, 185, 66, 0.05);
    }

    .religious-cat-card:hover {
        background: rgba(249, 115, 22, 0.04);
        border-color: rgba(249, 115, 22, 0.35);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 
                    0 0 25px rgba(249, 115, 22, 0.05);
    }

    /* Light mode support */
    [data-theme="light"] .premium-cat-card {
        background: rgba(255, 255, 255, 0.65) !important;
        border-color: rgba(0, 0, 0, 0.08) !important;
    }

    [data-theme="light"] .premium-cat-card:hover {
        background: rgba(244, 185, 66, 0.05) !important;
        border-color: rgba(244, 185, 66, 0.4) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06) !important;
    }

    [data-theme="light"] .religious-cat-card:hover {
        background: rgba(249, 115, 22, 0.05) !important;
        border-color: rgba(249, 115, 22, 0.4) !important;
    }

    /* Icon style */
    .premium-cat-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: rgba(244, 185, 66, 0.1);
        border: 1px solid rgba(244, 185, 66, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .premium-cat-icon i {
        font-size: 1.8rem;
        color: #F4B942;
    }

    .religious-cat-card .premium-cat-icon {
        background: rgba(249, 115, 22, 0.1);
        border-color: rgba(249, 115, 22, 0.2);
    }

    .religious-cat-card .premium-cat-icon i {
        color: #F97316;
    }

    .premium-cat-card:hover .premium-cat-icon {
        transform: scale(1.1) rotate(5deg);
        background: rgba(244, 185, 66, 0.2);
    }

    .religious-cat-card:hover .premium-cat-icon {
        background: rgba(249, 115, 22, 0.2);
    }

    /* Content styles */
    .premium-cat-content h4 {
        font-family: var(--font-serif);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-white);
        margin-bottom: 0.75rem;
    }

    [data-theme="light"] .premium-cat-content h4 {
        color: #0A1F44 !important;
    }

    .premium-cat-content p {
        color: var(--text-gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    [data-theme="light"] .premium-cat-content p {
        color: #475569 !important;
    }

    /* Action link style */
    .premium-cat-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #F4B942;
        transition: all 0.3s ease;
    }

    .religious-cat-card .premium-cat-action {
        color: #F97316;
    }

    .premium-cat-card:hover .premium-cat-action {
        gap: 0.75rem;
    }
    
    /* Search Filter Panel Styling */
    .search-filter-panel {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    [data-theme="light"] .search-filter-panel {
        background: rgba(255, 255, 255, 0.65) !important;
        border-color: rgba(0, 0, 0, 0.08) !important;
    }
    
    [data-theme="light"] .search-filter-panel h3 {
        color: #0A1F44 !important;
    }
    
    [data-theme="light"] .search-filter-panel input[type="text"] {
        background: #ffffff !important;
        border-color: rgba(0, 0, 0, 0.15) !important;
        color: #0c1322 !important;
    }
    
    [data-theme="light"] .search-filter-panel input[type="text"]::placeholder {
        color: #94a3b8 !important;
    }
    
    [data-theme="light"] .search-filter-panel label {
        color: #475569 !important;
    }
    
    [data-theme="light"] .search-filter-panel #budgetValue {
        color: #c2410c !important;
    }
    </style>
</head>
<body>
<?php include('top.php'); ?>

<main class="ta-container" role="main">
    <h1 class="ta-section-title" data-aos="fade-up">Explore <span>Categories</span></h1>
    
    <div style="max-width: 960px; margin: 0 auto 4rem auto;" data-aos="fade-up">
        <!-- Main Content -->
        <div class="ta-detail-card">
            <h2 style="font-family:var(--font-serif);font-size:2.2rem;margin-bottom:1rem;color:var(--gold);">Welcome to VoyageQuest Tours</h2>
            
            <div class="rating-container" style="margin-bottom:1.5rem;">
                <div class="rating-bubbles" aria-label="5 out of 5 rating">
                    <span class="bubble filled" aria-hidden="true"></span>
                    <span class="bubble filled" aria-hidden="true"></span>
                    <span class="bubble filled" aria-hidden="true"></span>
                    <span class="bubble filled" aria-hidden="true"></span>
                    <span class="bubble filled" aria-hidden="true"></span>
                </div>
                <span class="rating-count">Based on 14,000+ luxury bookings worldwide</span>
            </div>

            <p style="font-size:1.1rem;color:var(--text-gray);margin-bottom:2rem;line-height:1.8;">
                Select a category below to discover available regions, customized family holidays, and spiritual packages. VoyageQuest compares travel plans, hotels, and tours to present you with top-rated experiences.
            </p>

            <!-- Search & Filter Panel -->
            <div class="search-filter-panel">
                <h3 style="font-family:var(--font-serif); font-size:1.5rem; color:var(--text-white); margin-bottom:1.5rem; display:flex; align-items:center; gap:0.6rem; margin-top:0;"><i class="fa-solid fa-sliders" style="color:var(--gold);"></i> Find Your Ideal Journey</h3>
                <form method="get" action="category.php" class="row g-4 align-items-end">
                    <div class="col-md-5">
                        <label for="q" class="form-label" style="font-size:0.85rem; font-weight:700; color:var(--text-gray); text-transform:uppercase; letter-spacing:0.5px;">Search Keywords</label>
                        <input type="text" id="q" name="q" class="form-control" style="background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); color:#fff; border-radius:10px; padding:0.6rem 1rem;" placeholder="e.g. Beach, Goa, Temple, Adventure" value="<?php echo h($search_q); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="budget" class="form-label" style="font-size:0.85rem; font-weight:700; color:var(--text-gray); text-transform:uppercase; letter-spacing:0.5px; display:flex; justify-content:space-between; width:100%;">
                            <span>Max Price</span>
                            <span id="budgetValue" style="color:var(--gold); font-weight:800;"><?php echo ($search_budget > 0 && $search_budget < 300000) ? '₹' . number_format($search_budget) : 'Any'; ?></span>
                        </label>
                        <input type="range" id="budget" name="budget" min="5000" max="300000" step="5000" class="form-range" style="accent-color:var(--gold);" value="<?php echo ($search_budget > 0) ? $search_budget : 300000; ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-warning w-100" style="background:var(--gold); border:none; color:#0c1322; font-weight:800; border-radius:10px; padding:0.65rem 1rem; text-transform:uppercase; letter-spacing:0.5px; box-shadow:0 4px 15px rgba(244,185,66,0.2);">Filter Packages</button>
                    </div>
                </form>
            </div>

            <!-- Search Results Block -->
            <?php if ($is_search): ?>
                <div style="margin-top: 1rem; margin-bottom: 3.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; border-bottom:1px solid rgba(255,255,255,0.08); padding-bottom:0.75rem;">
                        <h3 style="font-family:var(--font-serif); font-size:1.8rem; color:var(--text-white); margin:0;">Search Results</h3>
                        <a href="category.php" style="color:var(--gold); font-size:0.9rem; text-decoration:none; font-weight:700; display:flex; align-items:center; gap:0.4rem;"><i class="fa-solid fa-circle-xmark"></i> Clear Filters</a>
                    </div>
                    
                    <?php if (empty($search_results)): ?>
                        <div style="padding:3rem; text-align:center; background:rgba(255,255,255,0.01); border:1px dashed rgba(255,255,255,0.08); border-radius:18px;">
                            <i class="fa-solid fa-face-frown" style="font-size:3rem; color:var(--text-gray); margin-bottom:1rem; display:block;"></i>
                            <h4 style="color:var(--text-white); font-weight:700; margin-bottom:0.5rem;">No Packages Match Your Filters</h4>
                            <p style="color:var(--text-gray); font-size:0.95rem; margin:0;">Try typing general keywords like "Goa" or adjusting the budget slider higher.</p>
                        </div>
                    <?php else: ?>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:1.5rem;">
                            <?php foreach ($search_results as $pkg):
                                $pseudo_rating  = (($pkg['Packid'] * 5) % 2 == 0) ? 5 : 4;
                                $pseudo_reviews = number_format(($pkg['Packid'] * 218) + 84);
                            ?>
                            <article class="ta-card" aria-label="<?php echo h($pkg['Packname']); ?>" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:18px; overflow:hidden; display:flex; flex-direction:column; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                                <div class="ta-card-img-wrapper" style="position:relative; aspect-ratio:16/10; overflow:hidden;">
                                    <img src="Admin/packimages/<?php echo h($pkg['Pic1']); ?>" alt="<?php echo h($pkg['Packname']); ?>" class="ta-card-img" style="width:100%; height:100%; object-fit:cover;" loading="lazy" onerror="this.src='images/travelimage.jpg'">
                                    <div style="position:absolute; top:0.75rem; right:0.75rem; background:rgba(10,31,68,0.9); backdrop-filter:blur(10px); border:1px solid rgba(244,185,66,0.3); border-radius:20px; padding:0.3rem 0.8rem; font-size:0.8rem; font-weight:800; color:var(--gold); z-index:2;">
                                        ₹<?php echo number_format((double)$pkg['Packprice']); ?>
                                    </div>
                                </div>
                                <div class="ta-card-body" style="padding:1.25rem; display:flex; flex-direction:column; flex-grow:1; background:rgba(255,255,255,0.01);">
                                    <span style="font-size:0.7rem; text-transform:uppercase; color:var(--gold); font-weight:700; letter-spacing:1px; display:block; margin-bottom:0.25rem;"><?php echo h($pkg['Cat_name']); ?> › <?php echo h($pkg['Subcatname']); ?></span>
                                    <h4 class="ta-card-title" style="font-size:1.15rem; font-weight:700; margin-bottom:0.5rem; color:#fff; line-height:1.3;"><?php echo h($pkg['Packname']); ?></h4>
                                    
                                    <div class="rating-container" style="margin-bottom:0.75rem;">
                                        <div class="rating-bubbles" aria-hidden="true" style="gap:2px;">
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                            <span class="bubble <?php echo ($j <= $pseudo_rating) ? 'filled' : ''; ?>" style="width:8px; height:8px;"></span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="rating-count" style="font-size:0.75rem; color:var(--text-gray); margin-left:0.5rem;"><?php echo $pseudo_reviews; ?> reviews</span>
                                    </div>
                                    
                                    <p class="ta-card-text" style="font-size:0.85rem; color:var(--text-gray); line-height:1.5; margin-bottom:1.25rem; flex-grow:1;">
                                        <?php echo h(substr($pkg['Detail'], 0, 100)) . (strlen($pkg['Detail']) > 100 ? '...' : ''); ?>
                                    </p>
                                    <a href="detail.php?pid=<?php echo (int)$pkg['Packid']; ?>" class="btn-ta btn-ta-primary" style="width:100%; padding:0.55rem; font-size:0.85rem; text-align:center; display:block; text-decoration:none; margin-top:auto;">
                                        View Details
                                    </a>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div style="aspect-ratio:16/9;border-radius:16px;overflow:hidden;border:1px solid var(--border-light);margin-bottom:2.5rem;box-shadow:0 10px 30px rgba(0,0,0,0.3);">
                <img src="images/beach3.jpg" alt="VoyageQuest Travel Destinations — Beautiful coastal scenery" 
                     style="width:100%;height:100%;object-fit:cover;" loading="lazy">
            </div>
            
            <p style="color:var(--text-gray);font-size:0.95rem;line-height:1.8;margin-bottom:2rem;">
                Our itineraries cover everything: luxury transportation, selected dining choices, and professional tour guides, all verified by traveler reviews.
            </p>

            <!-- Premium Category Cards -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem;margin-top:3rem;">
                
                <!-- Family Tours Card -->
                <a href="subcat.php?catid=1" class="premium-cat-card family-cat-card" style="text-decoration: none;">
                    <div class="premium-cat-icon">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </div>
                    <div class="premium-cat-content">
                        <h4>Family Tours</h4>
                        <p>Discover pristine beaches, lush mountains, and curated adventures tailored for unforgettable family moments.</p>
                        <span class="premium-cat-action">Explore Packages <i class="fa-solid fa-arrow-right-long"></i></span>
                    </div>
                </a>
                
                <!-- Religious Tours Card -->
                <a href="subcat.php?catid=2" class="premium-cat-card religious-cat-card" style="text-decoration: none;">
                    <div class="premium-cat-icon">
                        <i class="fa-solid fa-place-of-worship"></i>
                    </div>
                    <div class="premium-cat-content">
                        <h4>Religious Tours</h4>
                        <p>Embark on spiritual journeys, sacred pilgrimages, and historic temple explorations designed to inspire peace.</p>
                        <span class="premium-cat-action">Explore Packages <i class="fa-solid fa-arrow-right-long"></i></span>
                    </div>
                </a>
                
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const budgetInput = document.getElementById("budget");
    const budgetValue = document.getElementById("budgetValue");
    if (budgetInput && budgetValue) {
        budgetInput.addEventListener("input", (e) => {
            const val = parseInt(e.target.value);
            if (val === 300000) {
                budgetValue.textContent = "Any";
            } else {
                budgetValue.textContent = "₹" + new Intl.NumberFormat('en-IN').format(val);
            }
        });
    }
});
</script>
<?php include('bottom.php'); ?>
</body>
</html>