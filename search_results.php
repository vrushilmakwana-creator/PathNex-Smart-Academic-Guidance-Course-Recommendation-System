<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['query']) || trim($_POST['query']) === '') {
    header("Location: studentHome.php");
    exit();
}

$query = trim($_POST['query']);
$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* --- 1️⃣ Handle Sidebar Shortcut Searches --- */
$keywords = strtolower($query);
$pageMap = [
    'home' => 'studentHome.php',
    'profile' => 'student_profile.php',
    'recommendation' => 'recommendation.php',
    'recommendations' => 'recommendation.php',
    'courses' => 'courses.php',
    'top colleges' => 'topColleges.php',
];

foreach ($pageMap as $keyword => $redirectPage) {
    if (strpos($keywords, $keyword) !== false) {
        header("Location: $redirectPage");
        exit();
    }
}

/* --- 2️⃣ Search Courses Table --- */
$courseQuery = $conn->prepare("
    SELECT course_id, course_name, required_stream, required_subjects, recommended_interest 
    FROM course 
    WHERE course_name LIKE ? 
       OR required_stream LIKE ? 
       OR required_subjects LIKE ? 
       OR recommended_interest LIKE ?
");
$like = "%{$query}%";
$courseQuery->bind_param("ssss", $like, $like, $like, $like);
$courseQuery->execute();
$courseResults = $courseQuery->get_result();

/* --- 3️⃣ Search Colleges Table --- */
$collegeQuery = $conn->prepare("
    SELECT c.course_name, t.college_name, t.address, t.website, t.city, t.ranking 
    FROM top_colleges t 
    JOIN course c ON c.course_id = t.course_id 
    WHERE t.college_name LIKE ? 
       OR t.address LIKE ? 
       OR t.city LIKE ? 
       OR c.course_name LIKE ?
");
$collegeQuery->bind_param("ssss", $like, $like, $like, $like);
$collegeQuery->execute();
$collegeResults = $collegeQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Results - PathNex</title>
<style>
    * {margin:0; padding:0; box-sizing:border-box;}
    body {
        font-family: 'Poppins', sans-serif;
        background: radial-gradient(ellipse at top left, #06070a, #0d0f14 50%, #10121a);
        color: #e5e8ec;
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */
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
    }
    .sidebar a.active {
        background: linear-gradient(90deg, #21263a, #121622);
        color: #00c3ff;
        box-shadow: inset 3px 0 0 #00c3ff;
    }

    /* Main content */
    .main {
        flex: 1;
        padding: 50px 70px;
        overflow-y: auto;
    }

    /* Back Arrow */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #76e4ff;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 20px;
        transition: 0.3s ease;
        font-size: 0.95rem;
    }
    .back-btn:hover {
        color: #00c3ff;
        transform: translateX(-4px);
    }
    .back-btn svg {
        width: 20px;
        height: 20px;
        fill: #76e4ff;
        transition: 0.3s;
    }
    .back-btn:hover svg {
        fill: #00c3ff;
    }

    h1 {
        font-size: 1.8rem;
        background: linear-gradient(90deg, #00c3ff, #4b8cff, #a24cff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
    }

    .section {
        margin-top: 30px;
    }

    .result-box {
        background: rgba(18,24,35,0.95);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        padding: 18px 22px;
        margin-bottom: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.3);
        transition: 0.3s;
    }
    .result-box:hover {
        transform: translateY(-3px);
        border-color: rgba(0,195,255,0.2);
    }
    .result-box h3 {
        color: #76e4ff;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }
    .result-box p {
        color: #b5bbcf;
        font-size: 0.9rem;
        margin: 3px 0;
    }
    .result-box a {
        color: #00c3ff;
        text-decoration: none;
    }
    .result-box a:hover {
        text-decoration: underline;
    }

    .no-results {
        color: #aab2cc;
        text-align: center;
        margin-top: 40px;
    }

    footer {
        text-align: center;
        padding: 15px;
        color: #8892a0;
        font-size: 13px;
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 40px;
    }
</style>
</head>
<body>

<aside class="sidebar">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="logo">
    <a href="studentHome.php" class="active">Home</a>
    <a href="select_course.php">Select Courses</a>
    <a href="recommendation.php">Recommended Courses</a>
    <a href="courses.php">All Courses</a>
    <a href="topColleges.php">Top Colleges</a>
    <a href="student_profile.php">Profile</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>
<div class="main">
    <!-- Back Button -->
    <a href="studentHome.php" class="back-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
        </svg>
        Back to Home
    </a>

    <h1>Search Results for “<?= htmlspecialchars($query) ?>”</h1>

    <?php if ($courseResults->num_rows > 0): ?>
        <div class="section">
            <h2 style="color:#9fb4ff; margin-bottom:10px;">Matching Courses</h2>
            <?php while ($row = $courseResults->fetch_assoc()): ?>
                <div class="result-box">
                    <h3><?= htmlspecialchars($row['course_name']); ?></h3>
                    <p><strong>Stream:</strong> <?= htmlspecialchars($row['required_stream']); ?></p>
                    <p><strong>Subjects:</strong> <?= htmlspecialchars($row['required_subjects']); ?></p>
                    <p><strong>Interest:</strong> <?= htmlspecialchars($row['recommended_interest']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <?php if ($collegeResults->num_rows > 0): ?>
        <div class="section">
            <h2 style="color:#9fb4ff; margin-bottom:10px;">Matching Colleges</h2>
            <?php while ($row = $collegeResults->fetch_assoc()): ?>
                <div class="result-box">
                    <h3><?= htmlspecialchars($row['college_name']); ?></h3>
                    <p><strong>Course:</strong> <?= htmlspecialchars($row['course_name']); ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($row['address']); ?></p>
                    <p><strong>City:</strong> <?= htmlspecialchars($row['city']); ?></p>
                    <p><a href="<?= htmlspecialchars($row['website']); ?>" target="_blank">Visit College Website</a></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <?php if ($courseResults->num_rows === 0 && $collegeResults->num_rows === 0): ?>
        <p class="no-results">No results found for “<?= htmlspecialchars($query) ?>”. Try different keywords.</p>
    <?php endif; ?>

    <footer>© 2025 Yatin Thakkar | Vrushil Makwana</footer>
</div>

</body>
</html>

<?php $conn->close(); ?>
