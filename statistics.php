<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="project_Master.css">
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
        <h1>Statistics</h1>

        <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        include('db.php');

        $user_id = $_SESSION['user_id'];

        $query = "SELECT COUNT(*) AS total_workouts FROM workout WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_workouts = $row['total_workouts'];
        $stmt->close();

        $query = "SELECT SUM(duration) AS total_duration FROM workout WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_duration = $row['total_duration'];
        $stmt->close();

        $query = "SELECT SUM(distance) AS total_distance FROM workout WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_distance = $row['total_distance'];
        $stmt->close();

        $query = "SELECT exercise_id, COUNT(*) AS count FROM workout WHERE user_id = ? GROUP BY exercise_id ORDER BY count DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $most_common_exercise = $row['exercise_id'];
        $stmt->close();

        $query = "SELECT name FROM exercise WHERE exercise_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $most_common_exercise);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $most_common_exercise_name = $row['name'];
        $stmt->close();

        echo "<p>Total Workouts: $total_workouts</p>";
        echo "<p>Total Duration: $total_duration minutes</p>";
        echo "<p>Total Distance: $total_distance km</p>";
        echo "<p>Most Common Exercise: $most_common_exercise_name</p>";

        $conn->close();
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>
</html>
