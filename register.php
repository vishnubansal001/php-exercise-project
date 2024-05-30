<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="project_Master.css">
    <script src="s1.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>User Registration</h1>
        <form id="registrationForm" method="POST" action="register.php">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile"><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age"><br>

            <label for="height">Height (cm):</label>
            <input type="number" id="height" name="height"><br>

            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight"><br>

            <button type="submit" name="register">Register</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
            include('db.php');

            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $mobile = trim($_POST['mobile']);
            $password = hash('sha256', trim($_POST['password']));
            $age = !empty($_POST['age']) ? (int)$_POST['age'] : NULL;
            $height = !empty($_POST['height']) ? (int)$_POST['height'] : NULL;
            $weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : NULL;

            $errors = [];
            if (empty($first_name)) $errors[] = "First name is required.";
            if (empty($last_name)) $errors[] = "Last name is required.";
            if (empty($email)) $errors[] = "Email is required.";
            if (empty($password)) $errors[] = "Password is required.";
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }

            if (empty($errors)) {
                $email_check_query = "SELECT email FROM users WHERE email = ?";
                $stmt = $conn->prepare($email_check_query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo "<p>Email is already registered. Please use a different email.</p>";
                } else {
                    $stmt->close();
                    $insert_query = "INSERT INTO users (first_name, last_name, email, mobile, password, age, height, weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("sssssiid", $first_name, $last_name, $email, $mobile, $password, $age, $height, $weight);

                    if ($stmt->execute()) {
                        echo "<p>Registration successful! .</p>";
                        header('Location: login.php');
                        exit;
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                }
            } else {
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
            }

            $conn->close();
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>
</html>
