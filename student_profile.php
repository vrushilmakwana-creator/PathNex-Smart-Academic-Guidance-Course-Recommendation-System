<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$profileQuery = $conn->prepare("SELECT * FROM student_profile WHERE email = ?");
$profileQuery->bind_param("s", $email);
$profileQuery->execute();
$profileResult = $profileQuery->get_result();
$profileExists = $profileResult->num_rows > 0;

// Delete profile
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_profile'])) {
    $delete = $conn->prepare("DELETE FROM student_profile WHERE email = ?");
    $delete->bind_param("s", $email);
    if ($delete->execute()) {
        header("Location: student_profile.php");
        exit();
    } else {
        $errorMsg = "Error deleting profile.";
    }
}

// Save or update profile
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_profile'])) {
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $languages_known = $_POST['languages_known'];
    $last_qualification = $_POST['last_qualification'];
    $stream = $_POST['stream'];
    $subjects = $_POST['subjects'];
    $interest = $_POST['interest'];
    $thinking_about_course = $_POST['thinking_about_course'];

    if ($profileExists) {
        $update = $conn->prepare("UPDATE student_profile SET age=?, gender=?, city=?, state=?, languages_known=?, last_qualification=?, stream=?, subjects=?, interest=?, thinking_about_course=? WHERE email=?");
        $update->bind_param("issssssssss", $age, $gender, $city, $state, $languages_known, $last_qualification, $stream, $subjects, $interest, $thinking_about_course, $email);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO student_profile (email, age, gender, city, state, languages_known, last_qualification, stream, subjects, interest, thinking_about_course) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sisssssssss", $email, $age, $gender, $city, $state, $languages_known, $last_qualification, $stream, $subjects, $interest, $thinking_about_course);
        $insert->execute();
    }
    header("Location: student_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Profile - PathNex</title>
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
    }

    /* ===== MAIN ===== */
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
        margin-bottom: 30px;
        font-weight: 600;
    }

    /* ===== PROFILE BOX ===== */
    .profile-box {
        background: rgba(18,24,35,0.95);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }
    .profile-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 30px;
        color: #b8c0d9;
        font-size: 0.9rem;
    }
    .profile-details p strong {
        color: #00c3ff;
        display: inline-block;
        width: 160px;
        font-size: 0.9rem;
    }

    .profile-form {
        background: rgba(18,24,35,0.9);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        max-width: 800px;
        margin: auto;
    }
    label {
        display: block;
        margin-top: 12px;
        font-weight: 500;
        color: #aab2cc;
        font-size: 0.9rem;
    }
    input, select, textarea {
        width: 100%;
        margin-top: 6px;
        padding: 10px 12px;
        border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        outline: none;
        font-size: 0.9rem;
    }
    textarea { resize: none; height: 80px; }
    input::placeholder { color: #888; }

    /* ===== BUTTONS ===== */
    .btn-row {
        text-align: center;
        margin-top: 25px;
    }
    button, .edit-btn {
        background: linear-gradient(135deg, #00c3ff, #4b8cff);
        border: none;
        color: white;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        font-size: 0.9rem;
    }
    button:hover, .edit-btn:hover {
        filter: brightness(1.15);
        box-shadow: 0 0 15px rgba(0,195,255,0.25);
    }
    .delete-btn {
        background: linear-gradient(135deg, #ff4e4e, #a00000);
    }
    .delete-btn:hover {
        box-shadow: 0 0 20px rgba(255,107,107,0.25);
    }

    footer {
        text-align: center;
        padding: 18px;
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
    <a href="select_course.php">Select Courses</a>
    <a href="recommendation.php">Recommended Courses</a>
    <a href="courses.php">All Courses</a>
    <a href="topColleges.php">Top Colleges</a>
    <a href="student_profile.php" class="active">Profile</a>
    <a href="help.php">Help & Support</a>
    <a href="about_us.php">About Us</a>
    <a href="documentation.php">Documentation</a>
    <a href="faq.php">FAQs</a>
    <a href="#" class="logout-btn" onclick="confirmLogout(event)">Logout</a>
</aside>

<!-- MAIN -->
<div class="main">
    <h1>Student Profile</h1>

    <?php if (isset($errorMsg)) echo "<p style='color:#ff6b6b; text-align:center;'>$errorMsg</p>"; ?>

    <?php if ($profileExists && !isset($_GET['edit'])) : ?>
        <?php $profile = $profileResult->fetch_assoc(); ?>
        <div class="profile-box">
            <div class="profile-details">
                <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']); ?></p>
                <p><strong>Age:</strong> <?= $profile['age']; ?></p>
                <p><strong>Gender:</strong> <?= $profile['gender']; ?></p>
                <p><strong>City:</strong> <?= $profile['city']; ?></p>
                <p><strong>State:</strong> <?= $profile['state']; ?></p>
                <p><strong>Languages Known:</strong> <?= $profile['languages_known']; ?></p>
                <p><strong>Last Qualification:</strong> <?= $profile['last_qualification']; ?></p>
                <p><strong>Stream:</strong> <?= $profile['stream']; ?></p>
                <p><strong>Subjects:</strong> <?= $profile['subjects']; ?></p>
                <p><strong>Interest:</strong> <?= $profile['interest']; ?></p>
                <p><strong>Thinking About:</strong> <?= $profile['thinking_about_course']; ?></p>
            </div>
            <div class="btn-row">
                <a href="?edit=1" class="edit-btn">Edit Profile</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_profile" value="1">
                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete your profile?');">Delete Profile</button>
                </form>
            </div>
        </div>

    <?php else : ?>
        <?php if ($profileExists) $profile = $profileResult->fetch_assoc(); ?>
        <form method="POST" class="profile-form">
            <label>Age:</label>
            <input type="number" name="age" value="<?= $profile['age'] ?? '' ?>" required>
            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($profile['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
            <label>City:</label>
            <input type="text" name="city" value="<?= $profile['city'] ?? '' ?>" required>
            <label>State:</label>
            <input type="text" name="state" value="<?= $profile['state'] ?? '' ?>" required>
            <label>Languages Known:</label>
            <input type="text" name="languages_known" value="<?= $profile['languages_known'] ?? '' ?>">
            <label>Last Qualification:</label>
            <input list="courses1" name="last_qualification" value="<?= $profile['last_qualification'] ?? '' ?>" required>
            <datalist id="courses1">
                <option value="B.Sc. IT (Bachelor of Science in Information Technology)">
                <option value="MBA (Master of Business Administration)">
                <option value="BCA (Bachelor of Computer Applications)">
                <option value="B.Tech (Bachelor of Technology)">
                <option value="MCA (Master of Computer Applications)">
            </datalist>
            <label>Stream:</label>
            <select name="stream" required>
                <option value="">Select Stream</option>
                <option value="Science" <?= ($profile['stream'] ?? '') === 'Science' ? 'selected' : '' ?>>Science</option>
                <option value="Commerce" <?= ($profile['stream'] ?? '') === 'Commerce' ? 'selected' : '' ?>>Commerce</option>
                <option value="Arts" <?= ($profile['stream'] ?? '') === 'Arts' ? 'selected' : '' ?>>Arts</option>
                <option value="CS/IT" <?= ($profile['stream'] ?? '') === 'CS/IT' ? 'selected' : '' ?>>CS/IT</option>
            </select>
            <label>Subjects:</label>
            <textarea name="subjects" required><?= $profile['subjects'] ?? '' ?></textarea>
            <label>Interest:</label>
            <input type="text" name="interest" value="<?= $profile['interest'] ?? '' ?>" required>
            <label>Thinking About Course:</label>
            <input type="text" name="thinking_about_course" value="<?= $profile['thinking_about_course'] ?? '' ?>" required>

            <div class="btn-row">
                <button type="submit" name="save_profile">Save Profile</button>
                <button type="button" onclick="window.location.href='student_profile.php'" class="delete-btn">Cancel</button>
            </div>
        </form>
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
