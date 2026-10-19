<?php
include 'config/db.php';

$studentID = 1;

$student = mysqli_query($conn,"SELECT * FROM Students WHERE StudentID=$studentID");
$studentData = mysqli_fetch_assoc($student);
?>

<!DOCTYPE html>
<html>
<head>

<title>Notifications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

<div class="container-fluid">

<a class="navbar-brand" href="#">Aspire Youth Initiative</a>

<div class="navbar-nav ms-auto">

<a class="nav-link" href="student_dashboard.php">Dashboard</a>
<a class="nav-link" href="student_profile.php">My Profile</a>
<a class="nav-link" href="student_sessions.php">My Sessions</a>
<a class="nav-link" href="student_attendance.php">Attendance</a>
<a class="nav-link" href="student_activities.php">My Activities</a>
<a class="nav-link active" href="student_notifications.php">Notifications</a>
<a class="nav-link text-warning" href="student_login.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Notifications</h3>

</div>

<div class="card-body">

<?php

if($studentData['Status']=="Approved"){

echo '

<div class="alert alert-success">

<strong>Congratulations!</strong><br>

Your registration has been approved by the administrator.

</div>

';

}else{

echo '

<div class="alert alert-warning">

<strong>Pending Approval</strong><br>

Your registration is awaiting administrator approval.

</div>

';

}

?>

<div class="alert alert-info">

Your tutor and session notifications will appear here once sessions have been scheduled.

</div>

<div class="alert alert-primary">

Attendance updates will also be displayed here.

</div>

</div>

</div>

</div>

</body>
</html>