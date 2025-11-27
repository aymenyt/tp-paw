 <?php
require_once 'db_connect.php';

$success_message = '';
$error_message = '';

// Get open sessions
try {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM attendance_sessions WHERE status = 'open' ORDER BY id DESC");
    $open_sessions = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $open_sessions = [];
}

// Close session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['session_id'])) {
    $session_id = $_POST['session_id'];
    
    try {
        $conn = getDBConnection();
        $sql = "UPDATE attendance_sessions SET status = 'closed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$session_id]);
        
        $success_message = "Session closed successfully!";
        // Refresh open sessions
        $stmt = $conn->query("SELECT * FROM attendance_sessions WHERE status = 'open' ORDER BY id DESC");
        $open_sessions = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Close Session</title>
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
            max-width: 800px;
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
        .session-item {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .session-info {
            margin-bottom: 10px;
        }
        .session-info strong {
            color: #722f37;
        }
        button {
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #b91c1c;
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
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #718096;
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
            <a href="create_session.php" class="nav-link">🎯 Create Session</a>
            <a href="close_session.php" class="nav-link">🔒 Close Session</a>
            <a href="index.html" class="nav-link">🚀 Main System</a>
        </div>
    </div>

    <div class="container">
        <h1>Close Attendance Session</h1>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($open_sessions)): ?>
            <div class="empty-state">
                <p>No open sessions found.</p>
                <a href="create_session.php" class="nav-link" style="display: inline-block; margin-top: 10px;">Create New Session</a>
            </div>
        <?php else: ?>
            <?php foreach ($open_sessions as $session): ?>
                <div class="session-item">
                    <div class="session-info">
                        <strong>Session ID:</strong> <?php echo $session['id']; ?><br>
                        <strong>Course:</strong> <?php echo htmlspecialchars($session['course_id']); ?><br>
                        <strong>Group:</strong> <?php echo htmlspecialchars($session['group_id']); ?><br>
                        <strong>Date:</strong> <?php echo $session['date']; ?><br>
                        <strong>Opened by:</strong> <?php echo $session['opened_by']; ?>
                    </div>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                        <button type="submit" onclick="return confirm('Close this session?')">Close Session</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
?>