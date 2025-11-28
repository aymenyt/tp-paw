 <?php
// Exercise 2: Take attendance using students.json

$students_file = "students.json";
$today = date("Y-m-d");
$attendance_file = "attendance_$today.json";
$message = "";

// If file for today already exists → stop
if (file_exists($attendance_file)) {
    die("<div style='
        background:#ffecec;
        color:#b30000;
        padding:20px;
        margin:20px auto;
        text-align:center;
        border-radius:10px;
        width:60%;
        font-family:Arial;'> 
        Attendance for today has already been taken.
    </div>");
}

// Load students
$students = file_exists($students_file)
    ? json_decode(file_get_contents($students_file), true)
    : [];

// If attendance submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $attendance = [];

    foreach ($students as $stu) {
        $id = $stu["student_id"];
        $attendance[] = [
            "student_id" => $id,
            "status" => $_POST["status_$id"]
        ];
    }

    file_put_contents($attendance_file, json_encode($attendance, JSON_PRETTY_PRINT));
    $message = "Attendance saved successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Attendance</title>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #e9f4ff;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 70, 140, 0.15);
        border: 1px solid #c9e3ff;
    }

    h3 {
        text-align: center;
        color: #0056a3;
        margin-bottom: 20px;
    }

    .student-item {
        padding: 15px;
        background: #f0f7ff;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #d0e5ff;
    }

    label {
        margin-right: 10px;
        font-size: 15px;
        color: #003d73;
    }

    button {
        background: #007bff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
        transition: 0.3s;
    }

    button:hover {
        background: #005fcc;
    }

    .message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #c3e6cb;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

</head>
<body>

<div class="container">

<h3>Take Attendance (<?php echo $today; ?>)</h3>

<?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">

<?php foreach ($students as $stu): ?>
    <div class="student-item">
        <strong><?php echo $stu["name"]; ?></strong>
        <br>
        <label><input type="radio" name="status_<?php echo $stu["student_id"]; ?>" value="present" checked> Present</label>
        <label><input type="radio" name="status_<?php echo $stu["student_id"]; ?>" value="absent"> Absent</label>
    </div>
<?php endforeach; ?>

<button type="submit">Save Attendance</button>

</form>

</div>

</body>
</html>
