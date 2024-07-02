<?php
session_start();
require 'db/db.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit;
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$studentsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $studentsPerPage;

// Query to get total students (with search)
$totalStudentsQuery = $pdo->prepare("SELECT COUNT(*) FROM students WHERE name LIKE :search");
$totalStudentsQuery->execute(['search' => "%$search%"]);
$totalStudents = $totalStudentsQuery->fetchColumn();

$totalPages = ceil($totalStudents / $studentsPerPage);

// Query to get students (with search and sorting)
$stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE :search ORDER BY name ASC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $studentsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $name = $_POST['name'];
            $subject = $_POST['subject'];
            $mark = $_POST['mark'];
            
            $stmt = $pdo->prepare("INSERT INTO students (name, subject, marks) VALUES (?, ?, ?)");
            $stmt->execute([$name, $subject, $mark]);
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $subject = $_POST['subject'];
            $mark = $_POST['mark'];
            
            $stmt = $pdo->prepare("UPDATE students SET name = ?, subject = ?, marks = ? WHERE id = ?");
            $stmt->execute([$name, $subject, $mark, $id]);
        }
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #fff;
        }

        .logo {
            color: #ff6b6b;
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            margin-left: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #f0f0f0;
            padding: 30px;
            border-radius: 8px;
        }

        .table-container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f8f8f8;
            font-weight: normal;
            color: #888;
        }

        .profile-pic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #3498db;
            color: #fff;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .action-btn {
            background-color: #f0f0f0;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .action-btn::after {
            content: '';
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #333;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 120px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .add-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {background-color: #ddd;}

        .modal-title {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">tailwebs.</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Mark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <div class="profile-pic">
                                    <?= strtoupper(substr($student['name'], 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($student['name']) ?>
                            </td>
                            <td><?= htmlspecialchars($student['subject']) ?></td>
                            <td><?= htmlspecialchars($student['marks']) ?></td>
                            <td>
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="action-btn"></button>
                                    <div class="dropdown-content">
                                        <a href="#" onclick="openEditModal(<?= $student['id'] ?>, '<?= htmlspecialchars($student['name']) ?>', '<?= htmlspecialchars($student['subject']) ?>', <?= $student['marks'] ?>)">Edit</a>
                                        <a href="delete_student.php?id=<?= $student['id'] ?>">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <a href="#" class="add-btn" onclick="openAddModal()">Add</a>
        
        <div id="studentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 id="modalTitle" class="modal-title">Add Student</h2>
                <form id="studentForm" method="POST">
                    <input type="hidden" id="action" name="action" value="add">
                    <input type="hidden" id="studentId" name="id" value="">
                    <div class="form-group">
                        <label for="name">👤 Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">📚 Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="mark">🏆 Mark</label>
                        <input type="number" id="mark" name="mark" required>
                    </div>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('click', function(e) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        if (!e.target.matches('.action-btn')) {
            for (var d of dropdowns) {
                if (d.style.display === "block") {
                    d.style.display = "none";
                }
            }
        } else {
            var content = e.target.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                for (var d of dropdowns) {
                    d.style.display = "none";
                }
                content.style.display = "block";
            }
            e.stopPropagation();
        }
    });

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Add Student';
        document.getElementById('action').value = 'add';
        document.getElementById('studentId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('subject').value = '';
        document.getElementById('mark').value = '';
        document.getElementById('studentModal').style.display = 'block';
    }

    function openEditModal(id, name, subject, mark) {
        document.getElementById('modalTitle').textContent = 'Edit Student';
        document.getElementById('action').value = 'edit';
        document.getElementById('studentId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('subject').value = subject;
        document.getElementById('mark').value = mark;
        document.getElementById('studentModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('studentModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('studentModal')) {
            closeModal();
        }
    }
    </script>
</body>
</html>