<?php
include 'config/db.php';

$message = "";
$messageType = "danger";

if (isset($_POST['register'])) {

    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $subject   = trim($_POST['subject']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $message = "Password must contain at least 8 characters.";
    } else {

        // Check whether the email already exists
        $check = mysqli_prepare(
            $conn,
            "SELECT TutorID FROM Tutors WHERE Email = ?"
        );

        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "This email address is already registered.";
        } else {

            // Securely hash the password
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Register tutor with Pending status
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO Tutors
                (FirstName, LastName, Subject, Email,
                 PhoneNumber, Password, Status)
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
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
                $message = "Registration successful! Your application is awaiting admin approval.";
                $messageType = "success";
            } else {
                $message = "Registration failed. Please try again.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tutor Registration - AspireYouthSystem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h2 class="text-center mb-2">
                        Tutor Registration
                    </h2>

                    <p class="text-center text-muted mb-4">
                        Create your tutor account.
                        Your application must be approved by an admin.
                    </p>

                    <?php if ($message !== ""): ?>
                        <div class="alert alert-<?php
                            echo htmlspecialchars($messageType);
                        ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname"
                               class="form-control mb-3"
                               required>

                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname"
                               class="form-control mb-3"
                               required>

                        <label class="form-label">Subject</label>
                        <input type="text" name="subject"
                               class="form-control mb-3"
                               placeholder="e.g. Mathematics"
                               required>

                        <label class="form-label">Email Address</label>
                        <input type="email" name="email"
                               class="form-control mb-3"
                               required>

                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone"
                               class="form-control mb-3"
                               required>

                        <label class="form-label">Create Password</label>
                        <input type="password" name="password"
                               class="form-control mb-4"
                               minlength="8"
                               autocomplete="new-password"
                               required>

                        <button type="submit" name="register"
                                class="btn btn-success w-100">
                            Register as Tutor
                        </button>

                    </form>

                    <p class="text-center mt-3 mb-0">
                        Already registered?
                        <a href="tutor_login.php">Log in</a>
                    </p>

                    <p class="text-center mt-3 mb-0">
                        <a href="index.php">Back to Home</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>