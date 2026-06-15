<?php include('function.php'); check_login(); ?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>VoyageQuest - Admin Dashboard</title>
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<link href="../css/bootstrap.css" rel='stylesheet' type='text/css'/>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link href="style.css?v=2.0" rel="stylesheet" type="text/css" />
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

<?php include('top.php'); ?>
<!--/sticky-->
<div class="container">
<div class="col-sm-3">
<?php include('left.php'); ?>
</div>
<div class="col-sm-9 fade-in-up">
    <?php include('stats.php'); ?>
    
    <div class="travel-hero fade-in-up">
        <div class="hero-layout">
            <div class="hero-text-content" style="text-align: left;">
                <span class="hero-subtitle">Operational Command Center</span>
                <h1 class="hero-title">Discover & Manage Extraordinary Travel Experiences</h1>
                <p class="hero-description">
                    Elevate your business workflows and oversee curated travel packages. Coordinate luxury destination categories, modify exotic subcategories, and customize package pricing parameters with real-time analytics indicators.
                </p>
                <div class="hero-quote">
                    "To travel is to live, to explore is to discover the extraordinary."
                </div>
            </div>
            
            <div class="hero-globe-wrapper">
                <svg class="globe-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="48" fill="none" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2 3" opacity="0.3"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="0.8" opacity="0.5"/>
                    <ellipse cx="50" cy="50" rx="40" ry="14" fill="none" stroke="currentColor" stroke-width="0.6" opacity="0.4"/>
                    <ellipse cx="50" cy="50" rx="40" ry="26" fill="none" stroke="currentColor" stroke-width="0.5" opacity="0.25"/>
                    <ellipse cx="50" cy="50" rx="14" ry="40" fill="none" stroke="currentColor" stroke-width="0.6" opacity="0.4"/>
                    <ellipse cx="50" cy="50" rx="26" ry="40" fill="none" stroke="currentColor" stroke-width="0.5" opacity="0.25"/>
                    <line x1="50" y1="5" x2="50" y2="95" stroke="currentColor" stroke-width="0.5" stroke-dasharray="1 2" opacity="0.4"/>
                    <circle cx="28" cy="35" r="2.5" fill="#14F195" filter="drop-shadow(0 0 4px #14F195)"/>
                    <circle cx="75" cy="45" r="2.2" fill="#00D4FF" filter="drop-shadow(0 0 4px #00D4FF)"/>
                    <circle cx="45" cy="72" r="3" fill="#7C3AED" filter="drop-shadow(0 0 4px #7C3AED)"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 1rem;">
        <p style="color: var(--text-secondary); font-size: 0.9rem;">
            Quick Status: All systems operational. Connected to secure database engine.
        </p>
    </div>
</div>

</div>
<?php include('bottom.php'); ?>
</body>
</html>