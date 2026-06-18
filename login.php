<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $conn = new mysqli("localhost", "root", "", "course_recommendation_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT pass, UserType FROM login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($password === $row["pass"]) {
            $_SESSION["email"] = $email;
            $_SESSION["UserType"] = strtolower($row["UserType"]);

            if ($_SESSION["UserType"] === "student") header("Location: studentHome.php");
            elseif ($_SESSION["UserType"] === "admin") header("Location: adminHome.php");
            elseif ($_SESSION["UserType"] === "doctor") header("Location: Doctor_home.php");
            exit();
        } else {
            $errorMessage = "Incorrect password!";
        }
    } else {
        $errorMessage = "Invalid login details!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - PathNex</title>

<style>
*{
    margin:0; padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* ---------------------- BRIGHTER FUTURISTIC BACKGROUND ---------------------- */
body{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;

    background: radial-gradient(circle at top, #1a2336, #0f1522 50%, #0c121d);
    color:#eaf0f8;
}

/* ---------------------- SUPER-SHARP ROBOTIC CARD ---------------------- */
.login-box{
    width:350px;
    padding:28px 26px;

    background:rgba(22, 28, 42, 0.9);
    border:1.8px solid rgba(0,195,255,0.28);
    border-radius:3px;

    box-shadow:
        0 0 25px rgba(0,195,255,0.18),
        0 0 8px rgba(0,0,0,0.6),
        inset 0 0 12px rgba(255,255,255,0.05);

    backdrop-filter: blur(3px);
    transform: translateY(0);
    animation: robotPop 0.35s ease-out;
}

@keyframes robotPop{
    from{opacity:0; transform:translateY(10px) scale(0.96);}
    to{opacity:1; transform:translateY(0) scale(1);}
}

/* Robotic Panel Line (top) */
.login-box::before{
    content:"";
    position:absolute;
    width:40%;
    height:2px;
    top:0; left:30%;
    background:linear-gradient(90deg,#00c3ff,#4b8cff,#a24cff);
    filter:drop-shadow(0 0 4px #00c3ff);
}

/* ---------------------- HEADING ---------------------- */
.title{
    text-align:center;
    font-size:1.7rem;
    font-weight:600;
    margin-bottom:6px;

    background:linear-gradient(90deg,#00c3ff,#4b8cff,#a24cff);
    -webkit-background-clip:text;
    color:transparent;
}

.subtitle{
    text-align:center;
    color:#b3bfd4;
    font-size:0.88rem;
    margin-bottom:22px;
}

/* ---------------------- INPUTS ---------------------- */
.label{
    font-size:0.85rem;
    margin-bottom:4px;
    color:#9fb0cc;
}

.input-field{
    width:100%;
    padding:9px 12px;
    margin-bottom:16px;

    border-radius:2px;
    border:1px solid rgba(255,255,255,0.1);
    background:rgba(255,255,255,0.05);
    color:#fff;

    transition:0.25s ease;
}

.input-field:focus{
    border-color:#00c3ff;
    box-shadow:0 0 8px rgba(0,195,255,0.25);
    outline:none;
}

/* ---------------------- REMEMBER + FORGOT ---------------------- */
.row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
    color:#a5b0c5;
    font-size:0.8rem;
}

.row a{
    color:#00c3ff;
    text-decoration:none;
}

/* ---------------------- BUTTON ---------------------- */
.login-btn{
    width:100%;
    padding:10px;
    border:none;
    border-radius:3px;

    background:linear-gradient(90deg,#00c3ff,#4b8cff);
    color:white;

    font-size:1rem;
    font-weight:600;
    cursor:pointer;

    transition:0.2s ease;
}

.login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 0 14px rgba(0,195,255,0.35);
}

/* ---------------------- ERROR ---------------------- */
.error{
    background:rgba(255,0,0,0.18);
    border:1px solid rgba(255,0,0,0.3);
    color:#ff6b6b;
    padding:10px;
    text-align:center;
    border-radius:3px;
    margin-bottom:14px;
    font-size:0.88rem;
}

/* ---------------------- REGISTER ---------------------- */
.reg{
    margin-top:14px;
    text-align:center;
    font-size:0.9rem;
    color:#a5b1c7;
}
.reg a{
    color:#00c3ff;
}
</style>
</head>
<body>

<div class="login-box">

    <h2 class="title">Login</h2>
    <p class="subtitle">Access your PathNex dashboard</p>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST">

        <label class="label">Email</label>
        <input type="email" name="email" class="input-field" required>

        <label class="label">Password</label>
        <input type="password" name="password" class="input-field" required>

        <div class="row">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="login-btn">Login</button>

        <p class="reg">Don't have an account?
            <a href="registration.php">Register now</a>
        </p>

    </form>

</div>

</body>
</html>
