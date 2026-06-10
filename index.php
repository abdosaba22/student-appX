<?php
// قراءة بيانات قاعدة البيانات ديناميكياً من الـ Environment Variables (أفضل أمنياً)
$host = getenv('DB_HOST') ?: '172.17.0.1'; 
$db   = getenv('DB_NAME') ?: 'applicationDB';
$user = getenv('DB_USER') ?: 'app_user';
$pass = getenv('DB_PASSWORD') ?: 'YourStrongPassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("<div class='alert alert-danger m-3'>Database Connection Failed: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// استعلام الـ LEFT JOIN لعرض الطلاب وهواتفهم كما في مشروعك
$sql = "SELECT s.student_id, s.name, s.age, p.phone_number 
        FROM students s 
        LEFT JOIN st_phones p ON s.student_id = p.student_id";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - App 1 Node</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 10px; }
        .table th { background-color: #e9ecef; color: #495057; }
        .app-indicator { font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fa-solid fa-graduation-cap me-2"></i> Student Management System
            </a>
            <span class="badge bg-success p-2 fs-6 shadow-sm">
                <i class="fa-solid fa-server me-1"></i> Connected: App 1 Instance
            </span>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h3 class="text-secondary fw-bold mb-0">Students & Phones Registry</h3>
                <p class="text-muted small mb-0 mt-1">
                    <i class="fa-solid fa-circle-check text-success me-1"></i> Served by <strong>App 1 Container</strong> (Port 8080)
                </p>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <a href="add_student.php" class="btn btn-primary btn-md shadow-sm">
                    <i class="fa-solid fa-user-plus me-1"></i> Add New Student
                </a>
            </div>
        </div>

        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 35%;">Student Name</th>
                            <th style="width: 15%;">Age</th>
                            <th style="width: 20%;">Phone Number</th>
                            <th style="width: 20%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch()): ?>
                        <tr>
                            <td><span class="fw-bold text-muted">#<?= htmlspecialchars($row['student_id']) ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle p-2 me-2 text-center text-primary" style="width: 35px; height: 35px; line-height: 18px;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary px-2 py-1 fs-6"><?= htmlspecialchars($row['age']) ?> Years</span></td>
                            <td>
                                <?php if ($row['phone_number']): ?>
                                    <span class="text-dark"><i class="fa-solid fa-phone text-muted me-1 fs-7"></i> <?= htmlspecialchars($row['phone_number']) ?></span>
                                <?php else: ?>
                                    <span class="text-danger italic">No Phone</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                            	<a href="edit_student.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <a href="delete_student.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </a>
			    </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="text-center my-5 text-muted small">
            <p>Infrastructure Powered by <strong>Podman Containers</strong> & <strong>AWS EC2 Cluster</strong></p>
        </footer>
    </div>

</body>
</html>

