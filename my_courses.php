<?php
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT e.enrollment_id, c.course_code, c.course_name, c.semester
        FROM enrollments e
        INNER JOIN courses c ON e.course_id = c.course_id
        WHERE e.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>My Registered Classes</h2>

        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Semester</th>
                <th>Action</th>
            </tr>

            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["course_code"]); ?></td>
                        <td><?php echo htmlspecialchars($row["course_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["semester"]); ?></td>
                        <td>
                            <a class="btn" href="drop_course.php?id=<?php echo $row["enrollment_id"]; ?>">Drop</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">You are not registered for any classes.</td>
                </tr>
            <?php endif; ?>
        </table>

        <p>
            <a href="courses.php">Add More Courses</a> |
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</body>
</html>