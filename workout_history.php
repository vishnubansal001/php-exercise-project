<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('db.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT workout_date, exercise_id, duration, distance, notes FROM workout WHERE user_id = ?";

$filter_date = $filter_exercise = $sort_by = "";
$params = [$user_id];
$types = "i";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter_sort'])) {
    if (!empty($_POST['filter_date'])) {
        $filter_date = $_POST['filter_date'];
        $query .= " AND workout_date = ?";
        $params[] = $filter_date;
        $types .= 's';
    }

    if (!empty($_POST['filter_exercise'])) {
        $filter_exercise = $_POST['filter_exercise'];
        $query .= " AND exercise_id = ?";
        $params[] = $filter_exercise;
        $types .= 'i';
    }

    if (!empty($_POST['sort_by'])) {
        $sort_by = $_POST['sort_by'];
        switch ($sort_by) {
            case 'date_asc':
                $query .= " ORDER BY workout_date ASC";
                break;
            case 'date_desc':
                $query .= " ORDER BY workout_date DESC";
                break;
            case 'exercise_asc':
                $query .= " ORDER BY exercise_id ASC";
                break;
            case 'exercise_desc':
                $query .= " ORDER BY exercise_id DESC";
                break;
            case 'duration_asc':
                $query .= " ORDER BY duration ASC";
                break;
            case 'duration_desc':
                $query .= " ORDER BY duration DESC";
                break;
        }
    } else {
        $query .= " ORDER BY workout_date DESC";
    }
} else {
    $query .= " ORDER BY workout_date DESC";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout History</title>
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
        <h1>Workout History</h1>
        <form id="filterForm" method="POST" action="workout_history.php" onsubmit="return validateFilterForm();">
            <label for="filter_date">Filter by Date:</label>
            <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>"><br>

            <label for="filter_exercise">Filter by Exercise:</label>
            <select id="filter_exercise" name="filter_exercise">
                <option value="">All</option>
                <option value="1" <?php if ($filter_exercise == "1") echo 'selected'; ?>>Walking</option>
                <option value="2" <?php if ($filter_exercise == "2") echo 'selected'; ?>>Running</option>
                <option value="3" <?php if ($filter_exercise == "3") echo 'selected'; ?>>Cycling</option>
            </select><br>

            <label for="sort_by">Sort by:</label>
            <select id="sort_by" name="sort_by">
                <option value="date_asc" <?php if ($sort_by == "date_asc") echo 'selected'; ?>>Date (Ascending)</option>
                <option value="date_desc" <?php if ($sort_by == "date_desc") echo 'selected'; ?>>Date (Descending)</option>
                <option value="exercise_asc" <?php if ($sort_by == "exercise_asc") echo 'selected'; ?>>Exercise (Ascending)</option>
                <option value="exercise_desc" <?php if ($sort_by == "exercise_desc") echo 'selected'; ?>>Exercise (Descending)</option>
                <option value="duration_asc" <?php if ($sort_by == "duration_asc") echo 'selected'; ?>>Duration (Ascending)</option>
                <option value="duration_desc" <?php if ($sort_by == "duration_desc") echo 'selected'; ?>>Duration (Descending)</option>
            </select><br>

            <button type="submit" name="filter_sort">Apply Filters and Sort</button>
        </form>

        <table border='1'>
            <tr>
                <th>Date</th>
                <th>Exercise</th>
                <th>Duration</th>
                <th>Distance</th>
                <th>Notes</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['workout_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['exercise_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['distance']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Exercise Tracker</p>
    </footer>
</body>

</html>
