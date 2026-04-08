<?php
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($full_name) && !empty($phone) && !empty($email) && !empty($password)) {

        // Check if email already exists
        $check_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Email already exists. Please use another email.";
        } else {
            // Hash password before saving
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_sql = "INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ssss", $full_name, $phone, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful.";
            } else {
                $message = "Error: Unable to register user.";
            }
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Register New User</h2>

        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" required>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" required>

            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" class="btn">Register</button>
        </form>

        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>