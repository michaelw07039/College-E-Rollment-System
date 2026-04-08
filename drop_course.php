<?php
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $enrollment_id = intval($_GET["id"]);
    $user_id = $_SESSION["user_id"];

    $sql = "DELETE FROM enrollments WHERE enrollment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $enrollment_id, $user_id);
    $stmt->execute();
}

header("Location: my_courses.php");
exit();
?>