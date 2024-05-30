<?php
include 'db.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$first_name = $last_name = $email = $mobile = $password = "";
$first_name_err = $last_name_err = $email_err = $mobile_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter a first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter a last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    if (empty(trim($_POST["mobile"]))) {
        $mobile_err = "Please enter a mobile number.";
    } else {
        $mobile = trim($_POST["mobile"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($mobile_err) && empty($password_err)) {
        $sql = "INSERT INTO users (first_name, last_name, email, mobile, password, is_admin) VALUES (?, ?, ?, ?, ?, 1)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $param_first_name, $param_last_name, $param_email, $param_mobile, $param_password);
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_mobile = $mobile;
            $param_password = hash('sha256', $password);

            if ($stmt->execute()) {
                echo "<script>alert('New admin created successfully');</script>";
                $first_name = $last_name = $email = $mobile = $password = "";
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Admin</title>
    <link rel="stylesheet" href="project_Master.css">
    <script src="project_Script.js"></script>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="log_workout.php">Log Workout</a></li>
                <li><a href="workout_history.php">Workout History</a></li>
                <li><a href="statistics.php">Statistics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Create New Administrator</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateRegistrationForm();">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>">
            <span><?php echo $first_name_err; ?></span>

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>">
            <span><?php echo $last_name_err; ?></span>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo $email; ?>">
            <span><?php echo $email_err; ?></span>

            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>">
            <span><?php echo $mobile_err; ?></span>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="<?php echo $password; ?>">
            <span><?php echo $password_err; ?></span>

            <button type="submit">Create Admin</button>
        </form>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>

</html>
