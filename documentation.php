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
<title>Documentation - PathNex</title>
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
p, li {
    color: #c9d2e3;
    line-height: 1.6;
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
.doc-card {
    background: rgba(18,24,35,0.95);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.35);
    padding: 25px 30px;
    margin-top: 15px;
    animation: fadeIn 0.6s ease forwards;
}
code {
    background: rgba(255,255,255,0.08);
    padding: 3px 6px;
    border-radius: 5px;
    color: #7de1ff;
    font-size: 0.85rem;
}
pre {
    background: rgba(0,0,0,0.4);
    padding: 12px;
    border-radius: 10px;
    color: #7de1ff;
    font-size: 0.85rem;
    overflow-x: auto;
    border: 1px solid rgba(255,255,255,0.08);
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
    <a href="about_us.php">About Us</a>
    <a href="documentation.php" class="active">Documentation</a>
    <a href="faq.php">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>

<!-- MAIN -->
<div class="main">
    <h1>PathNex Documentation</h1>

    <div class="doc-card">
        <h2>1. Overview</h2>
        <p><strong>PathNex – Smart Academic Guidance System</strong> is a personalized recommendation platform that assists students in choosing suitable academic courses and colleges. It uses PHP (backend), MySQL (database), and a modern, responsive dark UI framework.</p>
    </div>

    <div class="doc-card">
        <h2>2. Installation Requirements</h2>
        <ul>
            <li>XAMPP Control Panel (Apache + MySQL enabled)</li>
            <li>PHP 8.0 or higher</li>
            <li>MySQL Database named <code>course_recommendation_system</code></li>
            <li>Browser: Chrome, Edge, or Firefox (latest version)</li>
        </ul>
    </div>

    <div class="doc-card">
        <h2>3. Folder Setup</h2>
        <p>Place all project files inside the <code>htdocs</code> folder of XAMPP:</p>
<pre>
C:\xampp\htdocs\PathNex\
</pre>
        <p>Then start Apache and MySQL servers from the XAMPP Control Panel.</p>
    </div>

    <div class="doc-card">
        <h2>4. Database Schema</h2>
        <ul>
            <li><strong>login</strong> – manages authentication</li>
            <li><strong>student_profile</strong> – stores student details</li>
            <li><strong>course</strong> – lists all available courses</li>
            <li><strong>top_colleges</strong> – lists top Gujarat colleges per course</li>
        </ul>
        <p>Example table creation for <code>login</code>:</p>
<pre>
CREATE TABLE login (
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);
</pre>
    </div>

    <div class="doc-card">
        <h2>5. Configuration Files</h2>
        <p>Each PHP file connects to the database using this configuration:</p>
<pre>
$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("Database connection failed.");
}
</pre>
    </div>

    <div class="doc-card">
        <h2>6. Key Pages</h2>
        <ul>
            <li><strong>studentHome.php</strong> – Dashboard with navigation</li>
            <li><strong>recommendation.php</strong> – Personalized recommendations</li>
            <li><strong>courses.php</strong> – All courses by stream</li>
            <li><strong>topColleges.php</strong> – Gujarat college data</li>
            <li><strong>student_profile.php</strong> – Profile management</li>
        </ul>
    </div>

    <div class="doc-card">
        <h2>7. Extending PathNex</h2>
        <p>You can extend PathNex by:</p>
        <ul>
            <li>Adding new states or college data into <code>top_colleges</code></li>
            <li>Integrating an AI recommendation engine (future goal)</li>
            <li>Connecting real-time APIs for national-level college data</li>
            <li>Implementing admin dashboards for data management</li>
        </ul>
    </div>

    <div class="doc-card">
        <h2>8. Troubleshooting</h2>
        <ul>
            <li><strong>Issue:</strong> Blank pages → Check PHP error reporting settings</li>
            <li><strong>Issue:</strong> “Database connection failed” → Ensure MySQL is running</li>
            <li><strong>Issue:</strong> Session logout → Enable cookies in your browser</li>
        </ul>
    </div>

    <div class="doc-card">
        <h2>9. Developer Info</h2>
        <p><strong>Project:</strong> PathNex v1.0 (Local Build)</p>
        <p><strong>Developers:</strong> Yatin Thakkar (25mca062) | Vrushil Makwana (25mca065)</p>
        <p><strong>Institution:</strong> Nirma University, Ahmedabad (MCA)</p>
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
