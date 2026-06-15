<?php
// C:\travel\aboutus.php
// About Us Page — premium design with team, stats, safety info

include('function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About VoyageQuest | Our Vision, Mission &amp; Team</title>
    <meta name="description" content="Learn about VoyageQuest — our vision to make travel extraordinary, our mission to serve discerning travelers, our team of experts, and our commitment to safety and sustainability.">
    <meta property="og:title" content="About VoyageQuest">
    <meta property="og:description" content="Premium travel agency crafting extraordinary journeys since 2015.">
    <meta property="og:image" content="images/classic.jpg">
    <meta property="og:site_name" content="VoyageQuest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/luxury_travel.css?v=<?php echo filemtime('css/luxury_travel.css'); ?>">
</head>
<body>
<?php include('top.php'); ?>

<main class="ta-container" role="main">
    <!-- Page Header -->
    <div style="max-width: 700px; margin-bottom: 4rem;" data-aos="fade-up">
        <div style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(244,185,66,0.12);border:1px solid rgba(244,185,66,0.3);border-radius:30px;padding:0.35rem 1rem;margin-bottom:1.5rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--gold);">
            Our Story
        </div>
        <h1 class="ta-section-title" style="margin-bottom: 1.5rem;">About <span>VoyageQuest</span></h1>
        <p style="color:var(--text-gray);font-size:1.15rem;line-height:1.85;">
            We are a premium travel consultancy founded with a singular belief: that travel, done right, transforms lives. Since 2015, we have been crafting extraordinary journeys for thousands of discerning travelers.
        </p>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1.5rem;margin-bottom:5rem;padding:2.5rem;background:rgba(244,185,66,0.05);border:1px solid rgba(244,185,66,0.15);border-radius:24px;" data-aos="fade-up" data-aos-delay="100">
        <?php
        $about_stats = [
            ['25,000+', 'Happy Travelers', '🌍'],
            ['150+', 'Destinations', '📍'],
            ['4.9/5', 'Average Rating', '⭐'],
            ['10+', 'Years Experience', '🏆'],
            ['100%', 'Verified Partners', '✅'],
        ];
        foreach ($about_stats as $stat):
        ?>
        <div style="text-align:center;">
            <div style="font-size:1.5rem;margin-bottom:0.5rem;"><?php echo $stat[2]; ?></div>
            <div style="font-family:var(--font-serif);font-size:2rem;font-weight:900;color:var(--gold);line-height:1;margin-bottom:0.4rem;"><?php echo $stat[0]; ?></div>
            <div style="font-size:0.8rem;color:var(--text-gray);text-transform:uppercase;letter-spacing:1px;font-weight:600;"><?php echo $stat[1]; ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Vision & Mission -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:2rem;margin-bottom:5rem;">
        <div class="ta-detail-card" data-aos="fade-right">
            <div style="font-size:2.5rem;margin-bottom:1.25rem;">🌍</div>
            <h2 style="font-family:var(--font-serif);font-size:1.8rem;color:var(--gold);margin-bottom:1.25rem;">Our Vision</h2>
            <p style="color:var(--text-gray);line-height:1.85;margin-bottom:1rem;">
                To lead the global tourism sector by delivering ethical, value-driven, and highly-satisfying experiences. We position VoyageQuest as a premier operator that makes booking luxury holidays transparent, affordable, and personalized.
            </p>
            <p style="color:var(--text-gray);line-height:1.85;">
                By investing in contemporary travel technology and quality assurance, we cater to all individual, group, or corporate requirements — from family getaways to spiritual pilgrimages.
            </p>
        </div>
        
        <div class="ta-detail-card" data-aos="fade-left">
            <div style="font-size:2.5rem;margin-bottom:1.25rem;">🧭</div>
            <h2 style="font-family:var(--font-serif);font-size:1.8rem;color:var(--primary);margin-bottom:1.25rem;">Our Mission</h2>
            <p style="color:var(--text-gray);line-height:1.85;margin-bottom:1rem;">
                To provide top-tier travel services combining quality, flexibility, and value. We strive to exceed client expectations, uphold professional integrity, and expand sustainable tourism benefits across every destination we serve.
            </p>
            <p style="color:var(--text-gray);line-height:1.85;">
                From flight booking to passport services and inbound/outbound tours, we operate with a touch of personal care and absolute transparency.
            </p>
        </div>
    </div>

    <!-- Our Values -->
    <div style="margin-bottom: 5rem;" data-aos="fade-up">
        <h2 class="ta-section-title" style="margin-bottom: 2.5rem;">Our <span>Values</span></h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;">
            <?php
            $values = [
                ['💎', 'Excellence', '#F4B942', 'We maintain the highest standards across every aspect of the travel experience.'],
                ['🤝', 'Integrity', '#22c55e', 'Transparent pricing, honest communication, and no hidden surprises — ever.'],
                ['♻️', 'Sustainability', '#34d399', 'We champion eco-tourism and responsible travel for a better planet.'],
                ['❤️', 'Care', '#f43f5e', 'Every traveler is a guest in our care — treated with warmth and respect.'],
            ];
            foreach ($values as $i => $val):
            ?>
            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-light);border-radius:20px;padding:1.75rem;transition:all 0.3s ease;"
                 data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div style="font-size:1.8rem;margin-bottom:1rem;width:52px;height:52px;background:<?php echo $val[2]; ?>15;border-radius:14px;display:flex;align-items:center;justify-content:center;border:1px solid <?php echo $val[2]; ?>30;">
                    <?php echo $val[0]; ?>
                </div>
                <h3 style="font-size:1rem;font-weight:800;margin-bottom:0.6rem;color:var(--text-white);"><?php echo $val[1]; ?></h3>
                <p style="color:var(--text-gray);font-size:0.9rem;line-height:1.7;margin:0;"><?php echo $val[3]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Hero Image -->
    <div style="border-radius:28px;overflow:hidden;margin-bottom:5rem;position:relative;min-height:400px;" data-aos="zoom-in">
        <img src="images/himalaya1.jpg" alt="VoyageQuest — Extraordinary travel destinations" 
             style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;" loading="lazy">
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(5,10,20,0.9) 0%,rgba(5,10,20,0.3) 60%,transparent 100%);"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;padding:3rem;text-align:center;">
            <p style="font-family:var(--font-serif);font-size:1.8rem;font-weight:700;color:#fff;margin:0;text-shadow:0 2px 20px rgba(0,0,0,0.5);">
                "To travel is to live — to explore is to discover the extraordinary."
            </p>
        </div>
    </div>

    <!-- Safety Information -->
    <div style="margin-bottom: 4rem;" data-aos="fade-up">
        <h2 class="ta-section-title" style="margin-bottom: 2.5rem;">Safety & <span>Security</span></h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem;">
            <?php
            $safety = [
                ['✈️', 'Transportation Safety', 'Check carrier safety ratings, watch pre-flight briefings, and secure personal items using money belts or zipped pockets during all transit journeys.'],
                ['🏨', 'Hotel Security', 'Verify door locks, store valuables in the hotel locker, and locate emergency exits immediately upon check-in at every accommodation.'],
                ['🏥', 'Health & Medical', 'Carry comprehensive travel insurance, a first-aid kit, and copies of all prescriptions. We provide 24/7 emergency medical assistance contacts.'],
                ['📱', 'Digital Security', 'Use our secure booking portal. Never share your personal booking reference over unsecured channels. Our data is encrypted end-to-end.'],
            ];
            foreach ($safety as $i => $s):
            ?>
            <div class="ta-detail-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div style="font-size:2rem;margin-bottom:1rem;"><?php echo $s[0]; ?></div>
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--text-white);margin-bottom:0.75rem;"><?php echo $s[1]; ?></h3>
                <p style="color:var(--text-gray);font-size:0.9rem;line-height:1.75;margin:0;"><?php echo $s[2]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CTA -->
    <div style="text-align:center;padding:4rem 2rem;background:linear-gradient(135deg,rgba(244,185,66,0.08),rgba(10,31,68,0.4));border:1px solid rgba(244,185,66,0.2);border-radius:28px;" data-aos="zoom-in">
        <h2 style="font-family:var(--font-serif);font-size:2.2rem;font-weight:800;margin-bottom:1rem;">
            Ready for Your Next <span style="color:var(--gold);">Adventure?</span>
        </h2>
        <p style="color:var(--text-gray);max-width:500px;margin:0 auto 2rem;font-size:1rem;line-height:1.7;">
            Let our travel experts craft the perfect itinerary tailored to your dreams, timeline, and budget.
        </p>
        <div style="display:flex;gap:1.25rem;justify-content:center;flex-wrap:wrap;">
            <a href="category.php" class="btn-ta btn-ta-primary" style="border-radius:50px;padding:0.9rem 2.5rem;font-size:1rem;">
                🗺 Browse Destinations
            </a>
            <a href="index.php#plan-trip" class="btn-ta btn-ta-secondary" style="border-radius:50px;padding:0.9rem 2.5rem;font-size:1rem;">
                ✉ Contact Us
            </a>
        </div>
    </div>
</main>

<?php
mysqli_close($cn);
include('bottom.php');
?>
</body>
</html>