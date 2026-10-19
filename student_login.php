<?php
include 'config/db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
    "SELECT * FROM Students
    WHERE Email='$email'
    AND Password='$password'
    AND Status='Approved'");

    if(mysqli_num_rows($query) > 0){

        $student = mysqli_fetch_assoc($query);

        header("Location: student_dashboard.php?id=".$student['StudentID']);
        exit();

    }else{

        echo "<script>alert('Invalid login details or account not approved.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body">

<h3 class="text-center mb-4">
Student Login
</h3>

<form method="POST">

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="Email Address"
required>

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="Password"
required>

<button
name="login"
class="btn btn-primary w-100">

Login

</button>

</form>

<hr>

<div class="text-center">

<a href="student_register.php">
Register as Student
</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>