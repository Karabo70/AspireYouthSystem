<?php
session_start();
include 'config/db.php';

// Check whether a tutor is logged in
if (!isset($_SESSION['TutorID'])) {
    header("Location: tutor_login.php");
    exit();
}

$tutorID = (int) $_SESSION['TutorID'];

// Verify that the tutor is still approved
$stmt = mysqli_prepare(
    $conn,
    "SELECT Status FROM Tutors WHERE TutorID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $tutorID);
mysqli_stmt_execute($stmt);

$tutorResult = mysqli_stmt_get_result($stmt);
$tutor = mysqli_fetch_assoc($tutorResult);

mysqli_stmt_close($stmt);

if (!$tutor || $tutor['Status'] !== 'Approved') {
    $_SESSION = [];
    session_destroy();

    header("Location: tutor_login.php");
    exit();
}

// Get only students assigned to this tutor
$stmt = mysqli_prepare(
    $conn,
    "SELECT DISTINCT
        Students.StudentID,
        Students.FirstName,
        Students.LastName,
        Students.Email,
        Students.PhoneNumber,
        Students.Status
     FROM Students
     INNER JOIN Sessions
        ON Students.StudentID = Sessions.StudentID
     WHERE Sessions.TutorID = ?
     ORDER BY Students.FirstName, Students.LastName"
);

mysqli_stmt_bind_param($stmt, "i", $tutorID);
mysqli_stmt_execute($stmt);

$students = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Students - AspireYouthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<?php include 'tutor_navbar.php'; ?>

<div class="container mt-4 mb-5">

    <h2>My Students</h2>

    <p class="text-muted">
        View the students assigned to your tutoring sessions.
    </p>

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            Assigned Students
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($students) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($students)): ?>

                            <tr>
                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['StudentID']
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['FirstName']
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['LastName']
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['Email']
                                    ); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $row['PhoneNumber'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?php if ($row['Status'] === 'Approved'): ?>

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    <?php elseif ($row['Status'] === 'Rejected'): ?>

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center">
                                No students assigned to you yet.
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