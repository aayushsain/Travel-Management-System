<?php
// C:\travel\Admin\viewenquiry.php
include('function.php');
check_login();

// Fetch enquiries and package details
$s = "SELECT e.*, p.Packname FROM enquiry e JOIN package p ON e.Packageid = p.Packid ORDER BY e.Enquiryid DESC";
$result = mysqli_query($cn, $s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - View Enquiries</title>
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
        
        <form method="post">
            <div class="tableshadow-card view-table-card">
                <div class="toptd" style="margin-top: 0; border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important; padding-bottom: 1.25rem !important;">Customer Enquiries</div>
                <div class="table-responsive-wrapper">
                    <table border="0" align="center" width="100%" class="wide-table">
                        <thead>
                            <tr>
                                <td style="font-weight:bold; font-size: 0.8rem;">Package</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">ID</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Customer</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Gender</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Mobile</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Email</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Days</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Kids</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Adults</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Message</td>
                                <td style="font-weight:bold; font-size: 0.8rem;">Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $status = trim($data['Statusfield']);
                                    $badgeClass = '';
                                    $statusLabel = '';
                                    $actionLink = '';
                                    
                                    if (strtolower($status) === 'confirm' || strtolower($status) === 'confirmed') {
                                        $badgeHtml = '<span style="background: rgba(20, 241, 149, 0.1); color: var(--success); border: 1px solid rgba(20, 241, 149, 0.2); padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700; font-size: 0.75rem;"><i class="fa-solid fa-circle-check" style="margin-right:4px;"></i>Confirmed</span>';
                                    } else {
                                        // Pending: Show action link to confirm status
                                        $badgeHtml = '<a href="chstatus.php?eid=' . (int)$data['Enquiryid'] . '" style="background: rgba(245, 158, 11, 0.1) !important; color: #F59E0B !important; border: 1px solid rgba(245, 158, 11, 0.2) !important; padding: 0.25rem 0.6rem !important; border-radius: 6px !important; font-weight: 700 !important; font-size: 0.75rem !important; display: inline-flex !important; align-items: center !important;" title="Click to Confirm Enquiry"><i class="fa-solid fa-circle-notch fa-spin" style="margin-right:4px;"></i>Pending</a>';
                                    }
                                    
                                    echo '<tr>';
                                    echo '<td>' . h($data['Packname']) . '</td>';
                                    echo '<td>' . (int)$data['Packageid'] . '</td>';
                                    echo '<td>' . h($data['Name']) . '</td>';
                                    echo '<td>' . h($data['Gender']) . '</td>';
                                    echo '<td>' . h($data['Mobileno']) . '</td>';
                                    echo '<td>' . h($data['Email']) . '</td>';
                                    echo '<td>' . (int)$data['NoofDays'] . '</td>';
                                    echo '<td>' . (int)$data['Child'] . '</td>';
                                    echo '<td>' . (int)$data['Adults'] . '</td>';
                                    echo '<td style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' . h($data['Message']) . '">' . h($data['Message']) . '</td>';
                                    echo '<td>' . $badgeHtml . '</td>';
                                    echo '</tr>';
                                }
                            }
                            mysqli_close($cn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>