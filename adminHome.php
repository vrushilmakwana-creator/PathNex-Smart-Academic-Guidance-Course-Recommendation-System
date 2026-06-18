<?php
session_start();

// --- SESSION VALIDATION ---
if (!isset($_SESSION['UserType']) || $_SESSION['UserType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// --- DATABASE CONNECTION ---
$conn = new mysqli("localhost", "root", "", "course_recommendation_system");
if ($conn->connect_error) {
    die("<h3 style='color:red;'>Database Connection Failed: " . $conn->connect_error . "</h3>");
}

// --- DELETE USER ---
if (isset($_POST['delete'])) {
    $emailToDelete = $_POST['email'];
    if (!in_array($emailToDelete, ['student123@gmail.com', 'admin123@gmail.com'])) {
        $stmt = $conn->prepare("DELETE FROM login WHERE email = ?");
        $stmt->bind_param("s", $emailToDelete);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: adminHome.php");
    exit();
}

// --- ADD ADMIN ---
if (isset($_POST['addAdmin'])) {
    $fullName = $_POST['newAdminFullName'];
    $pass = $_POST['newAdminPass'];
    $email = $_POST['newAdminEmail'];
    $mobile = $_POST['newAdminMobile'];

    $stmt = $conn->prepare("INSERT INTO login (full_name, pass, email, mobileNo, UserType) VALUES (?, ?, ?, ?, 'admin')");
    $stmt->bind_param("ssss", $fullName, $pass, $email, $mobile);
    $stmt->execute();
    $stmt->close();

    header("Location: adminHome.php");
    exit();
}

// --- ADD COURSE ---
if (isset($_POST['addCourse'])) {
    $courseName = $_POST['courseName'];
    $requiredStream = $_POST['requiredStream'];
    $requiredSubjects = $_POST['requiredSubjects'];
    $recommendedInterest = $_POST['recommendedInterest'];

    $stmt = $conn->prepare("INSERT INTO course (course_name, required_stream, required_subjects, recommended_interest) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $courseName, $requiredStream, $requiredSubjects, $recommendedInterest);
    $stmt->execute();
    $stmt->close();

    header("Location: adminHome.php");
    exit();
}

// --- DELETE COURSE ---
if (isset($_POST['deleteCourse'])) {
    $courseId = $_POST['courseId'];
    $stmt = $conn->prepare("DELETE FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $stmt->close();

    header("Location: adminHome.php");
    exit();
}

// --- ADD COLLEGE ---
if (isset($_POST['addCollege'])) {
    $collegeName = $_POST['college_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $website = $_POST['website'];
    $ranking = max(1, intval($_POST['ranking']));
    $coursesOffered = $_POST['courses_offered'];

    $stmt = $conn->prepare("INSERT INTO top_colleges (college_name, address, city, state, website, ranking, course_id) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssssi", $collegeName, $address, $city, $state, $website, $ranking);
    $stmt->execute();
    $stmt->close();

    header("Location: adminHome.php");
    exit();
}

// --- DELETE COLLEGE ---
if (isset($_POST['deleteCollege'])) {
    $collegeId = $_POST['collegeId'];
    $stmt = $conn->prepare("DELETE FROM top_colleges WHERE id = ?");
    $stmt->bind_param("i", $collegeId);
    $stmt->execute();
    $stmt->close();

    header("Location: adminHome.php");
    exit();
}

// --- FETCH DATA ---
$resultUsers = $conn->query("
    SELECT full_name, UserType, email, mobileNo
    FROM login
    ORDER BY 
      CASE 
        WHEN email IN ('student123@gmail.com', 'admin123@gmail.com') THEN 0 
        ELSE 1 
      END, 
      full_name ASC
");

$resultCourses = $conn->query("SELECT * FROM course ORDER BY course_name ASC");
$resultColleges = $conn->query("SELECT * FROM top_colleges ORDER BY college_name ASC");

$coursesList = [];
$coursesForDropdown = $conn->query("SELECT course_name FROM course ORDER BY course_name ASC");
while ($row = $coursesForDropdown->fetch_assoc()) {
    $coursesList[] = $row['course_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }

header {
    background-color: #333;
    color: white;
    padding: 15px 0;
    font-size: 22px;
    font-weight: bold;
    position: relative;
}

.logout-box {
    position: absolute;
    top: 10px;
    right: 25px;
    background-color: red;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}
.logout-box:hover { background-color: darkred; }

.nav-buttons {
    background-color: #eaeaea;
    display: flex;
    justify-content: center;
    padding: 10px 0;
    border-bottom: 1px solid #ccc;
}
.nav-buttons button {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 10px 20px;
    margin: 0 10px;
    cursor: pointer;
    border-radius: 5px;
    font-weight: bold;
    transition: 0.3s;
}
.nav-buttons button:hover, .nav-buttons button.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

table {
    width: 85%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
th, td { padding: 10px; border: 1px solid #ddd; font-size: 15px; }
th { background-color: #333; color: white; }

.box {
    background: white;
    padding: 20px;
    margin: 30px auto;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.input-group { margin-bottom: 15px; text-align: left; }
.input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.input-group input, .input-group select {
    width: 95%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.delete-btn, .add-btn, .locked-btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 14px;
    cursor: pointer;
}
.delete-btn { background-color: red; }
.delete-btn:hover { background-color: darkred; }
.add-btn { background-color: green; }
.add-btn:hover { background-color: darkgreen; }

.locked-btn {
    background-color: grey;
    cursor: not-allowed;
}
.locked-btn:hover { background-color: grey; }

.tags-input {
    display: flex;
    flex-wrap: wrap;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 6px;
    background-color: #fafafa;
    min-height: 40px;
    cursor: text;
}
.tag {
    background-color: #dce9ff;
    color: #004085;
    border-radius: 10px;
    padding: 3px 8px;
    margin: 4px;
    display: flex;
    align-items: center;
    font-size: 13px;
}
.tag span {
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
    color: #004085;
}
.tag span:hover { color: #c82333; }
</style>
</head>
<body>

<header>
    Admin Dashboard
    <button class="logout-box" onclick="confirmLogout()">Logout</button>
</header>

<div class="nav-buttons">
    <button class="active" onclick="showSection('users')">Manage Users</button>
    <button onclick="showSection('courses')">Manage Courses</button>
    <button onclick="showSection('colleges')">Manage Colleges</button>
</div>

<!-- USERS SECTION -->
<div id="users-section">
    <h2>Manage Users</h2>
    <table>
        <tr><th>Full Name</th><th>User Type</th><th>Email</th><th>Mobile No</th><th>Action</th></tr>
        <?php while ($user = $resultUsers->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($user['full_name']) ?></td>
            <td><?= htmlspecialchars($user['UserType']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['mobileNo']) ?></td>
            <td>
                <?php if (in_array($user['email'], ['student123@gmail.com','admin123@gmail.com'])): ?>
                    <button class="locked-btn">Locked</button>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        <button name="delete" class="delete-btn">Remove</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="box">
        <h3>Add Admin</h3>
        <form method="POST">
            <div class="input-group"><label>Full Name</label><input type="text" name="newAdminFullName" required></div>
            <div class="input-group"><label>Password</label><input type="password" name="newAdminPass" required></div>
            <div class="input-group"><label>Email</label><input type="email" name="newAdminEmail" required></div>
            <div class="input-group"><label>Mobile No</label><input type="text" name="newAdminMobile" required></div>
            <button name="addAdmin" class="add-btn">Add Admin</button>
        </form>
    </div>
</div>

<!-- COURSES SECTION -->
<div id="courses-section" style="display:none;">
    <h2>Manage Courses</h2>
    <table>
        <tr><th>ID</th><th>Course Name</th><th>Required Stream</th><th>Subjects</th><th>Interest</th><th>Action</th></tr>
        <?php while ($course = $resultCourses->fetch_assoc()): ?>
        <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= htmlspecialchars($course['course_name']) ?></td>
            <td><?= htmlspecialchars($course['required_stream']) ?></td>
            <td><?= htmlspecialchars($course['required_subjects']) ?></td>
            <td><?= htmlspecialchars($course['recommended_interest']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="courseId" value="<?= $course['course_id'] ?>">
                    <button name="deleteCourse" class="delete-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="box">
        <h3>Add Course</h3>
        <form method="POST">
            <div class="input-group"><label>Course Name</label><input type="text" name="courseName" required></div>
            <div class="input-group"><label>Required Stream</label>
                <select name="requiredStream" required>
                    <option value="">Select</option><option>Science</option><option>Commerce</option><option>Arts</option><option>CS/IT</option>
                </select>
            </div>
            <div class="input-group"><label>Required Subjects</label><input type="text" name="requiredSubjects" required></div>
            <div class="input-group"><label>Recommended Interest</label><input type="text" name="recommendedInterest" required></div>
            <button name="addCourse" class="add-btn">Add Course</button>
        </form>
    </div>
</div>

<!-- COLLEGES SECTION -->
<div id="colleges-section" style="display:none;">
    <h2>Manage Colleges</h2>
    <table>
        <tr><th>ID</th><th>College Name</th><th>City</th><th>State</th><th>Website</th><th>Ranking</th><th>Action</th></tr>
        <?php while ($college = $resultColleges->fetch_assoc()): ?>
        <tr>
            <td><?= $college['id'] ?></td>
            <td><?= htmlspecialchars($college['college_name']) ?></td>
            <td><?= htmlspecialchars($college['city']) ?></td>
            <td><?= htmlspecialchars($college['state']) ?></td>
            <td><a href="<?= htmlspecialchars($college['website']) ?>" target="_blank"><?= htmlspecialchars($college['website']) ?></a></td>
            <td><?= htmlspecialchars($college['ranking']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="collegeId" value="<?= $college['id'] ?>">
                    <button name="deleteCollege" class="delete-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="box">
        <h3>Add College</h3>
        <form method="POST">
            <div class="input-group"><label>College Name</label><input type="text" name="college_name" required></div>
            <div class="input-group"><label>Address</label><input type="text" name="address" required></div>
            <div class="input-group"><label>City</label><input type="text" name="city" required></div>
            <div class="input-group"><label>State</label><input type="text" name="state" required></div>
            <div class="input-group"><label>Website URL</label><input type="text" name="website" required></div>
            <div class="input-group"><label>Ranking</label><input type="number" name="ranking" min="1" required></div>
            <div class="input-group">
                <label>Courses Offered</label>
                <div class="tags-input" id="course-tags"></div>
                <select id="course-dropdown">
                    <option value="">Select a course</option>
                    <?php foreach ($coursesList as $courseName): ?>
                        <option value="<?= htmlspecialchars($courseName) ?>"><?= htmlspecialchars($courseName) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="courses_offered" id="courses_offered">
            </div>
            <button name="addCollege" class="add-btn">Add College</button>
        </form>
    </div>
</div>

<script>
function showSection(section){
    ['users','courses','colleges'].forEach(id=>{
        document.getElementById(id+'-section').style.display=(id===section)?'block':'none';
    });
    document.querySelectorAll('.nav-buttons button').forEach(btn=>btn.classList.remove('active'));
    event.target.classList.add('active');
}
function confirmLogout(){
    if(confirm("Are you sure you want to log out?")) window.location.href="logout.php";
}

// --- COURSE TAGS LOGIC ---
const dropdown=document.getElementById('course-dropdown');
const tagsContainer=document.getElementById('course-tags');
const hiddenInput=document.getElementById('courses_offered');
let tags=[];

dropdown.addEventListener('change',()=>{
    const value=dropdown.value.trim();
    if(value!==''&&!tags.includes(value)){
        tags.push(value);
        updateTags();
    }
    dropdown.value='';
});

function removeTag(tag){
    tags=tags.filter(t=>t!==tag);
    updateTags();
}
function updateTags(){
    tagsContainer.innerHTML='';
    tags.forEach(tag=>{
        const div=document.createElement('div');
        div.className='tag';
        div.textContent=tag;
        const span=document.createElement('span');
        span.textContent='×';
        span.onclick=()=>removeTag(tag);
        div.appendChild(span);
        tagsContainer.appendChild(div);
    });
    hiddenInput.value=tags.join(',');
}
</script>
</body>
</html>

<?php $conn->close(); ?>
