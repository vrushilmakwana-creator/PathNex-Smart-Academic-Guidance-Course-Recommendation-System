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
<title>About Us - PathNex</title>
<style>
* {margin:0; padding:0; box-sizing:border-box;}
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
.about-card {
    background: rgba(18,24,35,0.95);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.35);
    padding: 25px 30px;
    margin-top: 15px;
    animation: fadeIn 0.6s ease forwards;
}
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    margin-top: 25px;
}
.team-card {
    background: rgba(25,30,45,0.9);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: 0.3s ease;
}
.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 18px rgba(0,195,255,0.25);
}
.team-card img {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 12px;
    border: 2px solid #00c3ff;
}
.team-card h3 {
    color: #76e4ff;
    font-size: 1rem;
    margin-bottom: 6px;
}
.team-card p {
    color: #aab2cc;
    font-size: 0.85rem;
    line-height: 1.4;
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
    <a href="help.php">Help & Support</a>
    <a href="about_us.php" class="active">About Us</a>
    <a href="documentation.php">Documentation</a>
    <a href="faq.php">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>
    

<!-- MAIN -->
<div class="main">

    <h1>About PathNex</h1>

    <div class="about-card">
        <h2>Our Vision</h2>
        <p><strong>PathNex</strong> was built to empower students to make informed academic decisions using data, insight, and personalization. Our mission is to bridge the gap between students’ interests and the courses that best align with their future aspirations.</p>
    </div>

    <div class="about-card">
        <h2>Our Story</h2>
        <p>PathNex – Smart Academic Guidance System began as a university project with a goal: to make course selection less confusing and more intelligent. Many students struggle to find suitable courses or colleges after graduation. PathNex uses profile-based recommendations and real academic data to make this process seamless.</p>
        <p>Hosted locally with <strong>XAMPP</strong> and powered by <strong>PHP</strong>, <strong>MySQL</strong>, and modern web design, PathNex ensures performance, simplicity, and an intuitive user experience.</p>
    </div>

    <div class="about-card">
        <h2>Core Features</h2>
        <ul>
            <li>Personalized course recommendations based on student profiles</li>
            <li>List of top Gujarat colleges with verified data</li>
            <li>Stream-based filtering for course discovery</li>
            <li>Interactive and minimal user interface (Poppins typography + gradient dark UI)</li>
            <li>Secure session-based login system for students</li>
        </ul>
    </div>
    
        <div class="about-card">
        <h2>Meet the Developers</h2>
        <div class="team-grid">
            <div class="team-card">
                <img src="https://i.ibb.co/6Hk3yMj/avatar1.png" alt="Vrushil">
                <h3>Vrushil Makwana</h3>
                <p>25MCA065</p>
                <p>Back-End & Database Developer</p>
                <p>Specializes in PHP, MySQL optimization, and backend architecture for PathNex.</p>
            </div>
            <div class="team-card">
                <img src="https://i.ibb.co/6Hk3yMj/avatar1.png" alt="Yatin">
                <h3>Yatin Thakkar</h3>
                <p>25MCA062</p>
                <p>Front-End Developer & UI Designer</p>
                <p>Focused on crafting PathNex’s intuitive and modern user experience.</p>
            </div>
        </div>
    </div>


    <div class="about-card">
        <h2>Technology Stack</h2>
        <ul>
            <li><strong>Frontend:</strong> HTML5, CSS3 (PathNex Custom UI Framework)</li>
            <li><strong>Backend:</strong> PHP 8+ with MySQL (XAMPP Localhost)</li>
            <li><strong>Database:</strong> course_recommendation_system</li>
            <li><strong>Server:</strong> Apache (XAMPP)</li>
        </ul>
    </div>

    <div class="about-card">
        <h2>Future Plans</h2>
        <p>We aim to enhance PathNex with AI-driven course matching, national-level college data integration, and dynamic dashboards for real-time analytics. Our vision is to evolve PathNex into a national-level academic recommendation platform.</p>
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
