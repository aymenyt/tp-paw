 <?php
$message = "";

// Traitement du formulaire si POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $name       = trim($_POST['name'] ?? '');
    $group      = trim($_POST['group'] ?? '');

    if ($student_id === '' || $name === '' || $group === '') {
        $message = "<div class='msg error'>All fields are required!</div>";
    } else {
        $filename = "students.json";
        $students = [];

        if (file_exists($filename)) {
            $json = file_get_contents($filename);
            $students = json_decode($json, true);
            if (!is_array($students)) $students = [];
        }

        $students[] = [
            "student_id" => $student_id,
            "name"       => $name,
            "group"      => $group
        ];

        file_put_contents($filename, json_encode($students, JSON_PRETTY_PRINT));
        $message = "<div class='msg success'>Student added successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #e8f1ff; /* blue light */
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-container {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        width: 380px;
        text-align: center;
    }

    h2 {
        color: #1e3a8a;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #1e3a8a;
        display: block;
        margin-bottom: 6px;
        text-align: left;
    }

    input, button {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
    }

    button {
        background: #2563eb;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        opacity: 0.9;
    }

    .msg {
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #34d399;
    }

    .error {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #f87171;
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>Add Student</h2>

    <?php echo $message; ?>

    <form method="POST">
        <label>Student ID:</label>
        <input type="text" name="student_id" required>

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Group:</label>
        <input type="text" name="group" required>

        <button type="submit">Add Student</button>
    </form>
</div>

</body>
</html>
