<?php
// C:\travel\Admin\left.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$usertype = isset($_SESSION["usertype"]) ? $_SESSION["usertype"] : "";
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar-wrapper">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-plane-departure text-info"></i>
        </div>
        <div class="brand-name">
            <span class="brand-title">VoyageQuest</span>
            <span class="brand-subtitle">Admin Workspace</span>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <!-- Dashboard Overview -->
        <div class="menu-category">Overview</div>
        <a href="index.php" class="menu-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <!-- Category Operations -->
        <div class="menu-category">Categories</div>
        <div class="menu-group">
            <a href="addcategory.php" class="menu-item <?php echo ($current_page == 'addcategory.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-folder-plus"></i>
                <span>Add Category</span>
            </a>
            <a href="viewcategory.php" class="menu-item <?php echo ($current_page == 'viewcategory.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-folder-tree"></i>
                <span>View Categories</span>
            </a>
            <?php if ($usertype == "Admin") { ?>
                <a href="updatecategory.php" class="menu-item <?php echo ($current_page == 'updatecategory.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-folder-open"></i>
                    <span>Update Category</span>
                </a>
                <a href="deletecategory.php" class="menu-item <?php echo ($current_page == 'deletecategory.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-folder-minus"></i>
                    <span>Delete Category</span>
                </a>
            <?php } ?>
        </div>

        <!-- Subcategories Operations -->
        <div class="menu-category">Subcategories</div>
        <div class="menu-group">
            <a href="addsubcategory.php" class="menu-item <?php echo ($current_page == 'addsubcategory.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-tags"></i>
                <span>Add Subcategory</span>
            </a>
            <a href="viewsubcategory.php" class="menu-item <?php echo ($current_page == 'viewsubcategory.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-list"></i>
                <span>View Subcategories</span>
            </a>
            <?php if ($usertype == "Admin") { ?>
                <a href="updatesubcategory.php" class="menu-item <?php echo ($current_page == 'updatesubcategory.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-tag"></i>
                    <span>Update Subcategory</span>
                </a>
                <a href="deletesubcategory.php" class="menu-item <?php echo ($current_page == 'deletesubcategory.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete Subcategory</span>
                </a>
            <?php } ?>
        </div>

        <!-- Packages Operations -->
        <div class="menu-category">Packages</div>
        <div class="menu-group">
            <a href="addpackage.php" class="menu-item <?php echo ($current_page == 'addpackage.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-archive"></i>
                <span>Add Package</span>
            </a>
            <a href="viewpackage.php" class="menu-item <?php echo ($current_page == 'viewpackage.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-cubes"></i>
                <span>View Packages</span>
            </a>
            <?php if ($usertype == "Admin") { ?>
                <a href="updatepackage.php" class="menu-item <?php echo ($current_page == 'updatepackage.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Update Package</span>
                </a>
                <a href="deletepackage.php" class="menu-item <?php echo ($current_page == 'deletepackage.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-box-open"></i>
                    <span>Delete Package</span>
                </a>
            <?php } ?>
        </div>

        <!-- User Management (Admin Only) -->
        <?php if ($usertype == "Admin") { ?>
            <div class="menu-category">Users</div>
            <div class="menu-group">
                <a href="adduser.php" class="menu-item <?php echo ($current_page == 'adduser.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add User</span>
                </a>
                <a href="updateuser.php" class="menu-item <?php echo ($current_page == 'updateuser.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Update User</span>
                </a>
                <a href="deleteuser.php" class="menu-item <?php echo ($current_page == 'deleteuser.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-xmark"></i>
                    <span>Delete User</span>
                </a>
            </div>
        <?php } ?>

        <!-- Customer Request Enquiries -->
        <div class="menu-category">Enquiries</div>
        <a href="viewenquiry.php" class="menu-item <?php echo ($current_page == 'viewenquiry.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span>View Enquiries</span>
        </a>
    </div>
    
    <!-- Collapsible Toggle Action -->
    <button class="sidebar-toggle" id="sidebarToggleBtn" type="button">
        <i class="fa-solid fa-angles-left"></i>
        <span>Collapse Sidebar</span>
    </button>
</div>

<!-- Simple Script for Sidebar Toggle -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const sidebarCol = document.querySelector(".col-sm-3");
    const headerTop = document.querySelector(".admin-header");
    const bodyElem = document.body;
    
    // Check local storage for sidebar collapsed state
    if (localStorage.getItem("sidebar-collapsed") === "true") {
        if (sidebarCol) sidebarCol.classList.add("collapsed");
        if (headerTop) headerTop.classList.add("expanded-header");
        bodyElem.classList.add("sidebar-collapsed");
        if (toggleBtn) {
            const icon = toggleBtn.querySelector("i");
            const span = toggleBtn.querySelector("span");
            if (icon) icon.className = "fa-solid fa-angles-right";
            if (span) span.textContent = "Expand";
        }
    }

    if (toggleBtn && sidebarCol) {
        toggleBtn.addEventListener("click", function() {
            sidebarCol.classList.toggle("collapsed");
            const isCollapsed = sidebarCol.classList.contains("collapsed");
            localStorage.setItem("sidebar-collapsed", isCollapsed);
            
            const icon = toggleBtn.querySelector("i");
            const span = toggleBtn.querySelector("span");
            
            if (isCollapsed) {
                if (headerTop) headerTop.classList.add("expanded-header");
                bodyElem.classList.add("sidebar-collapsed");
                if (icon) icon.className = "fa-solid fa-angles-right";
                if (span) span.textContent = "Expand";
            } else {
                if (headerTop) headerTop.classList.remove("expanded-header");
                bodyElem.classList.remove("sidebar-collapsed");
                if (icon) icon.className = "fa-solid fa-angles-left";
                if (span) span.textContent = "Collapse Sidebar";
            }
        });
    }
});
</script>