<?php
// C:\travel\enquiry.php
// Booking Enquiry Page — uses prepared statements for all DB operations

include('function.php');
$pid = isset($_GET["pid"]) ? (int)$_GET["pid"] : 0;

$server_error = null;
$error_field = null;
$booking_success = false;

if (isset($_POST["sbmt"])) {
    // CSRF protection
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $safe_pid    = isset($_POST["pid"]) ? (int)$_POST["pid"] : 0;
    $name        = trim($_POST["t1"] ?? '');
    $gender      = in_array($_POST["r1"] ?? '', ['Male', 'Female', 'Other']) ? $_POST["r1"] : 'Male';
    $mobile      = trim($_POST["t2"] ?? '');
    $email       = trim($_POST["t3"] ?? '');
    $days_raw    = $_POST["t4"] ?? '';
    $children_raw = $_POST["t5"] ?? '';
    $adults_raw  = $_POST["t6"] ?? '';
    $message     = trim($_POST["t7"] ?? '');

    // Validate Package ID exists in DB
    $pkg_check = prepare_query($cn, "SELECT Packid FROM package WHERE Packid = ?", "i", [$safe_pid]);
    if (!$pkg_check || mysqli_num_rows($pkg_check) === 0) {
        $server_error = "Invalid travel package selected. Please select a valid package.";
        $error_field = "pid";
    } elseif ($name === '' || !preg_match('/^[a-zA-Z ]{3,50}$/', $name)) {
        $server_error = "Please enter a valid name (3-50 letters and spaces only).";
        $error_field = "t1";
    } elseif ($mobile === '' || !preg_match('/^[0-9]{10,12}$/', $mobile)) {
        $server_error = "Please enter a valid 10-12 digit mobile number.";
        $error_field = "t2";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $server_error = "Please enter a valid email address.";
        $error_field = "t3";
    } elseif ($days_raw === '' || !is_numeric($days_raw) || (int)$days_raw < 1 || (int)$days_raw > 365) {
        $server_error = "Number of Days must be a number between 1 and 365.";
        $error_field = "t4";
    } elseif ($adults_raw === '' || !is_numeric($adults_raw) || (int)$adults_raw < 1 || (int)$adults_raw > 20) {
        $server_error = "Number of Adults must be a number between 1 and 20.";
        $error_field = "t6";
    } elseif ($children_raw !== '' && (!is_numeric($children_raw) || (int)$children_raw < 0 || (int)$children_raw > 20)) {
        $server_error = "Number of Children must be a number between 0 and 20.";
        $error_field = "t5";
    } elseif (strlen($message) < 10) {
        $server_error = "Please enter a detailed message (at least 10 characters).";
        $error_field = "t7";
    } else {
        $days = (int)$days_raw;
        $children = $children_raw !== '' ? (int)$children_raw : 0;
        $adults = (int)$adults_raw;

        // Use prepared statement — prevents SQL injection
        $ok = prepare_exec($cn,
            "INSERT INTO enquiry(Packageid,Name,Gender,Mobileno,Email,NoofDays,Child,Adults,Message,Statusfield)
             VALUES(?,?,?,?,?,?,?,?,?,'Pending')",
             "issssiiss",
            [$safe_pid, $name, $gender, $mobile, $email, $days, $children, $adults, $message]
        );
        
        if ($ok) {
            $booking_success = true;
        } else {
            $server_error = "There was an error submitting your enquiry. Please try again.";
        }
    }
}

if ($booking_success) {
    // Render a premium cinematic overlay with checkmark animation
    echo "
    <style>
    @keyframes pulseGold {
        0% { box-shadow: 0 0 0 0 rgba(244,185,66,0.5); }
        70% { box-shadow: 0 0 0 20px rgba(244,185,66,0); }
        100% { box-shadow: 0 0 0 0 rgba(244,185,66,0); }
    }
    .success-ring-anim {
        animation: pulseGold 2s infinite;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        showCustomAlert('Journey Reserved', 'Your booking enquiry has been received successfully! A travel expert will contact you within 24 hours.', false);
    });
    </script>
    ";
}

if ($server_error) {
    if ($error_field && $error_field !== 'pid') {
        echo "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            showInlineError('" . addslashes($error_field) . "', '" . addslashes($server_error) . "');
        });
        </script>
        ";
    }
}

// Fetch package details using prepared statement
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
    if ($result) {
        $package_data = mysqli_fetch_assoc($result);
    }
}

// Fetch categories for sidebar
$cat_result = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $package_data ? 'Book ' . h($package_data['Packname']) . ' | VoyageQuest' : 'Book Package | VoyageQuest'; ?></title>
    <meta name="description" content="<?php echo $package_data ? 'Book the ' . h($package_data['Packname']) . ' package with VoyageQuest. Fill in your travel details and our experts will contact you within 24 hours.' : 'Book a luxury travel package with VoyageQuest.'; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
</head>
<body>
<?php include('top.php'); ?>

<section class="ta-container">
    <div class="ta-grid-sidebar">
        <!-- Sidebar Category Navigation -->
        <aside class="ta-sidebar" data-aos="fade-right">
            <h3 class="ta-sidebar-title">Categories</h3>
            <ul class="ta-sidebar-menu">
                <?php
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    echo '<li><a href="subcat.php?catid=' . (int)$cat['Cat_id'] . '" class="ta-sidebar-link">' . h($cat['Cat_name']) . '</a></li>';
                }
                ?>
            </ul>
        </aside>

        <!-- Booking Form -->
        <div data-aos="fade-left">
            <?php if (!$package_data): ?>
            <div class="ta-detail-card">
                <h3 style="text-align:center; color: var(--text-gray);">Package selection invalid. <a href="category.php" style="color: var(--gold);">Browse Packages</a></h3>
            </div>
            <?php else: ?>
            <div class="ta-form-card" style="max-width: 100%; margin: 0;">
                <!-- Package Preview Banner -->
                <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 80px; height: 80px; border-radius: 16px; overflow: hidden; flex-shrink: 0;">
                        <img src="Admin/packimages/<?php echo h($package_data['Pic1']); ?>" 
                             alt="<?php echo h($package_data['Packname']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.src='images/travelimage.jpg'">
                    </div>
                    <div>
                        <p style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gold); font-weight: 700; margin-bottom: 0.25rem;">Booking Enquiry</p>
                        <h3 style="font-family: var(--font-serif); font-size: 1.6rem; font-weight: 800; margin: 0; line-height: 1.2;"><?php echo h($package_data['Packname']); ?></h3>
                        <p style="color: var(--text-gray); font-size: 0.9rem; margin: 0.25rem 0 0 0;">Starting from <strong style="color: var(--gold);">₹<?php echo number_format((double)$package_data['Packprice']); ?></strong> per traveler</p>
                    </div>
                </div>

                <!-- Multi-step Booking Form -->
                <form method="post" id="bookingForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>">

                    <?php if ($server_error): ?>
                    <div class="ta-alert-banner" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 16px; padding: 1.25rem; margin-bottom: 2rem; color: #ff6b6b; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong style="color: #fff; display: block; margin-bottom: 0.25rem; font-weight: 700;">Validation Error</strong>
                            <span style="font-size: 0.9rem;"><?php echo h($server_error); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Step 1: Personal Information -->
                    <div class="booking-section" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold); color: #0a1f44; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;">1</div>
                            <h4 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Personal Information</h4>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t1">Full Name *</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t1" name="t1" type="text" 
                                           required minlength="3" maxlength="50" 
                                           pattern="[a-zA-Z ]{3,50}"
                                           title="Please enter 3-50 characters (letters and spaces only)"
                                           placeholder="First and last name"
                                           autocomplete="name"
                                           value="<?php echo isset($_POST['t1']) ? h($_POST['t1']) : ''; ?>">
                                    <div class="error-feedback" id="err-t1"></div>
                                </div>
                            </div>
                            <div class="ta-form-group">
                                <label class="ta-form-label">Gender *</label>
                                <div style="display: flex; gap: 1.5rem; margin-top: 0.8rem; flex-wrap: wrap;">
                                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                        <input type="radio" name="r1" value="Male" <?php echo (!isset($_POST['r1']) || $_POST['r1'] === 'Male') ? 'checked' : ''; ?> style="accent-color: var(--gold);"> Male
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                        <input type="radio" name="r1" value="Female" <?php echo (isset($_POST['r1']) && $_POST['r1'] === 'Female') ? 'checked' : ''; ?> style="accent-color: var(--gold);"> Female
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600;">
                                        <input type="radio" name="r1" value="Other" <?php echo (isset($_POST['r1']) && $_POST['r1'] === 'Other') ? 'checked' : ''; ?> style="accent-color: var(--gold);"> Other
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Contact Details -->
                    <div class="booking-section" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold); color: #0a1f44; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;">2</div>
                            <h4 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Contact Details</h4>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t2">Mobile Number *</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t2" name="t2" type="tel" 
                                           required pattern="[0-9]{10,12}" 
                                           title="Please enter 10-12 digit mobile number"
                                           placeholder="e.g. 9876543210"
                                           autocomplete="tel"
                                           value="<?php echo isset($_POST['t2']) ? h($_POST['t2']) : ''; ?>">
                                    <div class="error-feedback" id="err-t2"></div>
                                </div>
                            </div>
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t3">Email Address *</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t3" name="t3" type="email" 
                                           required placeholder="name@domain.com"
                                           autocomplete="email"
                                           value="<?php echo isset($_POST['t3']) ? h($_POST['t3']) : ''; ?>">
                                    <div class="error-feedback" id="err-t3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Travel Details -->
                    <div class="booking-section" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold); color: #0a1f44; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;">3</div>
                            <h4 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Travel Details</h4>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;" class="travel-details-grid">
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t4">Number of Days *</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t4" name="t4" type="number" 
                                           min="1" max="365" required placeholder="5"
                                           style="text-align: center;"
                                           value="<?php echo isset($_POST['t4']) ? h($_POST['t4']) : ($package_data ? h($package_data['NoofDays'] ?? '5') : '5'); ?>">
                                    <div class="error-feedback" id="err-t4"></div>
                                </div>
                            </div>
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t5">Number of Children</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t5" name="t5" type="number" 
                                           min="0" max="20" placeholder="0"
                                           style="text-align: center;"
                                           value="<?php echo isset($_POST['t5']) ? h($_POST['t5']) : '0'; ?>">
                                    <div class="error-feedback" id="err-t5"></div>
                                </div>
                            </div>
                            <div class="ta-form-group">
                                <label class="ta-form-label" for="t6">Number of Adults *</label>
                                <div class="input-wrapper">
                                    <input class="ta-form-control" id="t6" name="t6" type="number" 
                                           min="1" max="20" required placeholder="1"
                                           style="text-align: center;"
                                           value="<?php echo isset($_POST['t6']) ? h($_POST['t6']) : '1'; ?>">
                                    <div class="error-feedback" id="err-t6"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Message -->
                    <div class="booking-section" style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--gold); color: #0a1f44; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;">4</div>
                            <h4 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Additional Requirements</h4>
                        </div>
                        <div class="ta-form-group">
                            <label class="ta-form-label" for="t7">Enquiry Message *</label>
                            <div class="input-wrapper" style="align-items: flex-start;">
                                <textarea class="ta-form-control" id="t7" name="t7" required
                                          placeholder="Tell us about your travel preferences: hotel class, dietary needs, special requirements, pickup location..."
                                          rows="4"><?php echo isset($_POST['t7']) ? h($_POST['t7']) : ''; ?></textarea>
                                <div class="error-feedback" id="err-t7" style="margin-top: 0.5rem;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div style="background: rgba(244, 185, 66, 0.06); border: 1px solid rgba(244, 185, 66, 0.2); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="font-size: 0.85rem; color: var(--text-gray); margin: 0;">Package starting price</p>
                                <p style="font-size: 1.8rem; font-weight: 800; color: var(--gold); margin: 0;">₹<?php echo number_format((double)$package_data['Packprice']); ?> <span style="font-size: 0.85rem; color: var(--text-gray); font-weight: 400;">per traveler</span></p>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 0.8rem; color: var(--text-gray); margin: 0;">Our team will send</p>
                                <p style="font-size: 0.8rem; color: var(--text-gray); margin: 0;">a custom quote within 24h</p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                        <a href="detail.php?pid=<?php echo $pid; ?>" 
                           class="btn-ta btn-ta-secondary" 
                           style="flex: 1; min-width: 140px; text-align: center;">
                            ← Back to Details
                        </a>
                        <button type="submit" name="sbmt" 
                                class="btn-ta btn-ta-primary" 
                                style="flex: 2; min-width: 200px; border-radius: 30px; padding: 0.95rem; font-size: 1rem;">
                            Submit Booking Enquiry ✈
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Mobile-responsive travel details grid */
@media (max-width: 640px) {
    .travel-details-grid {
        grid-template-columns: 1fr 1fr !important;
    }
    .travel-details-grid .ta-form-group:last-child {
        grid-column: 1 / -1;
    }
}
@media (max-width: 400px) {
    .travel-details-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Premium Inline Form Validation Styles */
.input-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    width: 100%;
}

@media (max-width: 576px) {
    .input-wrapper {
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
    }
}

/* For grid columns where space is limited, default to vertical wrapper to prevent squishing */
.travel-details-grid .input-wrapper {
    flex-direction: column;
    align-items: stretch;
    gap: 0.25rem;
}

.error-feedback {
    color: #ff4d4d;
    font-size: 0.82rem;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: none;
    align-items: center;
    gap: 6px;
    padding: 0.4rem 0.8rem;
    background: rgba(255, 77, 77, 0.08);
    border: 1px solid rgba(255, 77, 77, 0.2);
    border-radius: 8px;
}

.error-feedback.visible {
    display: inline-flex;
    opacity: 1;
    transform: translateX(0);
}

.travel-details-grid .input-wrapper .error-feedback {
    white-space: normal;
    transform: translateY(-5px);
}

.travel-details-grid .input-wrapper .error-feedback.visible {
    transform: translateY(0);
}

@media (max-width: 576px) {
    .error-feedback {
        white-space: normal;
        transform: translateY(-5px);
    }
    .error-feedback.visible {
        transform: translateY(0);
    }
}

.ta-form-control.is-invalid {
    border-color: #ff4d4d !important;
    box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.15) !important;
    background: rgba(255, 77, 77, 0.02) !important;
}
</style>

<script>
// Custom premium glassmorphic alert function (used for success only now)
function showCustomAlert(title, message, isError = false) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(3,7,18,0.92);backdrop-filter:blur(15px);-webkit-backdrop-filter:blur(15px);display:flex;justify-content:center;align-items:center;z-index:99999;opacity:0;transition:opacity 0.4s ease;';
    
    const iconClass = isError ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
    const accentColor = isError ? '#ef4444' : '#F4B942';
    const ringBg = isError ? 'rgba(239,68,68,0.1)' : 'rgba(244,185,66,0.1)';
    const ringBorder = isError ? '2px solid #ef4444' : '2px solid #F4B942';
    const cardBg = 'rgba(10,31,68,0.45)';
    const cardBorder = isError ? '1px solid rgba(239,68,68,0.25)' : '1px solid rgba(244,185,66,0.25)';
    const buttonHtml = isError ? `
        <button id="customAlertDismissBtn" style="background:${accentColor};color:#FFF;border:none;border-radius:24px;padding:0.75rem 2rem;font-weight:700;font-size:0.9rem;cursor:pointer;transition:transform 0.2s, background-color 0.2s;box-shadow:0 4px 15px rgba(239,68,68,0.35);">
            Dismiss
        </button>
    ` : `
        <div style="color:#F4B942;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;">Redirecting to Discover in <span id='countdown'>5</span>s...</div>
    `;

    overlay.innerHTML = `
        <div style="background:${cardBg};border:${cardBorder};border-radius:32px;padding:3.5rem 2.5rem;text-align:center;max-width:440px;width:90%;box-shadow:0 30px 60px rgba(0,0,0,0.8);transform:scale(0.85);transition:transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width:88px;height:88px;border-radius:50%;background:${ringBg};border:${ringBorder};display:flex;align-items:center;justify-content:center;color:${accentColor};font-size:3rem;margin:0 auto 2rem;position:relative;">
                <i class="${iconClass}"></i>
            </div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:800;color:#FFF;margin-bottom:0.75rem;">${title}</h3>
            <p style="color:var(--text-gray);margin-bottom:2rem;font-size:0.95rem;line-height:1.75;">${message}</p>
            ${buttonHtml}
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.style.opacity = '1';
        overlay.querySelector('div').style.transform = 'scale(1)';
    }, 50);

    if (isError) {
        const dismissBtn = document.getElementById("customAlertDismissBtn");
        dismissBtn.addEventListener("click", function() {
            overlay.style.opacity = '0';
            overlay.querySelector('div').style.transform = 'scale(0.85)';
            setTimeout(() => {
                overlay.remove();
            }, 400);
        });
        dismissBtn.addEventListener("mouseover", function() {
            this.style.backgroundColor = '#dc2626';
            this.style.transform = 'translateY(-2px)';
        });
        dismissBtn.addEventListener("mouseout", function() {
            this.style.backgroundColor = '#ef4444';
            this.style.transform = 'translateY(0)';
        });
    } else {
        let timeLeft = 5;
        const interval = setInterval(() => {
            timeLeft--;
            const countSpan = document.getElementById('countdown');
            if (countSpan) countSpan.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(interval);
                window.location.href = 'index.php';
            }
        }, 1000);
    }
}

// Inline validation helpers
function showInlineError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    const errorEl = document.getElementById('err-' + fieldId);
    if (errorEl) {
        errorEl.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ` + message;
        errorEl.classList.add('visible');
    }
    field.classList.add('is-invalid');
}

function clearInlineError(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    const errorEl = document.getElementById('err-' + fieldId);
    if (errorEl) {
        errorEl.classList.remove('visible');
        // Clear message after animation finishes to prevent content pop
        setTimeout(() => {
            if (!errorEl.classList.contains('visible')) {
                errorEl.innerHTML = '';
            }
        }, 350);
    }
    field.classList.remove('is-invalid');
}

document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("bookingForm");
    if (form) {
        const fields = {
            t1: {
                el: document.getElementById("t1"),
                validate: function(val) {
                    if (val === "") return "Full Name is required.";
                    if (!/^[a-zA-Z ]{3,50}$/.test(val)) return "3-50 letters and spaces only.";
                    return null;
                }
            },
            t2: {
                el: document.getElementById("t2"),
                validate: function(val) {
                    if (val === "") return "Mobile Number is required.";
                    if (!/^[0-9]{10,12}$/.test(val)) return "10 to 12 digits required.";
                    return null;
                }
            },
            t3: {
                el: document.getElementById("t3"),
                validate: function(val) {
                    if (val === "") return "Email Address is required.";
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) return "Invalid email address.";
                    return null;
                }
            },
            t4: {
                el: document.getElementById("t4"),
                validate: function(val) {
                    if (val === "") return "Required.";
                    const num = parseInt(val);
                    if (isNaN(num) || num < 1 || num > 365) return "Must be 1 - 365 days.";
                    return null;
                }
            },
            t5: {
                el: document.getElementById("t5"),
                validate: function(val) {
                    if (val === "") return null;
                    const num = parseInt(val);
                    if (isNaN(num) || num < 0 || num > 20) return "Must be 0 - 20 children.";
                    return null;
                }
            },
            t6: {
                el: document.getElementById("t6"),
                validate: function(val) {
                    if (val === "") return "Required.";
                    const num = parseInt(val);
                    if (isNaN(num) || num < 1 || num > 20) return "Must be 1 - 20 adults.";
                    return null;
                }
            },
            t7: {
                el: document.getElementById("t7"),
                validate: function(val) {
                    if (val === "") return "Enquiry Message is required.";
                    if (val.length < 10) return "At least 10 characters required.";
                    return null;
                }
            }
        };

        function checkField(fieldId) {
            const field = fields[fieldId];
            if (!field || !field.el) return true;
            const val = field.el.value.trim();
            const errorMsg = field.validate(val);
            if (errorMsg) {
                showInlineError(fieldId, errorMsg);
                return false;
            } else {
                clearInlineError(fieldId);
                return true;
            }
        }

        // Real-time validation on input and blur
        Object.keys(fields).forEach(fieldId => {
            const field = fields[fieldId].el;
            if (field) {
                field.addEventListener("input", function() {
                    // Only validate dynamically if user had already failed or has entered text
                    if (field.classList.contains("is-invalid") || field.value.trim().length > 0) {
                        checkField(fieldId);
                    }
                });
                field.addEventListener("blur", function() {
                    checkField(fieldId);
                });
            }
        });

        form.addEventListener("submit", function(event) {
            let hasErrors = false;
            let firstInvalidField = null;

            Object.keys(fields).forEach(fieldId => {
                const isValid = checkField(fieldId);
                if (!isValid) {
                    hasErrors = true;
                    if (!firstInvalidField) {
                        firstInvalidField = fields[fieldId].el;
                    }
                }
            });

            if (hasErrors) {
                event.preventDefault();
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
                return false;
            }

            // Disable submit button to prevent double submits
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Processing Request... <i class="fa-solid fa-spinner fa-spin"></i>';
            }
        });
    }
});
</script>

<?php include('bottom.php'); ?>
</body>
</html>
