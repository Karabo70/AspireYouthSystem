<?php
session_start();
include 'config/db.php';

$message = "";

// Process approval or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'], $_POST['tutor_id'])) {

    $tutorID = filter_var(
        $_POST['tutor_id'],
        FILTER_VALIDATE_INT
    );

    $action = $_POST['action'];

    if ($tutorID && in_array(
        $action,
        ['Approved', 'Rejected'],
        true
    )) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE Tutors SET Status = ? WHERE TutorID = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $action,
            $tutorID
        );

        if (mysqli_stmt_execute($stmt)
            && mysqli_stmt_affected_rows($stmt) >= 0) {
            $message = "Tutor status updated successfully.";
        } else {
            $message = "Unable to update tutor status.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Invalid tutor or action.";
    }
}

// Get all tutors
$result = mysqli_query(
    $conn,
    "SELECT TutorID, FirstName, LastName, Subject,
            Email, PhoneNumber, Status
     FROM Tutors
     ORDER BY
        CASE WHEN Status = 'Pending' THEN 0 ELSE 1 END,
        TutorID DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Tutor Applications</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container mt-4 mb-5">

    <h2>Manage Tutor Applications</h2>

    <p class="text-muted">
        Review tutor applications and approve or reject them.
    </p>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Back to Dashboard
    </a>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Subject</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row['TutorID']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['FirstName']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['LastName']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['Subject']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['Email']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['PhoneNumber']); ?>
                        </td>
                        <td>
                            <?php
                            $status = $row['Status'];
                            $badge = 'warning';

                            if ($status === 'Approved') {
                                $badge = 'success';
                            } elseif ($status === 'Rejected') {
                                $badge = 'danger';
                            }
                            ?>

                            <span class="badge text-bg-<?php echo $badge; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($status === 'Pending'): ?>

                                <form method="POST" class="d-inline">
                                    <input type="hidden"
                                           name="tutor_id"
                                           value="<?php echo htmlspecialchars($row['TutorID']); ?>">

                                    <button type="submit"
                                            name="action"
                                            value="Approved"
                                            class="btn btn-success btn-sm mb-1">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to reject this tutor?');">
                                    <input type="hidden"
                                           name="tutor_id"
                                           value="<?php echo htmlspecialchars($row['TutorID']); ?>">

                                    <button type="submit"
                                            name="action"
                                            value="Rejected"
                                            class="btn btn-danger btn-sm mb-1">
                                        Reject
                                    </button>
                                </form>

                            <?php else: ?>
                                <span class="text-muted">
                                    No action needed
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">
                        No tutors found.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>