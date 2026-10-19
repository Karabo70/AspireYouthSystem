<?php
include 'config/db.php';

$message = "";

// Add a new tutor
if (isset($_POST['save'])) {

    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $subject   = trim($_POST['subject']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = $_POST['password'];

    // Hash the password before saving it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check whether the email already exists
    $check = mysqli_prepare(
        $conn,
        "SELECT TutorID FROM Tutors WHERE Email = ?"
    );

    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $message = "A tutor with this email already exists.";
    } else {

        // Insert the tutor securely
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO Tutors
            (FirstName, LastName, Subject, Email, PhoneNumber, Password)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $firstname,
            $lastname,
            $subject,
            $email,
            $phone,
            $hashedPassword
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Tutor added successfully!";
        } else {
            $message = "Could not add tutor. Please check the database.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_stmt_close($check);
}

// Get all tutors
$result = mysqli_query($conn, "SELECT * FROM Tutors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tutor Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">

    <h2>Tutor Management</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Back to Dashboard
    </a>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mb-4">

        <input type="text"
               name="firstname"
               class="form-control mb-2"
               placeholder="First Name"
               required>

        <input type="text"
               name="lastname"
               class="form-control mb-2"
               placeholder="Last Name"
               required>

        <input type="text"
               name="subject"
               class="form-control mb-2"
               placeholder="Subject"
               required>

        <input type="email"
               name="email"
               class="form-control mb-2"
               placeholder="Email Address"
               required>

        <input type="text"
               name="phone"
               class="form-control mb-2"
               placeholder="Phone Number"
               required>

        <input type="password"
               name="password"
               class="form-control mb-2"
               placeholder="Create Tutor Password"
               minlength="8"
               required>

        <button type="submit"
                name="save"
                class="btn btn-success">
            Add Tutor
        </button>

    </form>

    <hr>

    <h4>Registered Tutors</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Subject</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['TutorID']); ?></td>
                    <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                    <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>

        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>