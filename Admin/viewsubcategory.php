<?php
// C:\travel\Admin\viewsubcategory.php
include('function.php');
check_login();

// Fetch subcategories joined with categories
$s = "SELECT s.Subcatid, s.Subcatname, s.Pic, s.Detail, c.Cat_name 
      FROM subcategory s 
      LEFT JOIN category c ON s.Catid = c.Cat_id 
      ORDER BY s.Subcatid DESC";
$result = mysqli_query($cn, $s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - View Subcategories</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="style.css?v=2.0" rel="stylesheet" type="text/css"/>
</head>
<body>

<?php include('top.php'); ?>

<div class="container">
    <div class="col-sm-3">
        <?php include('left.php'); ?>
    </div>
    
    <div class="col-sm-9 fade-in-up">
        <?php include('stats.php'); ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <h2 style="font-weight: 800; background: linear-gradient(135deg, #FFF 40%, var(--primary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;">
                Subcategories Overview
            </h2>
            <a href="addsubcategory.php" class="btn btn-preview-site" style="background: rgba(0, 212, 255, 0.1) !important; border-color: rgba(0, 212, 255, 0.2) !important; color: var(--primary) !important;">
                <i class="fa-solid fa-circle-plus"></i> Add Subcategory
            </a>
        </div>
        
        <div class="package-grid">
            <?php
            if ($result) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $subcat_id = $data['Subcatid'];
                    $subcat_name = $data['Subcatname'];
                    $pic = $data['Pic'];
                    $detail = $data['Detail'];
                    $cat_name = $data['Cat_name'] ?? 'General';
            ?>
                <div class="package-card" style="margin: 0 !important;">
                    <div class="package-card-image-wrapper">
                        <img class="package-card-image" src="subcatimages/<?php echo h($pic); ?>" alt="<?php echo h($subcat_name); ?>" onerror="this.src='../images/default.jpg'">
                        <span class="package-card-category-tag"><?php echo h($cat_name); ?></span>
                        <span class="package-card-price-tag" style="background: rgba(0, 212, 255, 0.8) !important; color: #FFF !important; border-color: rgba(255, 255, 255, 0.1) !important;">ID: <?php echo (int)$subcat_id; ?></span>
                    </div>
                    
                    <div class="package-card-content">
                        <h3 class="package-card-title"><?php echo h($subcat_name); ?></h3>
                        <div class="package-card-subcat">
                            <i class="fa-solid fa-folder-open text-info"></i>
                            <span>Category: <?php echo h($cat_name); ?></span>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;" title="<?php echo h($detail); ?>">
                            <?php echo h($detail); ?>
                        </p>
                        
                        <?php if ($_SESSION["usertype"] == "Admin") { ?>
                            <div class="package-card-actions" style="margin-top: auto;">
                                <a href="updatesubcategory.php?sid=<?php echo (int)$subcat_id; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                                <a href="deletesubcategory.php?sid=<?php echo (int)$subcat_id; ?>" class="btn-delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            }
            mysqli_close($cn);
            ?>
        </div>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>