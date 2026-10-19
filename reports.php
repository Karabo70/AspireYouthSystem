<?php
include 'config/db.php';

$students =
mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM Students")
);

$tutors =
mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM Tutors")
);

$sessions =
mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM Sessions")
);

$attendance =
mysqli_query($conn,"
SELECT
Attendance.*,
Students.FirstName AS StudentName,
Tutors.FirstName AS TutorName

FROM Attendance

INNER JOIN Sessions
ON Attendance.SessionID = Sessions.SessionID

INNER JOIN Students
ON Sessions.StudentID = Students.StudentID

INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
");
?>

<!DOCTYPE html>
<html>

<head>

<title>Reports</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">

<h2>Aspire Youth Initiative Report</h2>

<a href="dashboard.php"
class="btn btn-secondary mb-3">
Back to Dashboard
</a>

<button
onclick="window.print()"
class="btn btn-primary mb-3">
Print Report
</button>

<div class="row">

<div class="col-md-4">
<div class="card text-bg-primary mb-3">

<div class="card-body">

<h4>Total Students</h4>

<h1><?php echo $students; ?></h1>

</div>

</div>
</div>

<div class="col-md-4">
<div class="card text-bg-success mb-3">

<div class="card-body">

<h4>Total Tutors</h4>

<h1><?php echo $tutors; ?></h1>

</div>

</div>
</div>

<div class="col-md-4">
<div class="card text-bg-warning mb-3">

<div class="card-body">

<h4>Total Sessions</h4>

<h1><?php echo $sessions; ?></h1>

</div>

</div>
</div>

</div>

<hr>

<h3>Attendance Report</h3>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Student</th>
<th>Tutor</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row=mysqli_fetch_assoc($attendance)){ ?>

<tr>

<td><?php echo $row['AttendanceID']; ?></td>

<td><?php echo $row['StudentName']; ?></td>

<td><?php echo $row['TutorName']; ?></td>

<td><?php echo $row['Status']; ?></td>

<td><?php echo $row['AttendanceDate']; ?></td>

</tr>

<?php } ?>

</table>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>