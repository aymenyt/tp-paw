 <?php
require_once 'db_connect.php';
$conn = getDBConnection();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_id = $_POST['course_id'];
    $groupe_id = $_POST['groupe_id'];
    $opened_by = $_POST['opened_by'];

    // Vérifier session déjà existante
    $check = $conn->prepare("
        SELECT * FROM attendance_sessions
        WHERE course_id = ? AND groupe_id = ? AND DATE(date) = CURDATE()
    ");
    $check->execute([$course_id, $groupe_id]);

    if ($check->rowCount() > 0) {
        $message = "<div class='msg error'>Attendance already exists for today!</div>";
    } else {
        $insert = $conn->prepare("
            INSERT INTO attendance_sessions (course_id, groupe_id, opened_by)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$course_id, $groupe_id, $opened_by]);

        $message = "<div class='msg success'>Session created successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Attendance Session</title>

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

        select, button {
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

        /* MESSAGE STYLES */
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
    <h2>Open Attendance Session</h2>

    <?php echo $message; ?>

    <form method="POST">

        <label>Course:</label>
        <select name="course_id" required>
            <option value="1">Course 1</option>
        </select>

        <label>Group:</label>
        <select name="groupe_id" required>
            <option value="3">Group 3</option>
        </select>

        <input type="hidden" name="opened_by" value="1">

        <button type="submit">Open Session</button>
    </form>
</div>

</body>
</html>
