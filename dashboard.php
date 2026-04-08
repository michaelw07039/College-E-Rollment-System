<?php
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h1>
        <p>Select an option below.</p>

        <div class="button-group">
            <a href="courses.php" class="btn">Register for Classes</a>
            <a href="my_courses.php" class="btn">My Schedule</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>