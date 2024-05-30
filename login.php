<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="project_Master.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">User Registration</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>

        <?php
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include('db.php');
            
            $email = $conn->real_escape_string($_POST['email']);
            $password = hash('sha256', $_POST['password']);
            
            $query = "SELECT user_id, first_name, last_name, is_admin, last_login FROM users WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['last_login'] = $user['last_login'];

                $updateQuery = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $user['user_id']);
                $updateStmt->execute();

                if ($_SESSION['is_admin']){
                    header('Location: manage_admin.php');
                    exit;
                }
                else{
                    header('Location: index.php');
                    exit;
                }   
            } else {
                echo "<p style='color:red;'>Invalid email or password.</p>";
            }
            $stmt->close();
            $conn->close();
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>
</html>
