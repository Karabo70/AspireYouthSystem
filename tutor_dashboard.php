<?php
session_start();
include 'config/db.php';

// 1. Check whether the tutor is logged in
if (!isset($_SESSION['TutorID'])) {
    header("Location: tutor_login.php");
    exit();
}

$tutorID = (int) $_SESSION['TutorID'];

// 2. Verify the tutor still exists and is approved
$stmt = mysqli_prepare(
    $conn,
    "SELECT TutorID, FirstName, LastName, Status
     FROM Tutors
     WHERE TutorID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $tutorID);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$tutorData = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Deny access if the account is missing or not approved
if (!$tutorData || $tutorData['Status'] !== 'Approved') {
    $_SESSION = [];
    session_destroy();

    header("Location: tutor_login.php");
    exit();
}

// 3. Get tutor details
$tutorName = $tutorData['FirstName'] . " " .
             $tutorData['LastName'];

// 4. Total students assigned to this tutor
$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(DISTINCT StudentID) AS total
     FROM Sessions
     WHERE TutorID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $tutorID);
mysqli_stmt_execute($stmt);

$totalStudents = (int) mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
)['total'];

mysqli_stmt_close($stmt);

// 5. Today's sessions
$today = date('Y-m-d');

$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM Sessions
     WHERE TutorID = ?
     AND SessionDate = ?"
);

mysqli_stmt_bind_param($stmt, "is", $tutorID, $today);
mysqli_stmt_execute($stmt);

$todaySessions = (int) mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
)['total'];

mysqli_stmt_close($stmt);

// 6. Attendance statistics
$stmt = mysqli_prepare(
    $conn,
    "SELECT
        COUNT(*) AS total,
        COALESCE(SUM(
            CASE WHEN Attendance.Status = 'Present'
                 THEN 1 ELSE 0 END
        ), 0) AS present
     FROM Attendance
     INNER JOIN Sessions
        ON Attendance.SessionID = Sessions.SessionID
     WHERE Sessions.TutorID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $tutorID);
mysqli_stmt_execute($stmt);

$attendanceData = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

$totalAttendance = (int) $attendanceData['total'];
$present = (int) $attendanceData['present'];

$attendanceRate = $totalAttendance > 0
    ? round(($present / $totalAttendance) * 100)
    : 0;

// 7. Today's session list
$stmt = mysqli_prepare(
    $conn,
    "SELECT
        Students.FirstName,
        Students.LastName,
        Sessions.SessionTime,
        Sessions.Venue
     FROM Sessions
     INNER JOIN Students
        ON Sessions.StudentID = Students.StudentID
     WHERE Sessions.TutorID = ?
     AND Sessions.SessionDate = ?
     ORDER BY Sessions.SessionTime"
);

mysqli_stmt_bind_param($stmt, "is", $tutorID, $today);
mysqli_stmt_execute($stmt);

$sessionList = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tutor Dashboard - AspireYouthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<?php include 'tutor_navbar.php'; ?>

<div class="container mt-4 mb-5">

    <h2>Welcome, <?php echo htmlspecialchars($tutorName); ?>!</h2>

    <p class="text-muted">
        Here is an overview of your tutoring activities.
    </p>

    <!-- Dashboard statistics -->
    <div class="row">

        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h2><?php echo $totalStudents; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5>Today's Sessions</h5>
                    <h2><?php echo $todaySessions; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h5>Attendance Rate</h5>
                    <h2><?php echo $attendanceRate; ?>%</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Today's sessions -->
    <div class="card mb-4">

        <div class="card-header">
            <h4>Today's Sessions</h4>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Time</th>
                            <th>Venue</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($sessionList) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($sessionList)): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['FirstName'] . ' ' .
                                        $row['LastName']
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['SessionTime'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['Venue'] ?? ''
                                    ); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="text-center">
                                No sessions scheduled for today.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Quick actions -->
    <div class="card">

        <div class="card-header">
            <h4>Quick Actions</h4>
        </div>

        <div class="card-body">

            <a href="students.php"
               class="btn btn-primary mb-2">
                View Students
            </a>

            <a href="attendance.php"
               class="btn btn-success mb-2">
                Mark Attendance
            </a>

            <a href="sessions.php"
               class="btn btn-warning mb-2">
                View Sessions
            </a>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>