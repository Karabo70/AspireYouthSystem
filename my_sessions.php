<?php
session_start();
include 'config/db.php';

// Check tutor login
if (!isset($_SESSION['TutorID'])) {
    header("Location: tutor_login.php");
    exit();
}

$tutorID = (int) $_SESSION['TutorID'];

// Check tutor approval status
$stmt = $conn->prepare(
    "SELECT Status FROM Tutors WHERE TutorID = ?"
);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$result = $stmt->get_result();
$tutor = $result->fetch_assoc();
$stmt->close();

if (!$tutor || $tutor['Status'] !== 'Approved') {
    session_unset();
    session_destroy();
    header("Location: tutor_login.php");
    exit();
}

// Fetch only this tutor's sessions
$stmt = $conn->prepare("
    SELECT Sessions.*,
           Students.FirstName,
           Students.LastName
    FROM Sessions
    INNER JOIN Students
        ON Sessions.StudentID = Students.StudentID
    WHERE Sessions.TutorID = ?
    ORDER BY Sessions.SessionDate ASC,
             Sessions.SessionTime ASC
");

$stmt->bind_param("i", $tutorID);
$stmt->execute();
$sessions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sessions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<?php include 'tutor_navbar.php'; ?>

<div class="container mt-4">
    <h2>My Sessions</h2>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Scheduled Tutoring Sessions
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Session ID</th>
                            <th>Student</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($sessions->num_rows > 0): ?>
                        <?php while ($row = $sessions->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['SessionID']) ?></td>
                                <td>
                                    <?= htmlspecialchars(
                                        $row['FirstName'] . ' ' . $row['LastName']
                                    ) ?>
                                </td>
                                <td><?= htmlspecialchars($row['SessionDate']) ?></td>
                                <td><?= htmlspecialchars($row['SessionTime']) ?></td>
                                <td><?= htmlspecialchars($row['Venue'] ?? '') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                No sessions assigned.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="tutor_dashboard.php" class="btn btn-secondary mt-3">
        Back to Dashboard
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
?>