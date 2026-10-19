<?php
include 'config/db.php';

session_start();

if(!isset($_SESSION['TutorID'])){
    header("Location: tutor_login.php");
    exit();
}

$tutorID = $_SESSION['TutorID'];

$tutor = mysqli_query($conn,"
SELECT *
FROM Tutors
WHERE TutorID=$tutorID
");

$tutorData=mysqli_fetch_assoc($tutor);
?>

<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include 'tutor_navbar.php'; ?>

<div class="container mt-4">

<h2>My Profile</h2>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Tutor Information

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="30%">Tutor ID</th>

<td><?php echo $tutorData['TutorID']; ?></td>

</tr>

<tr>

<th>First Name</th>

<td><?php echo $tutorData['FirstName']; ?></td>

</tr>

<tr>

<th>Last Name</th>

<td><?php echo $tutorData['LastName']; ?></td>

</tr>

<tr>

<th>Subject</th>

<td><?php echo $tutorData['Subject']; ?></td>

</tr>

<tr>

<th>Email</th>

<td><?php echo $tutorData['Email']; ?></td>

</tr>

<tr>

<th>Phone Number</th>

<td><?php echo $tutorData['PhoneNumber']; ?></td>

</tr>

</table>

</div>

</div>

</div>

</body>

</html>