<?php
include 'config/db.php';

$studentID = 1; // Change to an existing StudentID

$student = mysqli_query($conn,
"SELECT * FROM Students WHERE StudentID=$studentID");

$studentData = mysqli_fetch_assoc($student);

$sessions = mysqli_query($conn,"
SELECT
Sessions.*,
Tutors.FirstName AS TutorName,
Tutors.LastName AS TutorLastName
FROM Sessions
INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
WHERE Sessions.StudentID = $studentID
");

$present = mysqli_num_rows(mysqli_query($conn,"
SELECT AttendanceID
FROM Attendance
INNER JOIN Sessions
ON Attendance.SessionID = Sessions.SessionID
WHERE Sessions.StudentID = $studentID
AND Attendance.Status='Present'
"));

$absent = mysqli_num_rows(mysqli_query($conn,"
SELECT AttendanceID
FROM Attendance
INNER JOIN Sessions
ON Attendance.SessionID = Sessions.SessionID
WHERE Sessions.StudentID = $studentID
AND Attendance.Status='Absent'
"));

$attendance = mysqli_query($conn,"
SELECT Attendance.*
FROM Attendance
INNER JOIN Sessions
ON Attendance.SessionID = Sessions.SessionID
WHERE Sessions.StudentID = $studentID
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

<div class="container-fluid">

<a class="navbar-brand" href="#">Aspire Youth Initiative</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link active" href="student_dashboard.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="student_profile.php">My Profile</a>
</li>

<li class="nav-item">
<a class="nav-link" href="student_sessions.php">My Sessions</a>
</li>

<li class="nav-item">
<a class="nav-link" href="student_attendance.php">Attendance</a>
</li>

<li class="nav-item">
<a class="nav-link" href="student_activities.php">My Activities</a>
</li>

<li class="nav-item">
<a class="nav-link" href="student_notifications.php">Notifications</a>
</li>

<li class="nav-item">
<a class="nav-link text-warning" href="student_login.php">Logout</a>
</li>

</ul>

</div>

</div>

</nav>

<div class="container mt-4">

<h2 class="mb-4">Welcome to Your Student Portal</h2>

<!-- Student Information -->

<div class="card shadow mb-4">

<div class="card-body">

<h4>Student Information</h4>

<p>
<strong>Name:</strong>
<?php echo $studentData['FirstName']." ".$studentData['LastName']; ?>
</p>

<p>
<strong>Email:</strong>
<?php echo $studentData['Email']; ?>
</p>

<p>
<strong>Phone:</strong>
<?php echo $studentData['PhoneNumber']; ?>
</p>

<p>
<strong>Status:</strong>

<?php
if($studentData['Status'] == 'Approved'){
    echo '<span class="badge bg-success">Approved</span>';
}
else{
    echo '<span class="badge bg-warning text-dark">Pending</span>';
}
?>
</p>

</div>

</div>

<!-- Activities -->

<div class="card shadow mb-4">

<div class="card-body">

<h4>My Activities</h4>

<ul class="list-group">

<li class="list-group-item">
✅ Registration Submitted
</li>

<li class="list-group-item">

<?php
if($studentData['Status'] == 'Approved'){
    echo "✅ Registration Approved";
}
else{
    echo "⏳ Awaiting Approval";
}
?>

</li>

<li class="list-group-item">
📚 Student Account Active
</li>

<li class="list-group-item">
📅 View Scheduled Sessions Below
</li>

<li class="list-group-item">
📊 View Attendance History Below
</li>

</ul>

</div>

</div>

<!-- Sessions -->

<div class="card shadow mb-4">

<div class="card-body">

<h4>My Sessions</h4>

<table class="table table-bordered">

<tr>
<th>Tutor</th>
<th>Date</th>
<th>Time</th>
<th>Venue</th>
</tr>

<?php while($row=mysqli_fetch_assoc($sessions)){ ?>

<tr>

<td>
<?php echo $row['TutorName']." ".$row['TutorLastName']; ?>
</td>

<td>
<?php echo $row['SessionDate']; ?>
</td>

<td>
<?php echo $row['SessionTime']; ?>
</td>

<td>
<?php echo $row['Venue']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<!-- Attendance Summary -->

<div class="row mb-4">

<div class="col-md-6">

<div class="card text-bg-success">

<div class="card-body">

<h5>Present</h5>

<h2><?php echo $present; ?></h2>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card text-bg-danger">

<div class="card-body">

<h5>Absent</h5>

<h2><?php echo $absent; ?></h2>

</div>

</div>

</div>

</div>

<!-- Attendance History -->

<div class="card shadow">

<div class="card-body">

<h4>Attendance History</h4>

<table class="table table-bordered">

<tr>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row=mysqli_fetch_assoc($attendance)){ ?>

<tr>

<td>
<?php echo $row['Status']; ?>
</td>

<td>
<?php echo $row['AttendanceDate']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>