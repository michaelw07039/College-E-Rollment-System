<?php
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["course_id"])) {
    $course_id = intval($_POST["course_id"]);

    // Check if already enrolled
    $check_sql = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "You are already enrolled in this course.";
    } else {
        // Check class size
        $count_sql = "SELECT COUNT(*) AS total FROM enrollments WHERE course_id = ?";
        $stmt = $conn->prepare($count_sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $count_result = $stmt->get_result();
        $count_row = $count_result->fetch_assoc();

        $max_sql = "SELECT max_students FROM courses WHERE course_id = ?";
        $stmt = $conn->prepare($max_sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $max_result = $stmt->get_result();
        $course_row = $max_result->fetch_assoc();

        if ($count_row["total"] >= $course_row["max_students"]) {
            $message = "This course is already full.";
        } else {
            $insert_sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ii", $user_id, $course_id);

            if ($stmt->execute()) {
                $message = "Course added successfully.";
            } else {
                $message = "Unable to add course.";
            }
        }
    }
}

$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Classes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Available Courses</h2>

        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Semester</th>
                <th>Max Students</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $courses_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["course_code"]); ?></td>
                    <td><?php echo htmlspecialchars($row["course_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["semester"]); ?></td>
                    <td><?php echo htmlspecialchars($row["max_students"]); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="course_id" value="<?php echo $row["course_id"]; ?>">
                            <button type="submit" class="btn">Add Course</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <p>
            <a href="dashboard.php">Back to Dashboard</a> |
            <a href="my_courses.php">View My Schedule</a>
        </p>
    </div>
</body>
</html>