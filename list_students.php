<?php 
require_once 'db_connect.php';

try {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $students = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>List Students</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #e8f1ff; /* light blue */
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .nav-bar {
            background: #1e3a8a; /* dark blue */
            padding: 15px 0;
            margin-bottom: 20px;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            padding: 0 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            font-size: 14px;
            transition: .3s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.4); }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        h1 {
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 10px;
        }
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }
        .students-table th {
            background: #dbeafe; /* soft blue */
            color: #1e3a8a;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1;
        }
        .students-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .students-table tr:hover { background: #f0f7ff; }
        .action-buttons { display: flex; gap: 5px; }
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
            color: white;
        }
        .btn-edit { background: #2563eb; } /* blue */
        .btn-delete { background: #dc2626; } /* red */
        .btn:hover { opacity: .9; }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }
        .error {
            color: #dc2626;
            background: #ffe5e5;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #dc2626;
            margin-bottom: 20px;
            text-align: center;
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
        <h1>Students List</h1>

        <?php if (isset($error_message)): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if (empty($students)): ?>
            <div class="empty-state">
                <p>No students found in database.</p>
                <a href="add_student2.php" class="nav-link">Add First Student</a>
            </div>
        <?php else: ?>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Matricule</th>
                        <th>Group</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td><?= htmlspecialchars($s['fullname']) ?></td>
                            <td><?= htmlspecialchars($s['matricule']) ?></td>
                            <td><?= htmlspecialchars($s['group_id']) ?></td>
                            <td><?= $s['created_at'] ?></td>
                            <td class="action-buttons">
                                <a class="btn btn-edit" href="update_student.php?id=<?= $s['id'] ?>">Edit</a>
                                <a class="btn btn-delete" href="delete_student.php?id=<?= $s['id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

