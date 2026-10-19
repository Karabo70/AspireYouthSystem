<?php
include 'config/db.php';

$studentID = 1; // Later we'll replace this with the logged-in student's ID

$sessions = mysqli_query($conn,"
SELECT
    Sessions.*,
    Tutors.FirstName,
    Tutors.LastName,
    Tutors.Subject
FROM Sessions
INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
WHERE Sessions.StudentID = $studentID
");
?>

<!DOCTYPE html>
<html>
<head>

<title>My Sessions</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

<div class="container-fluid">

<a class="navbar-brand" href="#">Aspire Youth Initiative</a>

<div class="navbar-nav ms-auto">

<a class="nav-link" href="student_dashboard.php">Dashboard</a>
<a class="nav-link" href="student_profile.php">My Profile</a>
<a class="nav-link active" href="student_sessions.php">My Sessions</a>
<a class="nav-link" href="student_attendance.php">Attendance</a>
<a class="nav-link" href="student_activities.php">My Activities</a>
<a class="nav-link" href="student_notifications.php">Notifications</a>
<a class="nav-link text-warning" href="student_login.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">

<h2>My Tutoring Sessions</h2>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<tr class="table-primary">

<th>Session ID</th>
<th>Tutor</th>
<th>Subject</th>
<th>Date</th>
<th>Time</th>
<th>Venue</th>

</tr>

<?php
while($row = mysqli_fetch_assoc($sessions)){
?>

<tr>

<td><?php echo $row['SessionID']; ?></td>

<td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>

<td><?php echo $row['Subject']; ?></td>

<td><?php echo $row['SessionDate']; ?></td>

<td><?php echo $row['SessionTime']; ?></td>

<td><?php echo $row['Venue']; ?></td>

</tr>

<?php
}
?>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>