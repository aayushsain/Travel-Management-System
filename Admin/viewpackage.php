<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>VoyageQuest - View Packages</title>
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

<link href="../css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link href="style.css?v=2.0" rel="stylesheet" type="text/css"/>
<meta name="viewport" content="width=device-width, initial-scale=1">




<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--js--> 
<script src="js/jquery.min.js"></script>

<!--/js-->
<!--animated-css-->
<link href="../css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="../js/wow.min.js"></script>
<script>
 new WOW().init();
</script>
<!--/animated-css-->
</head>
<body>
<!--header-->
<!--sticky-->



<?php include('function.php'); check_login(); ?>



<?php include('top.php'); ?>
<!--/sticky-->
<div class="container">
<div class="col-sm-3">
<?php include('left.php'); ?>
</div>
<div class="col-sm-9 fade-in-up">
    <?php include('stats.php'); ?>





    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h2 style="font-weight: 800; background: linear-gradient(135deg, #FFF 40%, var(--primary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;">
            Curate Travel Experiences
        </h2>
        <a href="addpackage.php" class="btn btn-preview-site" style="background: rgba(0, 212, 255, 0.1) !important; border-color: rgba(0, 212, 255, 0.2) !important; color: var(--primary) !important;">
            <i class="fa-solid fa-circle-plus"></i> Add New Package
        </a>
    </div>

    <div class="package-grid">
    <?php
    $cn = makeconnection();
    $s = "SELECT p.*, c.Cat_name, s.Subcatname 
          FROM package p 
          LEFT JOIN category c ON p.Category = c.Cat_id 
          LEFT JOIN subcategory s ON p.Subcategory = s.Subcatid";
    $result = mysqli_query($cn, $s);
    
    while($data = mysqli_fetch_array($result)) {
        $pack_id = $data[0];
        $pack_name = $data[1];
        $cat_name = $data[9] ?? 'Travel';
        $subcat_name = $data[10] ?? 'Extraordinary Expedition';
        $price = $data[4];
        $pic1 = $data[5];
        $detail = $data[8];
    ?>
        <div class="package-card">
            <div class="package-card-image-wrapper">
                <img class="package-card-image" src="packimages/<?php echo h($pic1); ?>" alt="<?php echo h($pack_name); ?>" onerror="this.src='../images/default.jpg'">
                <span class="package-card-price-tag">$<?php echo h($price); ?></span>
                <span class="package-card-category-tag"><?php echo h($cat_name); ?></span>
            </div>
            
            <div class="package-card-content">
                <h3 class="package-card-title"><?php echo h($pack_name); ?></h3>
                
                <div class="package-card-subcat">
                    <i class="fa-solid fa-location-dot" style="color: var(--primary);"></i>
                    <span><?php echo h($subcat_name); ?></span>
                </div>
                
                <div class="package-card-details">
                    <div class="package-detail-item">
                        <i class="fa-regular fa-clock"></i>
                        <span>6 Days / 5 Nights</span>
                    </div>
                    <div class="package-detail-item">
                        <i class="fa-regular fa-star"></i>
                        <span>4.9 (48 Reviews)</span>
                    </div>
                    <div class="package-detail-item">
                        <i class="fa-solid fa-plane-up"></i>
                        <span>Flight Included</span>
                    </div>
                    <div class="package-detail-item">
                        <i class="fa-solid fa-percent"></i>
                        <span>Popularity: 96%</span>
                    </div>
                </div>
                
                <div class="package-card-actions">
                    <?php if ($_SESSION["usertype"] == "Admin") { ?>
                        <a href="updatepackage.php?pid=<?php echo $pack_id; ?>" class="btn-edit">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="deletepackage.php?pid=<?php echo $pack_id; ?>" class="btn-delete">
                            <i class="fa-solid fa-trash-can"></i> Delete
                        </a>
                    <?php } else { ?>
                        <span class="text-secondary text-center w-100 fs-7">Operator View</span>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
    mysqli_close($cn);
    ?>
    </div>
</div>


</div>
<?php include('bottom.php'); ?>
</body>
</html>