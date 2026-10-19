<?php
include 'config/db.php';

$studentID = 1; // Later we'll replace this with the logged-in student's ID

$attendance = mysqli_query($conn,"
SELECT
    Attendance.*,
    Sessions.SessionDate,
    Sessions.SessionTime,
    Tutors.FirstName,
    Tutors.LastName
FROM Attendance
INNER JOIN Sessions
ON Attendance.SessionID = Sessions.SessionID
INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
WHERE Sessions.StudentID = $studentID
");
?>

<!DOCTYPE html>
<html>
<head>

<title>My Attendance</title>

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
<a class="nav-link active" href="student_attendance.php">Attendance</a>
<a class="nav-link" href="student_activities.php">My Activities</a>
<a class="nav-link" href="student_notifications.php">Notifications</a>
<a class="nav-link text-warning" href="student_login.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">

<h2>My Attendance</h2>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<tr class="table-primary">

<th>Attendance ID</th>
<th>Tutor</th>
<th>Session Date</th>
<th>Session Time</th>
<th>Status</th>
<th>Attendance Date</th>

</tr>

<?php while($row = mysqli_fetch_assoc($attendance)){ ?>

<tr>

<td><?php echo $row['AttendanceID']; ?></td>

<td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>

<td><?php echo $row['SessionDate']; ?></td>

<td><?php echo $row['SessionTime']; ?></td>

<td>

<?php
if($row['Status'] == "Present"){
    echo "<span class='badge bg-success'>Present</span>";
}else{
    echo "<span class='badge bg-danger'>Absent</span>";
}
?>

</td>

<td><?php echo $row['AttendanceDate']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>