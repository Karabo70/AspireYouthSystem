<?php
include 'config/db.php';

if(isset($_POST['submit'])){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
   $password = $_POST['password'] ?? '';

    $check = mysqli_query($conn,
    "SELECT * FROM Students WHERE Email='$email'");

    if(mysqli_num_rows($check) > 0){

        echo "<script>alert('Email already registered');</script>";

    }else{

        mysqli_query($conn,
        "INSERT INTO Students
        (FirstName, LastName, Email, PhoneNumber, Password, Status)
        VALUES
        ('$firstname','$lastname','$email','$phone','$password','Pending')");

        echo "<script>alert('Registration submitted successfully. Await admin approval.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Student Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<div class="text-center mb-4">

<img src="assets/images/logo.png"
     alt="Aspire Youth Logo"
     style="width:300px; max-width:100%; height:auto;">

<h3 class="mt-3">
Student Registration
</h3>

<p class="text-muted">
Aspire Youth Initiative
</p>

</div>

<form method="POST">

<input
type="text"
name="firstname"
class="form-control mb-3"
placeholder="First Name"
required>

<input
type="text"
name="lastname"
class="form-control mb-3"
placeholder="Last Name"
required>

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="Email Address"
required>

<input
type="text"
name="phone"
class="form-control mb-3"
placeholder="Phone Number"
required>

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="Create Password"
required>

<button
type="submit"
name="submit"
class="btn btn-primary w-100">

Register

</button>

</form>

<hr>

<div class="text-center">

Already registered?

<a href="student_login.php">
Login Here
</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>