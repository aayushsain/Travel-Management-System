<?php
// C:\travel\Admin\stats.php
if (!isset($cn) || !$cn) {
    $cn = makeconnection();
}

// Get counts
$count_categories = 0;
$res = mysqli_query($cn, "SELECT COUNT(*) FROM category");
if ($res) {
    $row = mysqli_fetch_row($res);
    $count_categories = $row[0];
}

$count_subcategories = 0;
$res = mysqli_query($cn, "SELECT COUNT(*) FROM subcategory");
if ($res) {
    $row = mysqli_fetch_row($res);
    $count_subcategories = $row[0];
}

$count_packages = 0;
$res = mysqli_query($cn, "SELECT COUNT(*) FROM package");
if ($res) {
    $row = mysqli_fetch_row($res);
    $count_packages = $row[0];
}

$count_enquiries = 0;
$res = mysqli_query($cn, "SELECT COUNT(*) FROM enquiry");
if ($res) {
    $row = mysqli_fetch_row($res);
    $count_enquiries = $row[0];
}
?>

<div class="stats-grid mb-5 mt-2 fade-in-up" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; width: 100%;">
    <!-- Card 1: Total Destinations -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(0, 212, 255, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper cyan" style="background: rgba(0, 212, 255, 0.1); color: var(--primary); border: 1px solid rgba(0, 212, 255, 0.2);">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Destinations</span>
                <span class="stat-value counter" data-target="<?php echo $count_subcategories; ?>">0</span>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Active Packages -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(124, 58, 237, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper purple" style="background: rgba(124, 58, 237, 0.1); color: var(--accent); border: 1px solid rgba(124, 58, 237, 0.2);">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Active Packages</span>
                <span class="stat-value counter" data-target="<?php echo $count_packages; ?>">0</span>
            </div>
        </div>
    </div>
    
    <!-- Card 3: Countries Covered -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(20, 241, 149, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper green" style="background: rgba(20, 241, 149, 0.1); color: var(--success); border: 1px solid rgba(20, 241, 149, 0.2);">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Countries</span>
                <span class="stat-value counter" data-target="<?php echo $count_categories; ?>">0</span>
            </div>
        </div>
    </div>
    
    <!-- Card 4: Total Enquiries -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper blue" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Enquiries</span>
                <span class="stat-value counter" data-target="<?php echo $count_enquiries; ?>">0</span>
            </div>
        </div>
    </div>

    <!-- Card 5: Satisfaction -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(245, 158, 11, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper orange" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2);">
                <i class="fa-solid fa-face-smile"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Satisfaction</span>
                <span class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height: 1.1; margin-top: 0.25rem;">98.4%</span>
            </div>
        </div>
    </div>

    <!-- Card 6: Revenue -->
    <div class="stat-card">
        <div class="stat-card-glow" style="background: radial-gradient(circle, rgba(236, 72, 153, 0.05) 0%, transparent 70%);"></div>
        <div class="stat-card-content">
            <div class="stat-icon-wrapper pink" style="background: rgba(236, 72, 153, 0.1); color: #EC4899; border: 1px solid rgba(236, 72, 153, 0.2);">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Revenue</span>
                <span class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height: 1.1; margin-top: 0.25rem;">$412K</span>
            </div>
        </div>
    </div>
</div>

<script>
// Animated counter logic
document.addEventListener("DOMContentLoaded", function() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 800; // ms
        
        if (target === 0) {
            counter.textContent = '0';
            return;
        }
        
        const stepTime = Math.max(Math.floor(duration / target), 15);
        let current = 0;
        
        const timer = setInterval(() => {
            current += Math.ceil(target / (duration / stepTime));
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = current;
            }
        }, stepTime);
    });
});
</script>
