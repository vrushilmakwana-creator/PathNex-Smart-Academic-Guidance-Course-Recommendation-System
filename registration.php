<?php
$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "course_recommendation_system";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["password1"]);
    $userType = "student";

    $pattern = "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*()_+=-]).{8,}$/";

    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } elseif (!preg_match($pattern, $password)) {
        $errorMessage = "Password must be at least 8 characters, include an uppercase letter, a number, and a special character.";
    } else {
        $conn = new mysqli($servername, $username, $dbpassword, $dbname);
        if ($conn->connect_error) {
            $errorMessage = "Database connection failed.";
        } else {
            $stmt = $conn->prepare("INSERT INTO login (full_name, mobileNo, email, pass, UserType) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $mobile, $email, $password, $userType);

            if ($stmt->execute()) {
                $successMessage = "Registration successful! You can now login.";
                $name = $mobile = $email = "";
            } else {
                $errorMessage = "Error: Email already exists or registration failed.";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - PathNex</title>

<style>
*{
    margin:0; padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* ---- BRIGHT ROBOTIC BACKGROUND ---- */
body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;

    background: radial-gradient(circle at top, #1a2336, #0f1522 50%, #0c121d);
    color:#eaf0f8;
    padding:60px 0;
}

/* ---- ROBOTIC REGISTER BOX ---- */
.register-card{
    width:380px;
    padding:32px 28px;

    background:rgba(22,28,42,0.9);
    border:1.8px solid rgba(0,195,255,0.28);
    border-radius:3px;

    box-shadow:
        0 0 25px rgba(0,195,255,0.18),
        0 0 8px rgba(0,0,0,0.6),
        inset 0 0 12px rgba(255,255,255,0.05);

    backdrop-filter:blur(3px);
    text-align:center;
    animation:fadeIn 0.45s ease-out;
    position:relative;
}

/* ---- SHINY TOP LINE ---- */
.register-card::before{
    content:"";
    position:absolute;
    width:40%;
    height:2px;
    top:0; left:30%;
    background:linear-gradient(90deg,#00c3ff,#4b8cff,#a24cff);
    filter:drop-shadow(0 0 4px #00c3ff);
}

/* ---- HEADINGS ---- */
h2{
    font-size:1.6rem;
    margin-bottom:6px;
    background:linear-gradient(90deg,#00c3ff,#4b8cff,#a24cff);
    -webkit-background-clip:text;
    color:transparent;
}

.subtitle{
    font-size:0.9rem;
    color:#b3bfd4;
    margin-bottom:20px;
}

/* ---- INPUT GROUP ---- */
.input-group{
    text-align:left;
    margin-bottom:16px;
}

label{
    display:block;
    font-size:0.85rem;
    color:#9fb0cc;
    margin-bottom:6px;
}

/* ---- INPUTS ---- */
input{
    width:100%;
    padding:9px 12px;

    border-radius:2px;
    background:rgba(255,255,255,0.05);
    border:1px solid rgba(255,255,255,0.1);
    color:white;

    font-size:0.9rem;
    transition:0.25s ease;
}

input:focus{
    border-color:#00c3ff;
    box-shadow:0 0 8px rgba(0,195,255,0.25);
    outline:none;
}

/* ---- BUTTON ---- */
.register-btn{
    width:100%;
    padding:10px;

    border:none;
    border-radius:3px;

    background:linear-gradient(90deg,#00c3ff,#4b8cff);
    color:white;
    font-weight:600;
    cursor:pointer;
    font-size:0.95rem;

    transition:0.2s ease;
    margin-top:8px;
}

.register-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 0 14px rgba(0,195,255,0.35);
}

/* ---- MESSAGES ---- */
.error, .success{
    padding:10px;
    border-radius:3px;
    margin-bottom:14px;
    font-size:0.88rem;
}

.error{
    background:rgba(255,0,0,0.18);
    border:1px solid rgba(255,0,0,0.3);
    color:#ff6b6b;
}

.success{
    background:rgba(0,255,150,0.08);
    border:1px solid rgba(0,255,150,0.2);
    color:#00ffa6;
}

/* ---- LINK ---- */
.link{
    margin-top:14px;
    font-size:0.85rem;
    color:#a5b1c7;
}

.link a{
    color:#00c3ff;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(10px);}
    to{opacity:1; transform:translateY(0);}
}
</style>
</head>

<body>

<div class="register-card">
    <h2>Create Account</h2>
    <p class="subtitle">Join PathNex and start your academic journey</p>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php elseif (!empty($successMessage)): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" required placeholder="Enter your full name"
                   value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="mobile" required placeholder="Enter your phone number"
                   value="<?php echo isset($mobile) ? htmlspecialchars($mobile) : ''; ?>">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Enter your email"
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="password1" required placeholder="Re-enter password">
        </div>

        <button type="submit" class="register-btn">Register</button>

        <p class="link">Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </form>
</div>

</body>
</html>
