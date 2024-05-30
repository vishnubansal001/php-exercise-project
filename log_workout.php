<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Workout</title>
    <link rel="stylesheet" href="project_Master.css">
    <script src="project_Script.js" defer></script>
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
        <h1>Log Workout</h1>
        <form id="workoutForm" method="POST" action="log_workout.php">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br>

            <label for="exercise">Type of Exercise:</label>
            <select id="exercise" name="exercise" required>
                <option value="1">Walking</option>
                <option value="2">Running</option>
                <option value="3">Cycling</option>
            </select><br>

            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" required><br>

            <label for="distance">Distance (km):</label>
            <input type="number" step="0.01" id="distance" name="distance" required><br>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea><br>

            <button type="submit" name="log_workout">Log Workout</button>
        </form>

        <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log_workout'])) {
            include('db.php');

            $user_id = $_SESSION['user_id'];
            $date = $_POST['date'];
            $exercise = $_POST['exercise'];
            $duration = $_POST['duration'];
            $distance = $_POST['distance'];
            $notes = $_POST['notes'];

            if (!empty($date) && !empty($exercise) && !empty($duration) && !empty($distance)) {
                $stmt = $conn->prepare("INSERT INTO workout (user_id, workout_date, exercise_id, duration, distance, notes) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isiiis", $user_id, $date, $exercise, $duration, $distance, $notes);

                if ($stmt->execute()) {
                    echo "<p>Workout logged successfully!</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p>All fields marked with * are required.</p>";
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
