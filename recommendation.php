<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Database connection
$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch student profile
$studentQuery = $conn->prepare("SELECT * FROM student_profile WHERE email = ?");
$studentQuery->bind_param("s", $email);
$studentQuery->execute();
$studentResult = $studentQuery->get_result();
$student = $studentResult->fetch_assoc();

if (!$student) {
    echo "<p style='text-align:center; color:red;'>Please complete your profile first.</p>";
    exit();
}

// Fetch all courses
$coursesResult = $conn->query("SELECT * FROM course");

$recommendations = [];

while ($course = $coursesResult->fetch_assoc()) {
    $score = 0;

    // Stream match
    if (strcasecmp(trim($student['stream']), trim($course['required_stream'])) == 0) {
        $score += 5;
    }

    // Interest match
    if (stripos($course['recommended_interest'], $student['interest']) !== false) {
        $score += 3;
    }

    // Thinking about course match
    if (stripos($course['course_name'], $student['thinking_about_course']) !== false) {
        $score += 2;
    }

    // Subject match
    $studentSubjects = array_map('trim', explode(',', strtolower($student['subjects'])));
    $courseSubjects = array_map('trim', explode(',', strtolower($course['required_subjects'])));
    $matches = array_intersect($studentSubjects, $courseSubjects);
    $score += count($matches) * 2;

    $course['score'] = $score;
    $recommendations[] = $course;
}

// ---------- 3 SETS OF RECOMMENDATIONS ----------
$topMatches = $recommendations;
usort($topMatches, function($a, $b) { return $b['score'] - $a['score']; });
$topMatches = array_slice($topMatches, 0, 5);

$interestMatches = array_filter($recommendations, function($course) use ($student) {
    return stripos($course['recommended_interest'], $student['interest']) !== false;
});
usort($interestMatches, function($a, $b) { return $b['score'] - $a['score']; });
$interestMatches = array_slice($interestMatches, 0, 5);

$exploreMore = $recommendations;
shuffle($exploreMore);
$exploreMore = array_slice($exploreMore, 0, 5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Recommended Courses - PathNex</title>
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

    /* ===== MAIN CONTENT ===== */
    .main {
        flex: 1;
        padding: 40px 60px;
        overflow-y: auto;
    }
    .main h1 {
        font-size: 1.8rem;
        background: linear-gradient(90deg, #00c3ff, #4b8cff, #a24cff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
        font-weight: 600;
    }
    .section-title {
        font-size: 1.1rem;
        color: #9fb4ff;
        margin-top: 35px;
        margin-bottom: 8px;
        border-left: 4px solid #00c3ff;
        padding-left: 10px;
    }

    /* ===== TABLE CONTAINER ===== */
    .table-container {
        background: rgba(18,24,35,0.95);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 6px 18px rgba(0,0,0,0.35);
        margin-top: 10px;
        overflow-x: auto;
        animation: fadeIn 0.6s ease forwards;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    th, td {
        padding: 10px 14px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    th {
        background: linear-gradient(90deg, #151a2e, #111528);
        color: #76e4ff;
        font-weight: 600;
        font-size: 0.85rem;
    }
    td {
        color: #d5d9e3;
        font-size: 0.88rem;
    }
    tr:hover td {
        background: rgba(255,255,255,0.03);
        transition: 0.2s;
    }

    .score-badge {
        background: linear-gradient(135deg, #00c3ff, #4b8cff);
        padding: 4px 12px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: 0 0 10px rgba(0,195,255,0.25);
    }

    .no-results {
        text-align: center;
        color: #9da6c7;
        margin: 20px 0;
        font-size: 14px;
    }

    footer {
        text-align: center;
        padding: 15px;
        color: #8892a0;
        font-size: 13px;
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 30px;
    }

    @keyframes fadeIn {
        from {opacity:0; transform: translateY(10px);}
        to {opacity:1; transform: translateY(0);}
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="logo">
    <a href="studentHome.php">Home</a>
    <a href="select_course.php">Select Courses</a>
    <a href="recommendation.php" class="active">Recommended Courses</a>
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
    <h1>Your Personalized Course Recommendations</h1>

    <!-- Top Matches -->
    <h2 class="section-title">Top Matches</h2>
    <?php if (count($topMatches) > 0): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Required Stream</th>
                    <th>Subjects</th>
                    <th>Interest Area</th>
                    <th>Match Score</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($topMatches as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['course_name']); ?></td>
                    <td><?= htmlspecialchars($course['required_stream']); ?></td>
                    <td><?= htmlspecialchars($course['required_subjects']); ?></td>
                    <td><?= htmlspecialchars($course['recommended_interest']); ?></td>
                    <td><span class="score-badge"><?= $course['score']; ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?><p class="no-results">No top matches found.</p><?php endif; ?>

    <!-- Interest-Based -->
    <h2 class="section-title">Based on Your Interests</h2>
    <?php if (count($interestMatches) > 0): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Required Stream</th>
                    <th>Subjects</th>
                    <th>Interest Area</th>
                    <th>Match Score</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($interestMatches as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['course_name']); ?></td>
                    <td><?= htmlspecialchars($course['required_stream']); ?></td>
                    <td><?= htmlspecialchars($course['required_subjects']); ?></td>
                    <td><?= htmlspecialchars($course['recommended_interest']); ?></td>
                    <td><span class="score-badge"><?= $course['score']; ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?><p class="no-results">No interest-based matches found.</p><?php endif; ?>

    <!-- Explore More -->
    <h2 class="section-title">Explore More</h2>
    <?php if (count($exploreMore) > 0): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Required Stream</th>
                    <th>Subjects</th>
                    <th>Interest Area</th>
                    <th>Match Score</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($exploreMore as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['course_name']); ?></td>
                    <td><?= htmlspecialchars($course['required_stream']); ?></td>
                    <td><?= htmlspecialchars($course['required_subjects']); ?></td>
                    <td><?= htmlspecialchars($course['recommended_interest']); ?></td>
                    <td><span class="score-badge"><?= $course['score']; ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?><p class="no-results">No additional courses found to explore.</p><?php endif; ?>

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

<?php $conn->close(); ?>
