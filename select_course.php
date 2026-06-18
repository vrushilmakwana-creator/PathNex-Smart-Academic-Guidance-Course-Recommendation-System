<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* Fetch all courses for dropdown */
$courseList = $conn->query("SELECT DISTINCT course_name FROM course ORDER BY course_name ASC");

/* Initialize variables */
$selectedCourse = $_POST['course'] ?? '';
$interest = trim($_POST['interest'] ?? '');
$results = [];

/* When form submitted */
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($selectedCourse)) {
    $query = "
        SELECT c.course_id, c.course_name, c.required_stream, c.required_subjects, c.recommended_interest
        FROM course c
        WHERE c.course_name LIKE ?
    ";
    if (!empty($interest)) {
        $query .= " OR c.recommended_interest LIKE ?";
    }

    $stmt = $conn->prepare($query);
    if (!empty($interest)) {
        $likeCourse = "%$selectedCourse%";
        $likeInterest = "%$interest%";
        $stmt->bind_param("ss", $likeCourse, $likeInterest);
    } else {
        $likeCourse = "%$selectedCourse%";
        $stmt->bind_param("s", $likeCourse);
    }

    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select Course - PathNex</title>
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

    form {
        background: rgba(18,24,35,0.95);
        padding: 25px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 6px 18px rgba(0,0,0,0.35);
        max-width: 650px;
        margin-bottom: 40px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #aab2cc;
    }
    select, input[type="text"] {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #e5e8ec;
        font-size: 0.95rem;
        outline: none;
        margin-bottom: 18px;
        transition: none;
    }
    select option {
        background: #10131c;
        color: #e5e8ec;
    }
    select:focus, input:focus {
        border-color: #00c3ff;
        box-shadow: 0 0 6px rgba(0,195,255,0.2);
    }

    button {
        background: linear-gradient(135deg, #00c3ff, #4b8cff);
        border: none;
        color: white;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 15px rgba(0,195,255,0.3);
    }

    /* ===== TABLE ===== */
    .table-container {
        background: rgba(18,24,35,0.95);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 6px 18px rgba(0,0,0,0.35);
        overflow-x: auto;
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
        vertical-align: top;
    }
    th {
        background: linear-gradient(90deg, #151a2e, #111528);
        color: #76e4ff;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    td {
        color: #d5d9e3;
        font-size: 0.9rem;
    }
    tr:hover td {
        background: rgba(255,255,255,0.03);
        transition: 0.2s;
    }

    ul.college-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    ul.college-list li {
        margin: 3px 0;
        position: relative;
        padding-left: 14px;
    }
    ul.college-list li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: #00c3ff;
    }

    .no-results {
        text-align: center;
        color: #9da6c7;
        margin-top: 20px;
        font-size: 0.9rem;
    }

    footer {
        text-align: center;
        padding: 15px;
        color: #8892a0;
        font-size: 13px;
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 30px;
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="logo">
    <a href="studentHome.php">Home</a>
    <a href="select_course.php" class="active">Select Courses</a>
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

<!-- MAIN -->
<div class="main">
    <h1>Select a Course</h1>

    <form method="POST">
        <label for="course">Choose a Course:</label>
        <select name="course" id="course" required>
            <option value="">Select a course</option>
            <?php while ($row = $courseList->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['course_name']); ?>" <?= ($selectedCourse === $row['course_name']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['course_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="interest">Subject of Interest (optional):</label>
        <input type="text" name="interest" id="interest" value="<?= htmlspecialchars($interest); ?>" placeholder="e.g. Programming, Finance, Biology">

        <button type="submit">Show Results</button>
    </form>

    <?php if (!empty($selectedCourse)): ?>
        <?php if ($results && $results->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Stream</th>
                            <th>Subjects</th>
                            <th>Interest Area</th>
                            <th>Top Colleges</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $results->fetch_assoc()):
                            $colleges = $conn->prepare("SELECT DISTINCT college_name FROM top_colleges WHERE course_id = ? ORDER BY college_name ASC");
                            $colleges->bind_param("i", $row['course_id']);
                            $colleges->execute();
                            $collegeRes = $colleges->get_result();
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['course_name']); ?></td>
                                <td><?= htmlspecialchars($row['required_stream']); ?></td>
                                <td><?= htmlspecialchars($row['required_subjects']); ?></td>
                                <td><?= htmlspecialchars($row['recommended_interest']); ?></td>
                                <td>
                                    <?php if ($collegeRes->num_rows > 0): ?>
                                        <ul class="college-list">
                                            <?php while ($c = $collegeRes->fetch_assoc()): ?>
                                                <li><?= htmlspecialchars($c['college_name']); ?></li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-results">No related courses or colleges found for your selection.</p>
        <?php endif; ?>
    <?php endif; ?>

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
