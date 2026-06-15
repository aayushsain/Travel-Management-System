<?php
// C:\travel\Admin\top.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Global Resources Injected by top.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="style.css?v=2.0" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Global Script for Cinematic Particles and Custom Dialogs -->
<script>
// Intercept all native alerts and replace with high-end glassmorphic dialogs
window.alert = function(message) {
    let icon = "fa-circle-info text-info";
    let title = "Notification";
    let btnClass = "btn-secondary";
    let accentColor = "var(--primary)";
    
    const lowerMsg = message.toLowerCase();
    if (lowerMsg.includes("save") || lowerMsg.includes("success") || lowerMsg.includes("add")) {
        icon = "fa-circle-check text-success";
        title = "Success";
        btnClass = "btn-success";
        accentColor = "var(--success)";
    } else if (lowerMsg.includes("update") || lowerMsg.includes("edit") || lowerMsg.includes("modify")) {
        icon = "fa-circle-check text-info";
        title = "Record Updated";
        btnClass = "btn-info";
        accentColor = "var(--primary)";
    } else if (lowerMsg.includes("delete") || lowerMsg.includes("remove")) {
        icon = "fa-circle-xmark text-danger";
        title = "Record Deleted";
        btnClass = "btn-danger";
        accentColor = "#EF4444";
    } else if (lowerMsg.includes("invalid") || lowerMsg.includes("sorry") || lowerMsg.includes("error") || lowerMsg.includes("failed")) {
        icon = "fa-triangle-exclamation text-warning";
        title = "Alert";
        btnClass = "btn-warning";
        accentColor = "#F59E0B";
    }
    
    const overlay = document.createElement("div");
    overlay.className = "success-overlay";
    overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(3, 7, 18, 0.85); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 99999; animation: fadeIn 0.2s ease-out forwards;";
    
    overlay.innerHTML = `
        <div class="success-card" style="border-color: ${accentColor}40;">
            <div class="success-icon-ring animate-ring" style="color: ${accentColor}; border-color: ${accentColor}60; background: ${accentColor}10;">
                <i class="fa-solid ${icon}"></i>
            </div>
            <h3 style="font-weight: 800; color: #FFF; margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">${title}</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">${message}</p>
            <button type="button" class="btn ${btnClass} px-5 py-2" id="customAlertBtn" style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 8px;">Dismiss</button>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    const dismissBtn = document.getElementById("customAlertBtn");
    if (dismissBtn) {
        dismissBtn.addEventListener("click", () => {
            overlay.style.animation = "fadeOut 0.15s ease-in forwards";
            setTimeout(() => overlay.remove(), 150);
        });
    }
};

class CinematicTravelBg {
    constructor() {
        if (document.getElementById('bg-particles')) return;
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'bg-particles';
        this.canvas.style.position = 'fixed';
        this.canvas.style.top = '0';
        this.canvas.style.left = '0';
        this.canvas.style.width = '100vw';
        this.canvas.style.height = '100vh';
        this.canvas.style.zIndex = '-1';
        this.canvas.style.pointerEvents = 'none';
        this.canvas.style.display = 'block';
        if (document.body.firstChild) {
            document.body.insertBefore(this.canvas, document.body.firstChild);
        } else {
            document.body.appendChild(this.canvas);
        }
        
        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.clouds = [];
        this.routes = [];
        this.time = 0;
        
        this.resize();
        window.addEventListener('resize', () => this.resize());
        this.init();
        this.animate();
    }
    
    resize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    }
    
    init() {
        // 1. Star Particles
        const count = Math.min(Math.floor(window.innerWidth / 30), 60);
        this.particles = [];
        for (let i = 0; i < count; i++) {
            this.particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                radius: Math.random() * 1.8 + 0.5,
                vx: (Math.random() - 0.5) * 0.05,
                vy: (Math.random() - 0.5) * 0.05,
                alpha: Math.random() * 0.6 + 0.2,
                speed: Math.random() * 0.02 + 0.008
            });
        }
        
        // 2. Slow-drifting clouds
        this.clouds = [];
        for (let i = 0; i < 4; i++) {
            this.clouds.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * (this.canvas.height * 0.35),
                r: Math.random() * 140 + 90,
                speed: Math.random() * 0.10 + 0.03,
                alpha: Math.random() * 0.05 + 0.02
            });
        }
        
        // 3. Flight Paths
        this.routes = [];
        this.routes.push({
            x1: 100, y1: 150,
            cx: this.canvas.width * 0.5, cy: 50,
            x2: this.canvas.width - 150, y2: this.canvas.height - 150,
            t: 0,
            speed: 0.0008
        });
        
        this.routes.push({
            x1: 150, y1: this.canvas.height - 100,
            cx: this.canvas.width * 0.4, cy: this.canvas.height * 0.5,
            x2: this.canvas.width - 100, y2: 100,
            t: 0.5,
            speed: 0.0006
        });
    }
    
    getBezierPoint(t, p0, p1, p2) {
        const x = (1 - t) * (1 - t) * p0.x + 2 * (1 - t) * t * p1.x + t * t * p2.x;
        const y = (1 - t) * (1 - t) * p0.y + 2 * (1 - t) * t * p1.y + t * t * p2.y;
        return { x, y };
    }
    
    drawAurora() {
        this.time += 0.0012;
        
        // Aurora 1 (Primary Cyan Glow)
        this.ctx.beginPath();
        const gradient1 = this.ctx.createLinearGradient(0, 0, this.canvas.width, 0);
        gradient1.addColorStop(0, 'rgba(0, 212, 255, 0.08)');
        gradient1.addColorStop(0.5, 'rgba(124, 58, 237, 0.12)');
        gradient1.addColorStop(1, 'rgba(20, 241, 149, 0.08)');
        
        for (let x = 0; x < this.canvas.width; x += 10) {
            const y = this.canvas.height * 0.28 + 
                      Math.sin(x * 0.0012 + this.time) * 60 + 
                      Math.cos(x * 0.0006 - this.time * 0.4) * 30;
            if (x === 0) this.ctx.moveTo(x, y);
            else this.ctx.lineTo(x, y);
        }
        this.ctx.lineTo(this.canvas.width, this.canvas.height);
        this.ctx.lineTo(0, this.canvas.height);
        this.ctx.closePath();
        this.ctx.fillStyle = gradient1;
        this.ctx.fill();
        
        // Aurora 2 (Accent Purple Glow)
        this.ctx.beginPath();
        const gradient2 = this.ctx.createLinearGradient(0, 0, this.canvas.width, 0);
        gradient2.addColorStop(0, 'rgba(124, 58, 237, 0.08)');
        gradient2.addColorStop(0.5, 'rgba(20, 241, 149, 0.10)');
        gradient2.addColorStop(1, 'rgba(0, 212, 255, 0.06)');
        
        for (let x = 0; x < this.canvas.width; x += 10) {
            const y = this.canvas.height * 0.38 + 
                      Math.sin(x * 0.0018 - this.time * 0.7) * 50 + 
                      Math.cos(x * 0.0009 + this.time * 0.3) * 35;
            if (x === 0) this.ctx.moveTo(x, y);
            else this.ctx.lineTo(x, y);
        }
        this.ctx.lineTo(this.canvas.width, this.canvas.height);
        this.ctx.lineTo(0, this.canvas.height);
        this.ctx.closePath();
        this.ctx.fillStyle = gradient2;
        this.ctx.fill();
    }
    
    drawMountains() {
        // Far mountain silhouette
        this.ctx.beginPath();
        this.ctx.fillStyle = 'rgba(10, 15, 30, 0.6)';
        for (let x = 0; x <= this.canvas.width; x += 20) {
            const y = this.canvas.height - 70 + Math.sin(x * 0.002) * 25 + Math.cos(x * 0.0006) * 12;
            if (x === 0) this.ctx.moveTo(x, y);
            else this.ctx.lineTo(x, y);
        }
        this.ctx.lineTo(this.canvas.width, this.canvas.height);
        this.ctx.lineTo(0, this.canvas.height);
        this.ctx.closePath();
        this.ctx.fill();
        
        // Near mountain silhouette
        this.ctx.beginPath();
        this.ctx.fillStyle = 'rgba(5, 7, 15, 0.85)';
        for (let x = 0; x <= this.canvas.width; x += 30) {
            const y = this.canvas.height - 40 + Math.sin(x * 0.0035) * 15 + Math.cos(x * 0.001) * 8;
            if (x === 0) this.ctx.moveTo(x, y);
            else this.ctx.lineTo(x, y);
        }
        this.ctx.lineTo(this.canvas.width, this.canvas.height);
        this.ctx.lineTo(0, this.canvas.height);
        this.ctx.closePath();
        this.ctx.fill();
    }
    
    drawCompassGrid() {
        const cx = this.canvas.width - 200;
        const cy = this.canvas.height - 200;
        
        this.ctx.beginPath();
        this.ctx.arc(cx, cy, 120, 0, Math.PI * 2);
        this.ctx.strokeStyle = 'rgba(0, 212, 255, 0.02)';
        this.ctx.lineWidth = 1;
        this.ctx.stroke();
        
        this.ctx.beginPath();
        this.ctx.arc(cx, cy, 60, 0, Math.PI * 2);
        this.ctx.stroke();
        
        // Draw cross axes
        this.ctx.beginPath();
        this.ctx.moveTo(cx - 135, cy);
        this.ctx.lineTo(cx + 135, cy);
        this.ctx.moveTo(cx, cy - 135);
        this.ctx.lineTo(cx, cy + 135);
        this.ctx.stroke();
        
        // Text coordinates
        this.ctx.fillStyle = 'rgba(0, 212, 255, 0.12)';
        this.ctx.font = '9px monospace';
        this.ctx.fillText("N 35.6762° / E 139.6503°", cx - 60, cy - 145);
        this.ctx.fillText("DESTINATION MARKER [ACTIVE]", cx - 65, cy + 150);
    }
    
    animate() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw Aurora sine waves
        this.drawAurora();
        
        // Draw Clouds
        for (let c of this.clouds) {
            this.ctx.beginPath();
            this.ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(255, 255, 255, ${c.alpha})`;
            this.ctx.fill();
            c.x += c.speed;
            if (c.x - c.r > this.canvas.width) {
                c.x = -c.r;
            }
        }
        
        // Draw Compass rose
        this.drawCompassGrid();
        
        // Draw Mountain silhouettes
        this.drawMountains();
        
        // Draw Star Particles
        for (let p of this.particles) {
            this.ctx.beginPath();
            this.ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(0, 212, 255, ${p.alpha})`;
            this.ctx.fill();
            
            p.alpha += p.speed;
            if (p.alpha > 0.5 || p.alpha < 0.1) {
                p.speed *= -1;
            }
        }
        
        // Draw Flight Paths & Airplanes
        for (let r of this.routes) {
            this.ctx.beginPath();
            this.ctx.moveTo(r.x1, r.y1);
            this.ctx.quadraticCurveTo(r.cx, r.cy, r.x2, r.y2);
            this.ctx.strokeStyle = 'rgba(0, 212, 255, 0.04)';
            this.ctx.lineWidth = 1;
            this.ctx.setLineDash([4, 6]);
            this.ctx.stroke();
            this.ctx.setLineDash([]);
            
            const planePos = this.getBezierPoint(r.t, {x: r.x1, y: r.y1}, {x: r.cx, y: r.cy}, {x: r.x2, y: r.y2});
            
            // Pulse trail
            this.ctx.beginPath();
            this.ctx.arc(planePos.x, planePos.y, 8, 0, Math.PI * 2);
            this.ctx.fillStyle = 'rgba(0, 212, 255, 0.08)';
            this.ctx.fill();
            
            // Plane dot
            this.ctx.beginPath();
            this.ctx.arc(planePos.x, planePos.y, 2.5, 0, Math.PI * 2);
            this.ctx.fillStyle = '#00D4FF';
            this.ctx.fill();
            
            r.t += r.speed;
            if (r.t > 1) {
                r.t = 0;
            }
        }
        
        requestAnimationFrame(() => this.animate());
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new CinematicTravelBg();
    
    // Set dynamic breadcrumb page name
    const path = window.location.pathname;
    const page = path.split("/").pop();
    let pageTitle = "Dashboard";
    
    const pageMap = {
        "index.php": "Dashboard",
        "addcategory.php": "Add Category",
        "viewcategory.php": "View Categories",
        "updatecategory.php": "Update Category",
        "deletecategory.php": "Delete Category",
        "addsubcategory.php": "Add Subcategory",
        "viewsubcategory.php": "View Subcategories",
        "updatesubcategory.php": "Update Subcategory",
        "deletesubcategory.php": "Delete Subcategory",
        "addpackage.php": "Add Package",
        "viewpackage.php": "View Packages",
        "updatepackage.php": "Update Package",
        "deletepackage.php": "Delete Package",
        "adduser.php": "Add User",
        "updateuser.php": "Update User",
        "deleteuser.php": "Delete User",
        "viewenquiry.php": "View Enquiries"
    };
    
    if (pageMap[page]) {
        pageTitle = pageMap[page];
    }
    
    const breadcrumbCurrent = document.getElementById("breadcrumb-current-page");
    if (breadcrumbCurrent) {
        breadcrumbCurrent.textContent = pageTitle;
    }
});
</script>

<!-- Premium Navigation Top Header -->
<header class="admin-header">
    <div class="header-left">
        <!-- Breadcrumb System -->
        <div class="breadcrumb-container">
            <span class="breadcrumb-root">VoyageQuest</span>
            <i class="fa-solid fa-chevron-right breadcrumb-separator"></i>
            <span class="breadcrumb-active" id="breadcrumb-current-page">Dashboard</span>
        </div>
    </div>
    
    <div class="header-right">
        <!-- Live Site Preview -->
        <a href="../index.php" target="_blank" class="btn btn-preview-site">
            <i class="fa-solid fa-globe"></i>
            <span>Preview Site</span>
        </a>
        
        <!-- User Badge -->
        <div class="user-badge">
            <div class="user-avatar">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div class="user-info">
                <span class="user-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'Admin'; ?></span>
                <span class="user-role"><?php echo isset($_SESSION['usertype']) ? htmlspecialchars($_SESSION['usertype'], ENT_QUOTES, 'UTF-8') : 'Administrator'; ?></span>
            </div>
        </div>
        
        <!-- Log Out -->
        <a href="logout.php" class="btn btn-logout-action">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Log Out</span>
        </a>
    </div>
</header>