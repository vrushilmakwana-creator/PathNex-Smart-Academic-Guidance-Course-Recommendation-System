<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Home - PathNex UI</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

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
        transition: 0.3s;
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
        transform: none;
    }
    .logout-btn {
        color: #ff6b6b !important;
        margin-top: auto;
    }
    .logout-btn:hover {
        background: linear-gradient(90deg, #2d1a1a, #1a0f0f);
        box-shadow: inset 3px 0 0 #ff6b6b;
        color: #ffb2b2 !important;
    }

    /* ===== MAIN AREA ===== */
    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background: radial-gradient(circle at top right, #101428 0%, #0a0c12 70%);
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 45px;
        background: linear-gradient(90deg, rgba(16,20,32,0.9), rgba(11,14,22,0.9));
        border-bottom: 1px solid rgba(255,255,255,0.05);
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .search {
        display: flex;
        flex: 1;
        margin: 0 20px;
        border-radius: 40px;
        overflow: hidden;
        background: linear-gradient(90deg, #1a1d2b, #11131e);
        border: 1px solid rgba(255,255,255,0.05);
    }
    .search input {
        flex: 1;
        border: none;
        background: transparent;
        color: #dfe2e8;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
    }
    .search input::placeholder {
        color: #888;
    }
    .search button {
        background: linear-gradient(135deg, #00c3ff, #4b8cff);
        border: none;
        color: #fff;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .search button:hover {
        filter: brightness(1.2);
    }

    /* ===== CONTENT ===== */
    .content {
        padding: 60px 70px;
        animation: fadeIn 0.6s ease forwards;
    }
    .content h1 {
        font-size: 2.3rem;
        background: linear-gradient(90deg, #00c3ff, #4b8cff, #a24cff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }
    .content p {
        color: #aab2cc;
        margin-bottom: 50px;
        font-size: 1rem;
        max-width: 600px;
        line-height: 1.7;
    }

    /* ===== DASHBOARD CARDS ===== */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 30px;
    }
    .card {
        background: linear-gradient(135deg, rgba(18,24,35,0.95), rgba(12,16,25,0.95));
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        padding: 30px 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .card::before {
        content: "";
        position: absolute;
        top: -30%;
        left: -30%;
        width: 160%;
        height: 160%;
        background: conic-gradient(from 180deg, #00c3ff22, #4b8cff11, #a24cff22, transparent);
        filter: blur(40px);
        opacity: 0;
        transition: 0.4s;
    }
    .card:hover::before {
        opacity: 0.6;
    }
    .card:hover {
        transform: translateY(-6px);
        border-color: rgba(0,195,255,0.25);
        box-shadow: 0 10px 35px rgba(0,195,255,0.15);
    }
    .card h3 {
        color: #7dc9ff;
        font-size: 1.25rem;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }
    .card p {
        font-size: 0.95rem;
        color: #b4bbd0;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .card button {
        background: linear-gradient(135deg, #00c3ff, #4b8cff);
        color: white;
        padding: 9px 18px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 0 15px rgba(0,195,255,0.2);
    }
    .card button:hover {
        filter: brightness(1.2);
        box-shadow: 0 0 25px rgba(0,195,255,0.35);
    }

    /* ===== FOOTER ===== */
    footer {
        text-align: center;
        padding: 18px;
        color: #8892a0;
        font-size: 13px;
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 30px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="logo">
    <a href="studentHome.php" class="active">Home</a>
    <a href="select_course.php">Select Courses</a>
    <a href="recommendation.php">Recommended Courses</a>
    <a href="courses.php">All Courses</a>
    <a href="topColleges.php">Top Colleges</a>
    <a href="student_profile.php">Profile</a>
    <a href="help.php">Help & Support</a>
    <a href="about_us.php">About Us</a>
    <a href="documentation.php">Documentation</a>
    <a href="faq.php">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>

<!-- MAIN CONTENT -->
<div class="main">
    <header>
        <form action="Search_results.php" method="POST" class="search">
            <input type="text" name="query" placeholder="Search courses or colleges..." required>
            <button type="submit">Search</button>
        </form>
    </header>

    <div class="content">
        <h1>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Student'; ?></h1>
        <p><strong>PathNex</strong> — Empowering students with personalized course recommendations for smarter academic choices.</p>

        <div class="grid">
            <div class="card" onclick="window.location.href='courses.php'">
                <h3>Courses</h3>
                <p>Explore programs crafted for your academic growth.</p>
                <button>Explore</button>
            </div>
            <div class="card" onclick="window.location.href='recommendation.php'">
                <h3>Recommendations</h3>
                <p>Suggestions designed to suit your goals.</p>
                <button>See</button>
            </div>
            <div class="card" onclick="window.location.href='topColleges.php'">
                <h3>Top Colleges</h3>
                <p>Find institutions that fit your career ambitions.</p>
                <button>View</button>
            </div>
            <div class="card" onclick="window.location.href='student_profile.php'">
                <h3>Profile</h3>
                <p>Update your learning preferences and interests.</p>
                <button>Open</button>
            </div>
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
