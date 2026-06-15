<?php
// C:\travel\Admin\addsubcategory.php
include('function.php');
check_login();

$message = '';
$messageType = '';

// Secure Image Upload Helper for subcategory
function upload_image_subcat($file_post_name, $target_dir = "subcatimages/") {
    if (!isset($_FILES[$file_post_name]) || $_FILES[$file_post_name]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'error' => 'No file uploaded or error in upload.'];
    }
    
    $file = $_FILES[$file_post_name];
    $filename = basename($file["name"]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Check file size (max 5MB)
    if ($file["size"] > 5000000) {
        return ['status' => false, 'error' => 'File size exceeds 5MB limit.'];
    }
    
    // Check if actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['status' => false, 'error' => 'Uploaded file is not a valid image.'];
    }
    
    // Check MIME type
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($check['mime'], $allowed_mimes)) {
        return ['status' => false, 'error' => 'Only JPG, JPEG, PNG, GIF, and WEBP formats are allowed.'];
    }
    
    // Check extension
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed_exts)) {
        return ['status' => false, 'error' => 'Only JPG, JPEG, PNG, GIF, and WEBP extensions are allowed.'];
    }
    
    // Ensure unique name
    $unique_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_file = $target_dir . $unique_name;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $unique_name];
    } else {
        return ['status' => false, 'error' => 'Failed to save uploaded file.'];
    }
}

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST["t1"] ?? '');
    $category = isset($_POST["t2"]) ? (int)$_POST["t2"] : 0;
    $detail = trim($_POST["t4"] ?? '');
    
    if (strlen($name) < 2 || strlen($name) > 50) {
        $message = "Subcategory name must be between 2 and 50 characters.";
        $messageType = "danger";
    } elseif ($category <= 0) {
        $message = "Please select a valid category.";
        $messageType = "danger";
    } else {
        $upload = upload_image_subcat('t3');
        if (!$upload['status']) {
            $message = $upload['error'];
            $messageType = "danger";
        } else {
            $ok = prepare_exec($cn,
                "INSERT INTO subcategory (Subcatname, Catid, Pic, Detail) VALUES (?, ?, ?, ?)",
                "siss",
                [$name, $category, $upload['filename'], $detail]
            );
            if ($ok) {
                echo "<script>alert('Subcategory saved successfully!'); window.location.href='viewsubcategory.php';</script>";
                exit;
            } else {
                $message = "Failed to save subcategory to database.";
                $messageType = "danger";
            }
        }
    }
}

// Fetch categories for selection
$categories_result = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Add Subcategory</title>
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
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="max-width: 500px; margin: 0 auto 1.5rem auto; border-radius: 12px;">
                <strong><i class="fa-solid fa-circle-exclamation"></i></strong> <?php echo h($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Add Subcategory</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Subcategory Name</td>
                        <td>
                            <input type="text" name="t1" required 
                                   value="<?php echo isset($_POST['t1']) ? h($_POST['t1']) : ''; ?>"
                                   pattern="[a-zA-Z0-9 _-]{2,50}" 
                                   title="Subcategory name must be between 2 and 50 characters" 
                                   placeholder="e.g. Kyoto Pilgrimage" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Select Category</td>
                        <td>
                            <select name="t2" required>
                                <option value="">Select Category</option>
                                <?php
                                if ($categories_result) {
                                    while ($cat = mysqli_fetch_assoc($categories_result)) {
                                        $selected = (isset($_POST['t2']) && $_POST['t2'] == $cat['Cat_id']) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Upload Picture</td>
                        <td>
                            <input type="file" name="t3" required accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Details / Description</td>
                        <td>
                            <textarea name="t4" required rows="4" placeholder="Briefly describe this region/subcategory..."><?php echo isset($_POST['t4']) ? h($_POST['t4']) : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Save Subcategory" name="sbmt" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>
<?php mysqli_close($cn); ?>
