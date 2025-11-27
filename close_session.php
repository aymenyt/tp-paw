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
            font-family: Arial, sans-serif;
            background: #e8f1ff;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #1f4fa3;
            margin-bottom: 20px;
        }

        .success {
            background: #c8ffd4;
            color: #067a1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 6px solid #06b63d;
        }

        .error {
            background: #ffd4d4;
            color: #a30000;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 6px solid #ff3d3d;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #555;
        }

        .session-item {
            background: #f0f6ff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 5px solid #1f4fa3;
        }

        button {
            background: #1f4fa3;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.2s;
        }

        button:hover {
            background: #183f82;
        }

        .nav-link {
            color: #1f4fa3;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-link:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

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
            <a href="create_session.php" class="nav-link">Create New Session</a>
        </div>
    <?php else: ?>
        <?php foreach ($open_sessions as $session): ?>
            <div class="session-item">
                <div class="session-info">
                    <strong>Session ID:</strong> <?php echo $session['id']; ?><br>
                    <strong>Course:</strong> <?php echo htmlspecialchars($session['course_id']); ?><br>
                    <strong>Group:</strong> <?php echo htmlspecialchars($session['groupe_id']); ?><br>
                    <strong>Date:</strong> <?php echo $session['date']; ?><br>
                    <strong>Opened by:</strong> <?php echo $session['opened_by']; ?>
                </div>
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                    <button type="submit" onclick="return confirm('Close this session?')">Close Session</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
