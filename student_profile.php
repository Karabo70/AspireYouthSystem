<?php
include 'config/db.php';

$studentID = 1; // Change this later to use the logged-in student's ID

$student = mysqli_query($conn, "SELECT * FROM Students WHERE StudentID=$studentID");
$studentData = mysqli_fetch_assoc($student);

if(!$studentData){
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

<div class="container-fluid">

<a class="navbar-brand" href="#">Aspire Youth Initiative</a>

<div class="navbar-nav ms-auto">

<a class="nav-link" href="student_dashboard.php">Dashboard</a>
<a class="nav-link active" href="student_profile.php">My Profile</a>
<a class="nav-link" href="student_sessions.php">My Sessions</a>
<a class="nav-link" href="student_attendance.php">Attendance</a>
<a class="nav-link" href="student_activities.php">My Activities</a>
<a class="nav-link" href="student_notifications.php">Notifications</a>
<a class="nav-link text-warning" href="student_login.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>My Profile</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="30%">Student ID</th>
<td><?php echo $studentData['StudentID']; ?></td>
</tr>

<tr>
<th>First Name</th>
<td><?php echo $studentData['FirstName']; ?></td>
</tr>

<tr>
<th>Last Name</th>
<td><?php echo $studentData['LastName']; ?></td>
</tr>

<tr>
<th>Email</th>
<td><?php echo $studentData['Email']; ?></td>
</tr>

<tr>
<th>Phone Number</th>
<td><?php echo $studentData['PhoneNumber']; ?></td>
</tr>

<tr>
<th>Registration Status</th>
<td>
<?php
if($studentData['Status']=="Approved"){
    echo "<span class='badge bg-success'>Approved</span>";
}else{
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}
?>
</td>
</tr>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>