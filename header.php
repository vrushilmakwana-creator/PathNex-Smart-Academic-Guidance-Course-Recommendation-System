<style>
    /* Header Reset & Base */
    .header-container, .header-search, .header-dropdown, .header-dropbtn {
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 40px;
        background-color: #f5f5f5;
        border-bottom: 1px solid #ddd;
    }

    .header-logo {
        max-width: 160px;
        height: auto;
    }

    .header-search {
        flex-grow: 1;
        margin: 0 20px;
        display: flex;
    }

    .header-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px 0 0 5px;
        outline: none;
        font-size: 14px;
    }

    .header-search button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
    }

    .header-search button:hover {
        background-color: #0056b3;
    }

    .header-dropdown {
        position: relative;
        display: inline-block;
    }

    .header-dropbtn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        padding: 5px;
    }

    .header-dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 120px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border-radius: 5px;
        overflow: hidden;
        z-index: 1;
    }

    .header-dropdown-content a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: black;
        text-align: center;
        font-size: 14px;
    }

    .header-dropdown-content a:hover {
        background-color: #f0f0f0;
    }

    .header-dropdown-content.show {
        display: block;
    }

    /* Navigation Bar */
    .header-nav {
        display: flex;
        justify-content: center;
        background-color: #333;
        color: #fff;
        padding: 15px 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .header-nav a {
        margin: 0 25px;
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        position: relative;
        transition: 0.3s;
    }

    .header-nav a:hover::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 100%;
        height: 3px;
        border-radius: 2px;
        background-color: #007bff;
    }

    .header-nav a:hover {
        color: #007bff;
    }
    .header-wrapper {
        width: 100%;
        max-width: 100%;
    }

</style>

<div class="header-wrapper">
    <header class="header-container">
    <img src="Official_LOGO_MedicalAppointmwntManagement.png" alt="logo" class="header-logo">

    <form action="Search_results.php" method="POST" class="header-search">
        <input type="text" name="query" placeholder="Search by course, specialization or college..">
        <button type="submit">🔍</button>
    </form>

    <div class="header-dropdown">
        <button class="header-dropbtn" onclick="toggleDropdown()">&#x22EE;</button>
        <div class="header-dropdown-content" id="dropdownMenu">
            <a href="logout.php">Log Out</a>
        </div>
    </div>
</header>

<nav class="header-nav">
    <a href="studentHome.php">Home</a>
    <a href="courses.php">View Courses</a>
    <a href="topColleges.php">Top Colleges</a>
    <a href="student_profile.php">Profile</a>
</nav>
</div>



<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.header-dropbtn')) {
            var dropdowns = document.getElementsByClassName("header-dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].classList.contains('show')) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
    }
</script>
