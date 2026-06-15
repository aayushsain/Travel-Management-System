<?php
// C:\travel\Admin\viewcategory.php
include('function.php');
check_login();

// Fetch categories
$s = "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_id";
$result = mysqli_query($cn, $s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - View Categories</title>
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
                Categories Overview
            </h2>
            <a href="addcategory.php" class="btn btn-preview-site" style="background: rgba(0, 212, 255, 0.1) !important; border-color: rgba(0, 212, 255, 0.2) !important; color: var(--primary) !important;">
                <i class="fa-solid fa-circle-plus"></i> Add Category
            </a>
        </div>
        
        <div class="tableshadow-card view-table-card">
            <div class="toptd" style="margin-top: 0; border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important; padding-bottom: 1.25rem !important;">Active Categories</div>
            <div class="table-responsive-wrapper">
                <table border="0" align="center" width="100%">
                    <thead>
                        <tr>
                            <td style="font-weight:bold; font-size: 0.85rem;">Category ID</td>
                            <td style="font-weight:bold; font-size: 0.85rem;">Category Name</td>
                            <?php if ($_SESSION["usertype"] == "Admin") { ?>
                                <td style="font-weight:bold; font-size: 0.85rem; text-align:right;">Actions</td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . (int)$data['Cat_id'] . '</td>';
                                echo '<td>' . h($data['Cat_name']) . '</td>';
                                if ($_SESSION["usertype"] == "Admin") {
                                    echo '<td style="text-align:right;">';
                                    echo '<a href="updatecategory.php?cid=' . (int)$data['Cat_id'] . '" class="btn-edit" style="margin-right:0.5rem; font-size:0.8rem; padding:0.25rem 0.5rem;">Edit</a>';
                                    echo '<a href="deletecategory.php?cid=' . (int)$data['Cat_id'] . '" class="btn-delete" style="font-size:0.8rem; padding:0.25rem 0.5rem; background:rgba(239, 68, 68, 0.1) !important; color:#EF4444 !important; border-color:rgba(239, 68, 68, 0.2) !important;">Delete</a>';
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }
                        }
                        mysqli_close($cn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>