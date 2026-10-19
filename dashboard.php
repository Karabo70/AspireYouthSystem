<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">

<h2>Dashboard</h2>

<div class="row">

<div class="col-md-4">
<div class="card shadow">
<div class="card-body">

<h4>Students</h4>

<a href="students.php"
class="btn btn-primary">
Manage Students
</a>

</div>
</div>
</div>

<div class="col-md-4">
<div class="card shadow">
<div class="card-body">

<h4>Tutors</h4>

<a href="tutors.php"
class="btn btn-success">
Manage Tutors
</a>

</div>
</div>
</div>

<div class="col-md-4">
<div class="card shadow">
<div class="card-body">

<h4>Sessions</h4>

<a href="sessions.php"
class="btn btn-warning">
Manage Sessions
</a>

</div>
</div>
</div>
<div class="col-md-4">
<div class="card shadow">
<div class="card-body">

<h4>Attendance</h4>

<a href="attendance.php"
class="btn btn-info">
Manage Attendance
</a>

</div>
</div>
</div>
<div class="col-md-4">
<div class="card shadow">

<div class="card-body">

<h4>Reports</h4>

<a href="reports.php"
class="btn btn-dark">
View Reports
</a>

</div>

</div>
</div>


</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
