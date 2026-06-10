<?php
// قراءة إعدادات الاتصال ديناميكياً
$host = getenv('DB_HOST') ?: '172.17.0.1'; 
$db   = getenv('DB_NAME') ?: 'applicationDB';
$user = getenv('DB_USER') ?: 'app_user';
$pass = getenv('DB_PASSWORD') ?: 'YourStrongPassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header("Location: index.php");
        exit;
    }

    // معالجة تحديث البيانات عند إرسال الفورم (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $age = $_POST["age"];
        $phone = $_POST["phone"];

        // تحديث جدول الطلاب بأمان (Prepared Statements)
        $stmtUpdate = $pdo->prepare("UPDATE students SET name = ?, age = ? WHERE student_id = ?");
        $stmtUpdate->execute([$name, $age, $id]);

        // التحقق من رقم الهاتف الحالي
        $stmtCheck = $pdo->prepare("SELECT * FROM st_phones WHERE student_id = ?");
        $stmtCheck->execute([$id]);

        if ($stmtCheck->rowCount() > 0) {
            $stmtPhone = $pdo->prepare("UPDATE st_phones SET phone_number = ? WHERE student_id = ?");
            $stmtPhone->execute([$phone, $id]);
        } else {
            if (!empty($phone)) {
                $stmtPhone = $pdo->prepare("INSERT INTO st_phones (student_id, phone_number) VALUES (?, ?)");
                $stmtPhone->execute([$id, $phone]);
            }
        }
        $updateSuccess = true;
    }

    // جلب بيانات الطالب الحالية لملء النموذج
    $stmtFetch = $pdo->prepare("SELECT s.name, s.age, p.phone_number 
                                FROM students s 
                                LEFT JOIN st_phones p ON s.student_id = p.student_id 
                                WHERE s.student_id = ?");
    $stmtFetch->execute([$id]);
    $row = $stmtFetch->fetch();

    if (!$row) {
        die("<div class='alert alert-danger m-3'>Student Profile Not Found.</div>");
    }

} catch (\PDOException $e) {
    die("<div class='alert alert-danger m-3'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php if (isset($updateSuccess)): ?><meta http-equiv="refresh" content="2;url=index.php"><?php endif; ?>
    <title>Edit Student - App 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
        .card-header-custom { background: linear-gradient(135deg, #f39c12 0%, #d35400 100%); color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fa-solid fa-graduation-cap me-2"></i> Student Management System
            </a>
            <span class="badge bg-success p-2 fs-6 shadow-sm">
                <i class="fa-solid fa-server me-1"></i> Connected: App 1 Instance
            </span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <?php if (isset($updateSuccess)): ?>
                    <div class="alert alert-success text-center shadow mb-4">
                        <i class="fa-solid fa-circle-check fa-spin me-2"></i> Updated via <strong>App 1 Node</strong>! Redirecting...
                    </div>
                <?php endif; ?>

                <div class="card shadow border-0 rounded-3">
                    <div class="card-header card-header-custom p-3">
                        <h5 class="mb-0"><i class="fa-solid fa-user-gear me-2"></i> Modify Student Profile</h5>
                        <small class="text-white-50"><i class="fa-solid fa-bolt me-1"></i> Real-time update on App 1 Cluster</small>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Student Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Age</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                    <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($row['age']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-phone text-muted"></i></span>
                                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($row['phone_number'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-light border me-md-2">Back</a>
                                <button type="submit" class="btn btn-warning text-white px-4 shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Update Changes
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
