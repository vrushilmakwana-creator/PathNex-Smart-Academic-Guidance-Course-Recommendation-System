<?php
session_start();
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

// Fetch logged-in student's stream (not used for filtering by default now)
$studentQuery = $conn->prepare("SELECT stream FROM student_profile WHERE email = ?");
$studentQuery->bind_param("s", $email);
$studentQuery->execute();
$studentResult = $studentQuery->get_result();
$student = $studentResult->fetch_assoc();

// Handle filter — default is now "All"
$selectedStream = $_GET['stream'] ?? 'All';

// Fetch distinct streams for dropdown
$streams = [];
$streamQuery = $conn->query("SELECT DISTINCT required_stream FROM course ORDER BY required_stream");
while ($row = $streamQuery->fetch_assoc()) {
    $streams[] = $row['required_stream'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Courses - PathNex</title>
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
        margin-bottom: 30px;
        font-weight: 600;
    }

    /* ===== FILTER BAR ===== */
    .filter {
        text-align: center;
        margin-bottom: 30px;
    }
    .filter label {
        font-weight: 500;
        margin-right: 10px;
        color: #aab2cc;
        font-size: 0.9rem;
    }
    select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(20,25,40,0.95);
        color: #e5e8ec;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
        transition: 0.3s;
    }
    select:hover {
        background: rgba(20,25,40,0.95); /* keep same background */
        color: #76e4ff; /* only color change */
    }
    select option {
        background-color: #0d0f14; /* matches page background */
        color: #e5e8ec;
    }

    /* ===== CATEGORY HEADER ===== */
    .category-header {
        margin-top: 40px;
        padding: 12px 20px;
        border-left: 4px solid #00c3ff;
        background: linear-gradient(90deg, rgba(0,195,255,0.08), rgba(75,140,255,0.08));
        border-radius: 8px;
        color: #76e4ff;
        font-weight: 600;
        font-size: 1.05rem;
        letter-spacing: 0.4px;
    }

    /* ===== TABLE STYLING ===== */
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
        text-transform: uppercase;
    }
    td {
        color: #d5d9e3;
        font-size: 0.88rem;
    }
    tr:hover td {
        background: rgba(255,255,255,0.03);
        transition: 0.2s;
    }

    .no-results {
        text-align: center;
        color: #9da6c7;
        margin: 25px 0;
        font-size: 0.9rem;
    }

    /* ===== FOOTER ===== */
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
    <a href="recommendation.php">Recommended Courses</a>
    <a href="courses.php" class="active">All Courses</a>
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
    <h1>Available Courses</h1>

    <div class="filter">
        <form method="GET">
            <label for="stream">Filter by Stream:</label>
            <select name="stream" id="stream" onchange="this.form.submit()">
                <option value="All" <?= ($selectedStream === "All") ? 'selected' : '' ?>>Show All</option>
                <?php foreach ($streams as $stream): ?>
                    <option value="<?= htmlspecialchars($stream) ?>" <?= ($selectedStream === $stream) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($stream) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php
    if ($selectedStream === "All") {
        foreach ($streams as $stream) {
            $stmt = $conn->prepare("SELECT * FROM course WHERE required_stream = ? ORDER BY course_name");
            $stmt->bind_param("s", $stream);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='category-header'>{$stream}</div>";
                echo "<div class='table-container'>
                        <table>
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Required Subjects</th>
                                    <th>Interest Area</th>
                                </tr>
                            </thead>
                            <tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['course_name']) . "</td>
                            <td>" . htmlspecialchars($row['required_subjects']) . "</td>
                            <td>" . htmlspecialchars($row['recommended_interest']) . "</td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            }
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM course WHERE required_stream = ? ORDER BY course_name");
        $stmt->bind_param("s", $selectedStream);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div class='category-header'>{$selectedStream}</div>";
            echo "<div class='table-container'>
                    <table>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Required Subjects</th>
                                <th>Interest Area</th>
                            </tr>
                        </thead>
                        <tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['course_name']) . "</td>
                        <td>" . htmlspecialchars($row['required_subjects']) . "</td>
                        <td>" . htmlspecialchars($row['recommended_interest']) . "</td>
                      </tr>";
            }
            echo "</tbody></table></div>";
        } else {
            echo "<div class='no-results'>No courses found for the selected stream.</div>";
        }
    }
    ?>

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
