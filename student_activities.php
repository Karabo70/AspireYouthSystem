<?php
include 'config/db.php';

$studentID = 1;

$student = mysqli_query($conn,"SELECT * FROM Students WHERE StudentID=$studentID");
$studentData = mysqli_fetch_assoc($student);
?>

<!DOCTYPE html>
<html>
<head>

<title>My Activities</title>

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
<a class="nav-link active" href="student_activities.php">My Activities</a>
<a class="nav-link" href="student_notifications.php">Notifications</a>
<a class="nav-link text-warning" href="student_login.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>My Activities</h3>

</div>

<div class="card-body">

<ul class="list-group">

<li class="list-group-item">
✅ Registration Submitted
</li>

<li class="list-group-item">

<?php

if($studentData['Status']=="Approved"){
echo "✅ Registration Approved";
}else{
echo "⏳ Waiting for Administrator Approval";
}

?>

</li>

<li class="list-group-item">
📚 Student Account Created
</li>

<li class="list-group-item">
📅 View Your Scheduled Sessions
</li>

<li class="list-group-item">
📊 View Your Attendance Records
</li>

</ul>

</div>

</div>

</div>

</body>
</html>