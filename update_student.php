 <?php
require_once 'db_connect.php';

$student = null;
$success_message = '';
$error_message = '';

// Get student data
if (isset($_GET['id'])) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $student = $stmt->fetch();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $matricule = trim($_POST['matricule']);
    $group_id = trim($_POST['group_id']);
    
    try {
        $conn = getDBConnection();
        $sql = "UPDATE students SET fullname = ?, matricule = ?, group_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fullname, $matricule, $group_id, $id]);
        
        $success_message = "Student updated successfully!";
        // Refresh student data
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

if (!$student) {
    die("Student not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Student</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fdf8fa 0%, #f8f0f2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .nav-bar {
            background: #722f37;
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
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(114, 47, 55, 0.08);
            border: 1px solid #e8c8ce;
        }
        h1 {
            color: #722f37;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e8c8ce;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #2d3748;
            font-weight: 500;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #722f37;
            box-shadow: 0 0 0 3px rgba(114, 47, 55, 0.1);
        }
        button {
            background: #722f37;
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
            background: #5a252b;
            transform: translateY(-2px);
        }
        .success {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #10b981;
            margin-bottom: 20px;
            text-align: center;
        }
        .error {
            color: #dc2626;
            background: rgba(220, 38, 38, 0.1);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #dc2626;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- NAVIGATION BAR -->
    <div class="nav-bar">
        <div class="nav-container">
            <a href="menu.php" class="nav-link">🏠 Menu</a>
            <a href="add_student.php" class="nav-link">➕ Add Student (JSON)</a>
            <a href="take_attendance.php" class="nav-link">📝 Take Attendance</a>
            <a href="add_student_db.php" class="nav-link">➕ Add Student (DB)</a>
            <a href="list_students.php" class="nav-link">📋 List Students</a>
            <a href="index.html" class="nav-link">🚀 Main System</a>
        </div>
    </div>

    <div class="container">
        <h1>Update Student</h1>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="matricule">Matricule:</label>
                <input type="text" id="matricule" name="matricule" value="<?php echo htmlspecialchars($student['matricule']); ?>" required>
            </div>
            <div class="form-group">
                <label for="group_id">Group ID:</label>
                <input type="text" id="group_id" name="group_id" value="<?php echo htmlspecialchars($student['group_id']); ?>" required>
            </div>
            <button type="submit">Update Student</button>
        </form>
    </div>
</body>
</html>