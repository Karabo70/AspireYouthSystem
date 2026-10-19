<?php
include 'config/db.php';

if(isset($_POST['save'])){

    $sessionID = $_POST['sessionID'];
    $status = $_POST['status'];
    $date = date("Y-m-d");

    mysqli_query($conn,
    "INSERT INTO Attendance
    (SessionID, Status, AttendanceDate)
    VALUES
    ('$sessionID','$status','$date')");
}

$sessions = mysqli_query($conn,"
SELECT
    Sessions.SessionID,
    Students.FirstName AS StudentName,
    Tutors.FirstName AS TutorName,
    Sessions.SessionDate
FROM Sessions
INNER JOIN Students
ON Sessions.StudentID = Students.StudentID
INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
");

$attendance = mysqli_query($conn,"
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

<title>Attendance</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">

<h2>Attendance Management</h2>

<a href="dashboard.php"
class="btn btn-secondary mb-3">
Back to Dashboard
</a>

<form method="POST">

<label>Select Session</label>

<select
name="sessionID"
class="form-control mb-2"
required>

<option value="">
Choose Session
</option>

<?php while($row=mysqli_fetch_assoc($sessions)){ ?>

<option value="<?php echo $row['SessionID']; ?>">

<?php
echo $row['StudentName'];
echo " - ";
echo $row['TutorName'];
echo " - ";
echo $row['SessionDate'];
?>

</option>

<?php } ?>

</select>

<label>Status</label>

<select
name="status"
class="form-control mb-3"
required>

<option value="Present">
Present
</option>

<option value="Absent">
Absent
</option>

</select>

<button
name="save"
class="btn btn-success">

Save Attendance

</button>

</form>

<hr>

<h3>Attendance Records</h3>

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