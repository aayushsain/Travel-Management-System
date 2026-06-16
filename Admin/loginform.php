<?php
// C:\travel\Admin\loginform.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['loginstatus'] = "";

include('function.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["sbmt"]) || isset($_POST["t1"]))) {
    // Verify CSRF token
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $cn = makeconnection();
    $password = $_POST["t2"];
    
    // Use prepared statement to prevent SQL injection
    $result = prepare_query($cn, "SELECT Username, Pwd, Typeofuser FROM users WHERE Username = ?", "s", [$_POST["t1"]]);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        // SECURITY: Only accept password_hash() bcrypt hashes.
        // Plaintext password comparison has been permanently removed.
        // To reset passwords, use: echo password_hash('your_password', PASSWORD_DEFAULT);
        if (password_verify($password, $data['Pwd'])) {
            $_SESSION["Username"] = $data['Username'];
            $_SESSION["usertype"] = $data['Typeofuser'];
            $_SESSION['loginstatus'] = "yes";
            // Regenerate session ID after login to prevent session fixation
            session_regenerate_id(true);
            header("location:index.php");
            exit;
        }
    }
    
    echo "<script>alert('Invalid User Name or Password');</script>";
    mysqli_close($cn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Administrative Gatekeeper</title>
    
    <!-- External Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #030712;
            --surface-dark: #090e1a;
            --primary: #00D4FF;
            --accent: #7C3AED;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.05);
            --primary-gradient: linear-gradient(135deg, #00D4FF 0%, #7C3AED 100%);
            --btn-gradient-hover: linear-gradient(135deg, #00E5FF 0%, #8b5cf6 100%);
            --font-sans: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --font-serif: 'Cormorant Garamond', Georgia, serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-dark) !important;
            color: var(--text-primary) !important;
            font-family: var(--font-sans) !important;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
        }

        /* Split Screen Container */
        .login-split-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            overflow: hidden;
            background-color: var(--bg-dark);
        }

        /* Auth Panel (Left side) */
        .auth-panel {
            flex: 1;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 3.5rem;
            background: #030712;
            position: relative;
            overflow: hidden;
            z-index: 5;
        }

        /* Showcase Panel (Right side) */
        .showcase-panel {
            flex: 1.3;
            background: #050814;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Grid backdrop overlay */
        .grid-backdrop {
            position: absolute;
            inset: 0;
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            z-index: 0;
        }

        /* Floating Aurora Blobs */
        .aurora-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.18;
            mix-blend-mode: screen;
            animation: floatBlob 25s infinite alternate ease-in-out;
            pointer-events: none;
        }
        .aurora-1 {
            width: 350px;
            height: 350px;
            background: var(--accent);
            top: -50px;
            left: -100px;
        }
        .aurora-2 {
            width: 300px;
            height: 300px;
            background: var(--primary);
            bottom: -50px;
            right: -100px;
            animation-delay: -7s;
        }
        .aurora-3 {
            width: 450px;
            height: 450px;
            background: #1e1b4b;
            top: -100px;
            right: -50px;
            animation-delay: -12s;
        }
        .aurora-4 {
            width: 400px;
            height: 400px;
            background: rgba(124, 58, 237, 0.22);
            bottom: -100px;
            left: -50px;
            animation-delay: -18s;
        }

        @keyframes floatBlob {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -60px) scale(1.1); }
            100% { transform: translate(-20px, 40px) scale(0.9); }
        }

        /* Noise Texture Overlay */
        .noise-overlay {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.015;
            pointer-events: none;
            z-index: 1;
        }

        /* Auth Panel Elements */
        .auth-content {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            z-index: 2;
            position: relative;
        }

        .auth-logo-header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 3.5rem;
        }

        .auth-logo-icon {
            width: 44px;
            height: 44px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);
        }

        .logo-svg {
            width: 22px;
            height: 22px;
        }

        .auth-logo-text-group {
            display: flex;
            flex-direction: column;
        }

        .auth-logo-title {
            font-size: 1.35rem;
            font-weight: 850;
            color: #FFF;
            letter-spacing: -0.2px;
            line-height: 1.1;
        }

        .auth-logo-subtitle {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: 1.5px;
            margin-top: 0.1rem;
        }

        .auth-title-section {
            margin-bottom: 2.5rem;
        }

        .auth-title-section h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #FFF;
            margin-bottom: 0.5rem;
        }

        .auth-title-section p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        /* Floating Form Inputs */
        .input-field-wrapper {
            position: relative;
            margin-bottom: 1.75rem;
        }

        .form-input {
            width: 100%;
            padding: 1.25rem 1.25rem 1.25rem 3.25rem;
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            color: #FFF;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus {
            border-color: var(--primary);
            background: rgba(0, 212, 255, 0.015);
            box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.12), 
                        0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.05rem;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-input:focus ~ .input-icon {
            color: var(--primary);
        }

        /* Floating Labels */
        .form-label {
            position: absolute;
            left: 3.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
        }

        .form-input:focus ~ .form-label,
        .form-input:not(:placeholder-shown) ~ .form-label {
            top: -8px;
            left: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            background: #030712;
            padding: 0 0.5rem;
            border-radius: 4px;
        }

        /* Eye toggle */
        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.05rem;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.25s;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #FFF;
        }

        /* Form Actions Row */
        .form-actions-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .remember-me-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-pass-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.25s;
        }

        .forgot-pass-link:hover {
            color: #FFF;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
        }

        /* Premium Submit Button */
        .btn-submit-premium {
            position: relative;
            width: 100%;
            padding: 1.1rem;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #FFF;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.25);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .btn-submit-premium::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--btn-gradient-hover);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 1;
        }

        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(124, 58, 237, 0.45);
        }

        .btn-submit-premium:hover::before {
            opacity: 1;
        }

        .btn-submit-premium:active {
            transform: translateY(0);
        }

        .btn-text, .btn-icon {
            position: relative;
            z-index: 2;
            transition: transform 0.3s;
        }

        .btn-submit-premium:hover .btn-icon {
            transform: translateX(4px);
        }

        /* Security status badge */
        .security-status-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #14F195;
            font-size: 0.8rem;
            margin-top: 1.5rem;
            font-weight: 600;
            opacity: 0.85;
        }

        /* Showcase content on the right */
        .showcase-content {
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 3.5rem;
            z-index: 2;
            position: relative;
        }

        .mock-dashboard {
            background: rgba(13, 18, 32, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 28px;
            padding: 2.25rem;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .mock-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 1.25rem;
        }

        .status-indicator-group {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .status-pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #14F195;
            border-radius: 50%;
            box-shadow: 0 0 12px #14F195;
            animation: statusPulse 2s infinite;
        }

        @keyframes statusPulse {
            0% { transform: scale(0.9); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.8; }
        }

        .status-text {
            font-size: 0.75rem;
            font-weight: 700;
            color: #FFF;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .mock-version {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .mock-metrics-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .mock-metric-card {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 1.25rem;
        }

        .metric-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 850;
            color: #FFF;
            margin: 0;
        }

        .metric-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .metric-trend.green {
            color: #14F195;
        }

        .mock-chart-container {
            height: 120px;
            width: 100%;
            margin-bottom: 2rem;
            border-radius: 12px;
            overflow: hidden;
        }

        .mock-chart-svg {
            width: 100%;
            height: 100%;
        }

        .chart-pulse-circle {
            animation: statusPulse 2s infinite;
            transform-origin: 300px 15px;
        }

        .mock-badges-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .mock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.85rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .mock-badge i {
            font-size: 0.7rem;
        }

        .showcase-caption h2 {
            font-family: var(--font-serif);
            font-size: 1.75rem;
            font-weight: 800;
            color: #FFF;
            margin-bottom: 0.75rem;
        }

        .showcase-caption p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .auth-panel-footer {
            margin-top: 3.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-align: center;
        }

        .auth-panel-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.25s;
        }

        .auth-panel-footer a:hover {
            color: #FFF;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
        }

        /* Custom alert styles */
        .success-overlay {
            animation: fadeIn 0.25s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .success-card {
            background: rgba(13, 18, 32, 0.85) !important;
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
            border-radius: 24px !important;
            padding: 3rem 2.5rem !important;
            text-align: center !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            transform: scale(0.9);
            animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes scaleIn {
            from { transform: scale(0.9); }
            to { transform: scale(1); }
        }

        .success-icon-ring {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.25rem;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.1);
        }

        /* Responsiveness styling */
        @media (max-width: 991px) {
            .showcase-panel {
                display: none !important;
            }
            .auth-panel {
                max-width: 100% !important;
                width: 100%;
                padding: 3rem 2rem;
            }
        }
<?php if (isset($_GET['screenshot'])): ?>
.noise-overlay {
    display: none !important;
}
<?php endif; ?>
    </style>
</head>
<body>

<div class="login-split-wrapper">
    <!-- Left: Auth Panel -->
    <div class="auth-panel">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="noise-overlay"></div>
        
        <div class="auth-content">
            <!-- Branding Logo -->
            <div class="auth-logo-header">
                <div class="auth-logo-icon">
                    <svg class="logo-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="12 2 15 9 22 12 15 15 12 22 9 15 2 12 9 9" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div class="auth-logo-text-group">
                    <span class="auth-logo-title">Voyage<span style="color:var(--primary);">Quest</span></span>
                    <span class="auth-logo-subtitle">ENTERPRISE GATEWAY</span>
                </div>
            </div>
            
            <!-- Title Section -->
            <div class="auth-title-section">
                <h1>Welcome back</h1>
                <p>Enter your administrative credentials to access the console</p>
            </div>
            
            <!-- Form -->
            <form method="post" id="loginForm" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="sbmt" value="1">
                
                <!-- Username Field -->
                <div class="input-field-wrapper">
                    <input type="text" id="t1" name="t1" required placeholder=" " autocomplete="username" class="form-input">
                    <label for="t1" class="form-label">Username</label>
                    <i class="fa-solid fa-user input-icon"></i>
                </div>
                
                <!-- Password Field -->
                <div class="input-field-wrapper">
                    <input type="password" id="t2" name="t2" required placeholder=" " autocomplete="current-password" class="form-input">
                    <label for="t2" class="form-label">Password</label>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="password-toggle" id="togglePasswordBtn" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                
                <!-- Remember Me / Forgot Password -->
                <div class="form-actions-row">
                    <label class="remember-me-label">
                        <input type="checkbox" name="remember" class="remember-checkbox">
                        <span>Remember me</span>
                    </label>
                    <a href="#" id="forgotPasswordLink" class="forgot-pass-link">Forgot password?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" name="sbmt" id="submitBtn" class="btn-submit-premium">
                    <span class="btn-text">Access Portal</span>
                    <i class="fa-solid fa-arrow-right-long btn-icon"></i>
                </button>
                
                <!-- Connection Security Status -->
                <div class="security-status-badge">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>End-to-end encrypted connection active</span>
                </div>
            </form>
            
            <!-- Footer Links -->
            <div class="auth-panel-footer">
                <p>Return to <a href="../index.php">VoyageQuest Home</a></p>
            </div>
        </div>
    </div>
    
    <!-- Right: Showcase Panel -->
    <div class="showcase-panel">
        <div class="aurora-blob aurora-3"></div>
        <div class="aurora-blob aurora-4"></div>
        <div class="noise-overlay"></div>
        
        <!-- Grid backdrop overlay -->
        <div class="grid-backdrop"></div>
        
        <div class="showcase-content">
            <!-- Mock Dashboard Card -->
            <div class="mock-dashboard">
                <div class="mock-header">
                    <div class="status-indicator-group">
                        <span class="status-pulse-dot"></span>
                        <span class="status-text">Operations Node: Nominal</span>
                    </div>
                    <span class="mock-version">v4.2.0</span>
                </div>
                
                <div class="mock-metrics-row">
                    <div class="mock-metric-card">
                        <span class="metric-label">Active Bookings</span>
                        <h4 class="metric-value">14,284</h4>
                        <span class="metric-trend green"><i class="fa-solid fa-arrow-trend-up"></i> +12.4%</span>
                    </div>
                    <div class="mock-metric-card">
                        <span class="metric-label">Node Load</span>
                        <h4 class="metric-value" style="color:var(--primary);">98.4%</h4>
                        <span class="metric-trend green"><i class="fa-solid fa-arrow-trend-up"></i> Optimal</span>
                    </div>
                </div>
                
                <!-- SVG line chart -->
                <div class="mock-chart-container">
                    <svg viewBox="0 0 300 100" class="mock-chart-svg">
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#00D4FF" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#00D4FF" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,80 Q30,50 60,65 T120,40 T180,75 T240,30 T300,15 L300,100 L0,100 Z" fill="url(#chartGrad)"/>
                        <path d="M0,80 Q30,50 60,65 T120,40 T180,75 T240,30 T300,15" fill="none" stroke="#00D4FF" stroke-width="2"/>
                        <circle cx="300" cy="15" r="4" fill="#00D4FF"/>
                        <circle cx="300" cy="15" r="8" fill="none" stroke="#00D4FF" stroke-width="1" class="chart-pulse-circle"/>
                    </svg>
                </div>
                
                <!-- Badges row -->
                <div class="mock-badges-row">
                    <span class="mock-badge"><i class="fa-solid fa-lock"></i> SSL: AES-256</span>
                    <span class="mock-badge"><i class="fa-solid fa-database"></i> DB: Parameterized</span>
                    <span class="mock-badge"><i class="fa-solid fa-server"></i> API: Active</span>
                </div>
            </div>
            
            <!-- Text caption -->
            <div class="showcase-caption">
                <h2>VoyageQuest Control Center</h2>
                <p>The centralized orchestration engine to configure luxury packages, monitor booking streams, and administer portal configuration.</p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
window.alert = function(message) {
    const overlay = document.createElement("div");
    overlay.className = "success-overlay";
    overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(7, 11, 20, 0.85); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 99999; animation: fadeIn 0.2s ease-out forwards;";
    
    overlay.innerHTML = `
        <div class="success-card" style="border-color: #EF444440; max-width: 380px;">
            <div class="success-icon-ring animate-ring" style="color: #EF4444; border-color: #EF444460; background: rgba(239, 68, 68, 0.1);">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 style="font-weight: 800; color: #FFF; margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">Login Alert</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">${message}</p>
            <button type="button" class="btn btn-danger px-5 py-2" id="customAlertBtn" style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 8px;">Dismiss</button>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    document.getElementById("customAlertBtn").addEventListener("click", () => {
        overlay.style.animation = "fadeOut 0.15s ease-in forwards";
        setTimeout(() => overlay.remove(), 150);
    });
};

// Toggle password visibility
const toggleBtn = document.getElementById("togglePasswordBtn");
const passwordInput = document.getElementById("t2");
if (toggleBtn && passwordInput) {
    toggleBtn.addEventListener("click", () => {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        const icon = toggleBtn.querySelector("i");
        if (icon) {
            icon.className = type === "password" ? "fa-solid fa-eye" : "fa-solid fa-eye-slash";
        }
    });
}

// Forgot Password Security Modal
const forgotLink = document.getElementById("forgotPasswordLink");
if (forgotLink) {
    forgotLink.addEventListener("click", (e) => {
        e.preventDefault();
        showSecurityNotice("Security Protocol", "For enterprise compliance, administrative credential resets must be authorized by your system administrator. Please contact your IT operations department to request a reset token.");
    });
}

function showSecurityNotice(title, message) {
    const overlay = document.createElement("div");
    overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(7, 11, 20, 0.85); backdrop-filter: blur(12px); display: flex; justify-content: center; align-items: center; z-index: 99999; opacity: 0; transition: opacity 0.3s ease;";
    
    overlay.innerHTML = `
        <div style="background: rgba(13, 18, 32, 0.65); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 28px; padding: 3rem 2.5rem; text-align: center; max-width: 420px; width: 90%; box-shadow: 0 30px 60px rgba(0,0,0,0.8); transform: scale(0.85); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width: 76px; height: 76px; border-radius: 50%; background: rgba(0, 212, 255, 0.1); border: 2px solid var(--primary); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 2.25rem; margin: 0 auto 1.5rem auto; box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 style="font-weight: 800; color: #FFF; margin-bottom: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem;">${title}</h3>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.95rem; line-height: 1.6; font-family: 'Plus Jakarta Sans', sans-serif;">${message}</p>
            <button type="button" class="btn-submit-premium" id="dismissNoticeBtn" style="padding: 0.75rem 2rem; border-radius: 20px; font-size: 0.9rem;">Acknowledge Protocol</button>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.style.opacity = "1";
        overlay.querySelector('div').style.transform = "scale(1)";
    }, 50);
    
    document.getElementById("dismissNoticeBtn").addEventListener("click", () => {
        overlay.style.opacity = "0";
        overlay.querySelector('div').style.transform = "scale(0.85)";
        setTimeout(() => overlay.remove(), 300);
    });
}

// Button loading state on submit
const form = document.getElementById("loginForm");
const submitBtn = document.getElementById("submitBtn");
if (form && submitBtn) {
    form.addEventListener("submit", () => {
        const btnText = submitBtn.querySelector(".btn-text");
        const btnIcon = submitBtn.querySelector(".btn-icon");
        if (btnText && btnIcon) {
            btnText.innerHTML = "Authenticating Node...";
            btnIcon.className = "fa-solid fa-spinner fa-spin";
        }
        // Disable button in the next tick to allow form submission data to package normally
        setTimeout(() => {
            submitBtn.disabled = true;
        }, 0);
    });
}

// Staggered entrance animations via GSAP
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('screenshot') === '1') {
        return; // Bypass GSAP animations for screenshots to avoid opacity=0
    }
    if (typeof gsap !== 'undefined') {
        gsap.from(".auth-logo-header", { duration: 1, y: -20, opacity: 0, ease: "power4.out" });
        gsap.from(".auth-title-section", { duration: 1, y: 20, opacity: 0, delay: 0.2, ease: "power4.out" });
        gsap.from(".input-field-wrapper", { duration: 1, y: 30, opacity: 0, stagger: 0.1, delay: 0.3, ease: "power4.out" });
        gsap.from(".form-actions-row, .btn-submit-premium, .security-status-badge", { duration: 1, y: 30, opacity: 0, stagger: 0.1, delay: 0.5, ease: "power4.out" });
        gsap.from(".auth-panel-footer", { duration: 1, opacity: 0, delay: 0.8, ease: "power4.out" });
        
        // Showcase panel items
        gsap.from(".mock-dashboard", { duration: 1.2, x: 50, opacity: 0, delay: 0.4, ease: "power4.out" });
        gsap.from(".showcase-caption", { duration: 1.2, y: 30, opacity: 0, delay: 0.6, ease: "power4.out" });
    }
});
</script>

</body>
</html>