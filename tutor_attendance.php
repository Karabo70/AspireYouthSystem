<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['TutorID'])) {
    header("Location: tutor_login.php");
    exit();
}

$tutorID = (int) $_SESSION['TutorID'];
$message = "";
$messageType = "danger";

// Confirm the tutor is approved
$stmt = $conn->prepare(
    "SELECT Status FROM Tutors WHERE TutorID = ?"
);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$tutor || $tutor['Status'] !== 'Approved') {
    session_unset();
    session_destroy();
    header("Location: tutor_login.php");
    exit();
}

// Save attendance securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {

    $sessionID = filter_input(
        INPUT_POST,
        'session',
        FILTER_VALIDATE_INT
    );

    $status = $_POST['status'] ?? '';

    if (!$sessionID || !in_array($status, ['Present', 'Absent'], true)) {
        $message = "Please select a valid session and attendance status.";
    } else {

        // Verify that this session belongs to the logged-in tutor
        $stmt = $conn->prepare(
            "SELECT SessionID
             FROM Sessions
             WHERE SessionID = ? AND TutorID = ?"
        );
        $stmt->bind_param("ii", $sessionID, $tutorID);
        $stmt->execute();
        $assignedSession = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$assignedSession) {
            $message = "You can only record attendance for your own sessions.";
        } else {

            $date = date("Y-m-d");

            // Check whether attendance was already recorded today
            $stmt = $conn->prepare(
                "SELECT AttendanceID
                 FROM Attendance
                 WHERE SessionID = ? AND AttendanceDate = ?"
            );
            $stmt->bind_param("is", $sessionID, $date);
            $stmt->execute();
            $existing = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($existing) {
                $message = "Attendance has already been recorded for this session today.";
            } else {

                $stmt = $conn->prepare(
                    "INSERT INTO Attendance
                     (SessionID, AttendanceDate, Status)
                     VALUES (?, ?, ?)"
                );
                $stmt->bind_param("iss", $sessionID, $date, $status);

                if ($stmt->execute()) {
                    $message = "Attendance saved successfully.";
                    $messageType = "success";
                } else {
                    $message = "Unable to save attendance. Please try again.";
                }

                $stmt->close();
            }
        }
    }
}

// Load only sessions assigned to this tutor
$stmt = $conn->prepare(
    "SELECT Sessions.SessionID,
            Students.FirstName,
            Students.LastName,
            Sessions.SessionDate,
            Sessions.SessionTime
     FROM Sessions
     INNER JOIN Students
        ON Sessions.StudentID = Students.StudentID
     WHERE Sessions.TutorID = ?
     ORDER BY Sessions.SessionDate ASC, Sessions.SessionTime ASC"
);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$sessions = $stmt->get_result();
$stmt->close();

// Load attendance records belonging to this tutor's sessions
$stmt = $conn->prepare(
    "SELECT Attendance.AttendanceID,
            Attendance.Status,
            Attendance.AttendanceDate,
            Students.FirstName,
            Students.LastName,
            Sessions.SessionID
     FROM Attendance
     INNER JOIN Sessions
        ON Attendance.SessionID = Sessions.SessionID
     INNER JOIN Students
        ON Sessions.StudentID = Students.StudentID
     WHERE Sessions.TutorID = ?
     ORDER BY Attendance.AttendanceDate DESC,
              Attendance.AttendanceID DESC"
);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$attendance = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<?php include 'tutor_navbar.php'; ?>

<div class="container mt-4 mb-5">

    <h2>My Attendance</h2>
    <p>Record attendance for your assigned tutoring sessions.</p>

    <?php if ($message !== ""): ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">
            Mark Attendance
        </div>

        <div class="card-body">

            <form method="POST">

                <label class="form-label">Select Session</label>

                <select name="session" class="form-select mb-3" required>
                    <option value="">Choose Session</option>

                    <?php while ($row = $sessions->fetch_assoc()): ?>
                        <option value="<?= (int) $row['SessionID'] ?>">
                            <?= htmlspecialchars(
                                $row['FirstName'] . ' ' .
                                $row['LastName'] . ' - ' .
                                $row['SessionDate'] . ' ' .
                                $row['SessionTime']
                            ) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label class="form-label">Status</label>

                <select name="status" class="form-select mb-3" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>

                <button type="submit" name="save" class="btn btn-success">
                    Save Attendance
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            My Attendance Records
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Attendance ID</th>
                            <th>Student</th>
                            <th>Session ID</th>
                            <th>Status</th>
                            <th>Date Recorded</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($attendance->num_rows > 0): ?>

                            <?php while ($row = $attendance->fetch_assoc()): ?>
                                <tr>
                                    <td><?= (int) $row['AttendanceID'] ?></td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['FirstName'] . ' ' .
                                            $row['LastName']
                                        ) ?>
                                    </td>

                                    <td><?= (int) $row['SessionID'] ?></td>

                                    <td>
                                        <span class="badge text-bg-<?= $row['Status'] === 'Present' ? 'success' : 'danger' ?>">
                                            <?= htmlspecialchars($row['Status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['AttendanceDate']) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center">
                                    No attendance records found for your sessions.
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