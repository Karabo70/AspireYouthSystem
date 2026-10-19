<?php
include 'config/db.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users
            WHERE Username='$username'
            AND Password='$password'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){
        header("Location: dashboard.php");
    }
    else{
        $error = "Invalid Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Aspire Youth Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card mx-auto shadow"
style="max-width:400px">

<div class="card-body">

<h3 class="text-center">
Aspire Youth Initiative
</h3>

<form method="POST">

<input type="text"
name="username"
class="form-control mb-3"
placeholder="Username">

<input type="password"
name="password"
class="form-control mb-3"
placeholder="Password">

<button
name="login"
class="btn btn-primary w-100">
Login
</button>

</form>

</div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>