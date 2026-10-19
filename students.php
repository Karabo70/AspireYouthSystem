<?php
include 'config/db.php';
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM Students WHERE StudentID=$id");
}

if(isset($_GET['approve'])){

    $id = $_GET['approve'];

    mysqli_query($conn,
    "UPDATE Students SET Status='Approved'
     WHERE StudentID=$id");
}

if(isset($_POST['save'])){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    mysqli_query($conn,
    "INSERT INTO Students
    (FirstName, LastName, Email, PhoneNumber)
    VALUES
    ('$firstname','$lastname','$email','$phone')");
}

$result = mysqli_query($conn,
"SELECT * FROM Students");
?>

<!DOCTYPE html>
<html>
<head>

<title>Students</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
<h2>Students</h2>

<form method="POST">

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

<input type="email"
name="email"
class="form-control mb-2"
placeholder="Email"
required>

<input type="text"
name="phone"
class="form-control mb-2"
placeholder="Phone Number"
required>

<button
name="save"
class="btn btn-primary">
Add Student
</button>

</form>

<hr>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Email</th>
<th>Phone</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
?>

<tr>
<td><?php echo $row['StudentID']; ?></td>
<td><?php echo $row['FirstName']; ?></td>
<td><?php echo $row['LastName']; ?></td>
<td><?php echo $row['Email']; ?></td>
<td><?php echo $row['PhoneNumber']; ?></td>
<td><?php echo $row['Status']; ?></td>

<td>

<a href="students.php?approve=<?php echo $row['StudentID']; ?>"
class="btn btn-success btn-sm"
onclick="return confirm('Approve this student?')">

Approve
</a>

<a href="students.php?delete=<?php echo $row['StudentID']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">

Delete
</a>

</td>
</tr>

<?php

}
?>

</table>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>