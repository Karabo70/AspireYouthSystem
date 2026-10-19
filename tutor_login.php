<?php
session_start();
include 'config/db.php';

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Find tutor by email
    $stmt = mysqli_prepare(
        $conn,
        "SELECT TutorID, FirstName, LastName, Email,
                Password, Status
         FROM Tutors
         WHERE Email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $tutor = mysqli_fetch_assoc($result);

    // Check whether the tutor exists and the password is correct
    if ($tutor && password_verify($password, $tutor['Password'])) {

        // Check approval status
        if ($tutor['Status'] === 'Pending') {

            $message = "Your registration is awaiting admin approval.";

        } elseif ($tutor['Status'] === 'Rejected') {

            $message = "Your tutor application has been rejected. Please contact the administrator.";

        } elseif ($tutor['Status'] === 'Approved') {

            // Regenerate session ID after successful login
            session_regenerate_id(true);

            $_SESSION['TutorID'] = $tutor['TutorID'];
            $_SESSION['TutorName'] =
                $tutor['FirstName'] . " " . $tutor['LastName'];

            $_SESSION['TutorEmail'] = $tutor['Email'];

            header("Location: tutor_dashboard.php");
            exit();

        } else {

            $message = "Your account status is invalid. Please contact the administrator.";
        }

    } else {

        $message = "Invalid email address or password.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tutor Login - AspireYouthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h3>Tutor Login</h3>
                </div>

                <div class="card-body">

                    <?php if ($message !== ""): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <label for="email" class="form-label">
                            Email Address
                        </label>

                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control mb-3"
                               autocomplete="username"
                               required>

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control mb-3"
                               autocomplete="current-password"
                               required>

                        <button type="submit"
                                name="login"
                                class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <p>Not registered yet?</p>

                        <a href="tutor_register.php"
                           class="btn btn-outline-success w-100 mb-2">
                            Register as Tutor
                        </a>

                        <a href="index.php"
                           class="btn btn-secondary w-100">
                            Back to Home
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>