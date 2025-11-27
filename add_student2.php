 <?php
// add_student.php - Exercise 1: JSON version
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $group = trim($_POST['group']);
    
    // Validation
    $errors = [];
    if (empty($student_id)) $errors[] = "Student ID is required";
    if (empty($name)) $errors[] = "Name is required";
    if (empty($group)) $errors[] = "Group is required";
    
    if (empty($errors)) {
        $students = [];
        if (file_exists('students.json')) {
            $json_data = file_get_contents('students.json');
            $students = json_decode($json_data, true) ?: [];
        }
        $students[] = [
            'student_id' => $student_id,
            'name' => $name,
            'group' => $group
        ];
        file_put_contents('students.json', json_encode($students, JSON_PRETTY_PRINT));
        $success_message = "Student added successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f2ff 0%, #f0f9ff 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .nav-bar {
            background: #1e40af; /* dark blue */
            padding: 15px 0;
            margin-bottom: 20px;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 0 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #e0f2ff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(30,64,175,0.2);
            border: 1px solid #93c5fd;
        }
        h1 {
            color: #1e40af;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #93c5fd;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #1e3a8a;
            font-weight: 500;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #93c5fd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30,64,175,0.1);
        }
        button {
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        button:hover {
            background: #1e3a8a;
            transform: translateY(-2px);
        }
        .success {
            color: #065f46;
            background: rgba(6,95,70,0.1);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #065f46;
            margin-bottom: 20px;
            text-align: center;
        }
        .error {
            color: #b91c1c;
            background: rgba(185,28,28,0.1);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #b91c1c;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <div class="nav-container">
            <a href="add_student.php" class="nav-link">➕ Add Student (JSON)</a>
            <a href="take_attendance.php" class="nav-link">📝 Take Attendance</a>
            <a href="add_student2.php" class="nav-link">➕ Add Student (DB)</a>
            <a href="list_students.php" class="nav-link">📋 List Students</a>
            <a href="1.html" class="nav-link">🚀 Main System</a>
        </div>
    </div>

    <div class="container">
        <h1>Add Student</h1>
        <?php if (isset($success_message)): ?>
            <div class="success"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="group">Group:</label>
                <input type="text" id="group" name="group" required>
            </div>
            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>
