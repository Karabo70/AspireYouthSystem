<?php
include 'config/db.php';

if(isset($_POST['save'])){

    $student = $_POST['student'];
    $tutor = $_POST['tutor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];

    mysqli_query($conn,
    "INSERT INTO Sessions
    (StudentID, TutorID, SessionDate, SessionTime, Venue)
    VALUES
    ('$student','$tutor','$date','$time','$venue')");
}

$students = mysqli_query($conn,"SELECT * FROM Students");
$tutors = mysqli_query($conn,"SELECT * FROM Tutors");

$sessions = mysqli_query($conn,"
SELECT
    Sessions.*,
    Students.FirstName AS StudentName,
    Tutors.FirstName AS TutorName
FROM Sessions
INNER JOIN Students
ON Sessions.StudentID = Students.StudentID
INNER JOIN Tutors
ON Sessions.TutorID = Tutors.TutorID
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Sessions</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
<h2>Session Management</h2>

<a href="dashboard.php" class="btn btn-secondary mb-3">
Back to Dashboard
</a>

<form method="POST">

<label>Student</label>
<select name="student" class="form-control mb-2" required>

<option value="">Select Student</option>

<?php while($row=mysqli_fetch_assoc($students)){ ?>

<option value="<?php echo $row['StudentID']; ?>">
<?php echo $row['FirstName']." ".$row['LastName']; ?>
</option>

<?php } ?>

</select>

<label>Tutor</label>
<select name="tutor" class="form-control mb-2" required>

<option value="">Select Tutor</option>

<?php while($row=mysqli_fetch_assoc($tutors)){ ?>

<option value="<?php echo $row['TutorID']; ?>">
<?php echo $row['FirstName']." ".$row['LastName']; ?>
</option>

<?php } ?>

</select>

<input
type="date"
name="date"
class="form-control mb-2"
required>

<input
type="time"
name="time"
class="form-control mb-2"
required>

<input
type="text"
name="venue"
class="form-control mb-2"
placeholder="Venue"
required>

<button
name="save"
class="btn btn-warning">
Schedule Session
</button>

</form>

<hr>

<h3>Scheduled Sessions</h3>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Student</th>
<th>Tutor</th>
<th>Date</th>
<th>Time</th>
<th>Venue</th>
</tr>

<?php while($row=mysqli_fetch_assoc($sessions)){ ?>

<tr>

<td><?php echo $row['SessionID']; ?></td>

<td><?php echo $row['StudentName']; ?></td>

<td><?php echo $row['TutorName']; ?></td>

<td><?php echo $row['SessionDate']; ?></td>

<td><?php echo $row['SessionTime']; ?></td>

<td><?php echo $row['Venue']; ?></td>

</tr>

<?php } ?>

</table>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
