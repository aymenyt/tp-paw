  <?php
// Exercise 2: Take attendance using students.json

$students_file = "students.json";
$today = date("Y-m-d");
$attendance_file = "attendance_$today.json";
$message = "";

// If file for today already exists → stop
if (file_exists($attendance_file)) {
    die("Attendance for today has already been taken.");
}

// Load students
$students = file_exists($students_file)
    ? json_decode(file_get_contents($students_file), true)
    : [];
    
// If attendance submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $attendance = [];

    // Loop through each student and collect status
    foreach ($students as $stu) {
        $id = $stu["student_id"];
        $attendance[] = [
            "student_id" => $id,
            "status" => $_POST["status_$id"]
        ];
    }

    // Save attendance JSON file
    file_put_contents($attendance_file, json_encode($attendance, JSON_PRETTY_PRINT));
    $message = "Attendance saved successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Attendance</title>
</head>
<body>

<h3>Take Attendance (<?php echo $today; ?>)</h3>

<p><?php echo $message; ?></p>

<form method="POST">

<?php foreach ($students as $stu): ?>
    <p>
        <?php echo $stu["name"]; ?> —
        <label><input type="radio" name="status_<?php echo $stu["student_id"]; ?>" value="present" checked> Present</label>
        <label><input type="radio" name="status_<?php echo $stu["student_id"]; ?>" value="absent"> Absent</label>
    </p>
<?php endforeach; ?>

<button type="submit">Save Attendance</button>

</form>

</body>
</html>
