<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Help & Support - PathNex</title>
<style>
* {margin: 0; padding: 0; box-sizing: border-box;}
body {
    font-family: 'Poppins', sans-serif;
    background: radial-gradient(ellipse at top left, #06070a, #0d0f14 50%, #10121a);
    color: #e5e8ec;
    display: flex;
    min-height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    background: linear-gradient(180deg, #131728, #0a0d14 90%);
    display: flex;
    flex-direction: column;
    padding: 22px;
    border-right: 1px solid rgba(255,255,255,0.05);
}
.sidebar .logo {
    width: 150px;
    margin: 0 auto 40px;
    filter: drop-shadow(0 0 10px #44d1ff55);
}
.sidebar a {
    text-decoration: none;
    color: #aab2cc;
    padding: 12px 16px;
    margin: 5px 0;
    border-radius: 8px;
    display: block;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.sidebar a:hover {
    background: linear-gradient(90deg, #2d3155, #1a1f33);
    color: #76e4ff;
    box-shadow: inset 3px 0 0 #00c3ff;
    transform: translateX(4px);
}
.sidebar a.active {
    background: linear-gradient(90deg, #21263a, #121622);
    color: #00c3ff;
    box-shadow: inset 3px 0 0 #00c3ff;
}
.logout-btn {
    color: #ff6b6b !important;
    margin-top: auto;
}
.logout-btn:hover {
    background: linear-gradient(90deg, #2d1a1a, #1a0f0f);
    box-shadow: inset 3px 0 0 #ff6b6b;
    color: #ffb2b2 !important;
    transform: translateX(4px);
}

/* ===== MAIN CONTENT ===== */
.main {
    flex: 1;
    padding: 50px 70px;
    overflow-y: auto;
}
h1 {
    font-size: 1.8rem;
    background: linear-gradient(90deg, #00c3ff, #4b8cff, #a24cff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 25px;
    font-weight: 600;
}
h2 {
    color: #9fb4ff;
    font-size: 1.1rem;
    margin-top: 35px;
    margin-bottom: 8px;
    border-left: 4px solid #00c3ff;
    padding-left: 10px;
    font-weight: 500;
}
p {
    color: #c9d2e3;
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 0.95rem;
}
ul {
    margin-left: 25px;
    margin-bottom: 15px;
}
ul li {
    margin-bottom: 8px;
}

/* ===== CARD STYLE ===== */
.help-card {
    background: rgba(18,24,35,0.95);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.35);
    padding: 25px 30px;
    margin-top: 15px;
    animation: fadeIn 0.6s ease forwards;
}
.contact-box {
    background: linear-gradient(135deg, #00c3ff25, #4b8cff25);
    border-left: 4px solid #00c3ff;
    padding: 18px 20px;
    border-radius: 10px;
    margin-top: 20px;
}
.contact-box strong {
    color: #00c3ff;
}
footer {
    text-align: center;
    padding: 15px;
    color: #8892a0;
    font-size: 13px;
    border-top: 1px solid rgba(255,255,255,0.05);
    margin-top: 40px;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="logo">
    <a href="studentHome.php">Home</a>
    <a href="select_course.php">Select Courses</a>
    <a href="recommendation.php">Recommended Courses</a>
    <a href="courses.php">All Courses</a>
    <a href="topColleges.php">Top Colleges</a>
    <a href="student_profile.php">Profile</a>
    <a href="help.php" class="active">Help & Support</a>
    <a href="about_us.php">About Us</a>
    <a href="documentation.php">Documentation</a>
    <a href="faq.php">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>

<!-- MAIN -->
<div class="main">
    <h1>Help & Support</h1>

    <div class="help-card">
        <h2>About PathNex</h2>
        <p><strong>PathNex – Smart Academic Guidance System</strong> helps students discover the best courses and colleges based on their background, interests, and academic profile. It uses data-driven recommendations to make educational decisions simpler and smarter.</p>
    </div>

    <div class="help-card">
        <h2>Using PathNex</h2>
        <ul>
            <li><strong>1. Profile Setup:</strong> Complete your <em>Student Profile</em> with details like stream, subjects, and interests.</li>
            <li><strong>2. Course Recommendations:</strong> Visit the <em>Recommended Courses</em> page to see courses that best match your profile.</li>
            <li><strong>3. Browse Courses:</strong> Explore all available programs on the <em>All Courses</em> page, with filters by stream.</li>
            <li><strong>4. Top Colleges:</strong> Check the <em>Top Colleges</em> page to view the best institutions in Gujarat for your chosen stream or course.</li>
            <li><strong>5. Stay Updated:</strong> You can edit or update your profile anytime — recommendations update automatically!</li>
        </ul>
    </div>

    <div class="help-card">
        <h2>Common Issues</h2>
        <ul>
            <li>If your <strong>recommendations are blank</strong>, ensure your profile is complete.</li>
            <li>If a page doesn’t load properly, try refreshing or logging out and back in.</li>
            <li>Make sure your <strong>session is active</strong> — PathNex requires you to be logged in.</li>
            <li>Data not saving? Verify that all required fields (like stream and subjects) are filled correctly.</li>
        </ul>
    </div>

    <div class="help-card">
        <h2>Contact Support</h2>
        <p>If you need further help or want to report a bug, please contact the PathNex support team below.</p>
        <div class="contact-box">
            <p><strong>Email:</strong> 25mca062@nirmauni.ac.in | 25mca065@nirmauni.ac.in</p>
            <p><strong>Developers:</strong> Yatin Thakkar | Vrushil Makwana</p>
            <p><strong>Version:</strong> PathNex v1.0 (Local Build)</p>
        </div>
    </div>

    <footer>© 2025 Yatin Thakkar | Vrushil Makwana</footer>
</div>

<script>
function confirmLogout(event) {
    event.preventDefault();
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>
