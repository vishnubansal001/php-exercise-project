<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Tracker</title>
    <link rel="stylesheet" href="project_Master.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="log_workout.php">Log Workout</a></li>
                    <li><a href="workout_history.php">Workout History</a></li>
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="register.php">User Registration</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Welcome to the Exercise Tracker</h1>
        <p>This is a simple exercise tracker to help you log your workouts and view your progress.</p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>
</html>
