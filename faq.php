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
<title>FAQs - PathNex</title>
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
p.intro {
    color: #c9d2e3;
    margin-bottom: 25px;
    font-size: 0.95rem;
}

/* ===== FAQ CONTAINER ===== */
.faq-container {
    background: rgba(18,24,35,0.95);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 6px 18px rgba(0,0,0,0.35);
    padding: 25px 30px;
    animation: fadeIn 0.6s ease forwards;
}

/* ===== FAQ ITEM ===== */
.faq-item {
    margin-bottom: 15px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    padding-bottom: 10px;
}
.faq-question {
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #76e4ff;
    font-weight: 500;
    font-size: 1rem;
    transition: 0.3s;
}
.faq-question:hover {
    color: #00c3ff;
}
.faq-answer {
    display: none;
    color: #c9d2e3;
    font-size: 0.9rem;
    margin-top: 8px;
    line-height: 1.6;
    padding-left: 5px;
}
.faq-item.active .faq-answer {
    display: block;
}
.faq-item.active .faq-question::after {
    content: "−";
}
.faq-question::after {
    content: "+";
    font-size: 1.2rem;
    font-weight: bold;
    color: #aab2cc;
    transition: 0.3s;
}
.faq-item.active .faq-question {
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
    <a href="help.php">Help & Support</a>
    <a href="about_us.php">About Us</a>
    <a href="documentation.php">Documentation</a>
    <a href="faq.php" class="active">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>

<!-- MAIN CONTENT -->
<div class="main">
    <h1>Frequently Asked Questions</h1>
    <p class="intro">Here are some of the most common questions students ask about PathNex. Click on a question to view the answer.</p>

    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-question">What is PathNex?</div>
            <div class="faq-answer">PathNex – Smart Academic Guidance System helps students discover the best academic courses and colleges based on their profile, interests, and educational background.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">How does PathNex recommend courses?</div>
            <div class="faq-answer">PathNex analyzes your profile — including your stream, subjects, and interests — and calculates a match score for each available course in the database. Courses with the highest scores appear under "Recommended Courses".</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Do I need to complete my profile to get recommendations?</div>
            <div class="faq-answer">Yes. Completing your student profile is essential for accurate recommendations. Without details like stream, interests, and subjects, the system can’t generate personalized results.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Where does PathNex get college data from?</div>
            <div class="faq-answer">PathNex’s <em>Top Colleges</em> page uses verified and normalized data for colleges in Gujarat, linked to each course via the database. Future versions will include national-level data integration.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Can I update my profile information?</div>
            <div class="faq-answer">Yes! You can go to your <em>Student Profile</em> page, edit any field, and save changes. Your recommendations will automatically refresh based on the new information.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Is my data secure?</div>
            <div class="faq-answer">All user data is stored locally in a secure MySQL database on XAMPP. The system uses session-based authentication to protect personal information during access.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">I’m getting “No recommendations found”. What should I do?</div>
            <div class="faq-answer">Ensure your profile is fully completed and your chosen stream or interests align with the courses available. If the problem persists, contact the PathNex support team.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Who developed PathNex?</div>
            <div class="faq-answer">PathNex was developed by <strong>Yatin Thakkar</strong> and <strong>Vrushil Makwana</strong> as part of a university project under the MCA program at Nirma University, Ahmedabad.</div>
        </div>
    </div>

    <footer>© 2025 Yatin Thakkar | Vrushil Makwana</footer>
</div>

<script>
const faqItems = document.querySelectorAll('.faq-item');
faqItems.forEach(item => {
    item.addEventListener('click', () => {
        item.classList.toggle('active');
    });
});

function confirmLogout(event) {
    event.preventDefault();
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>
