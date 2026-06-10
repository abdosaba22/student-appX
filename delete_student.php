<?php
$host = getenv('DB_HOST') ?: '172.17.0.1'; 
$db   = getenv('DB_NAME') ?: 'applicationDB';
$user = getenv('DB_USER') ?: 'app_user';
$pass = getenv('DB_PASSWORD') ?: 'YourStrongPassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $id = $_GET['id'] ?? null;
    $deleted = false;

    if ($id) {
        // التحقق أولاً من الوجود بأمان
        $stmtCheck = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmtCheck->execute([$id]);

        if ($stmtCheck->rowCount() > 0) {
            // وبسبب وجود الـ ON DELETE CASCADE ستُحذف الأرقام تلقائياً
            $stmtDelete = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
            $stmtDelete->execute([$id]);
            $deleted = true;
        }
    }
} catch (\PDOException $e) {
    $errorMsg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=index.php">
    <title>Deleting Record - App 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-5 shadow border-0 rounded-3">
                    <?php if (isset($deleted) && $deleted): ?>
                        <div class="text-danger mb-4">
                            <i class="fa-solid fa-trash-can fa-4x fa-bounce text-danger"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Record Removed Successfully!</h3>
                        <p class="text-muted">Purge executed completely via <span class="badge bg-primary">App 1 Container</span></p>
                        <div class="spinner-border spinner-border-sm text-secondary mt-3" role="status"></div>
                        <span class="text-muted small ms-2">Refreshing Registry Node...</span>
                    <?php else: ?>
                        <div class="text-warning mb-4">
                            <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Target Not Found</h3>
                        <p class="text-muted"><?= htmlspecialchars($errorMsg ?? 'Student record does not exist on core cluster.') ?></p>
                        <a href="index.php" class="btn btn-outline-secondary mt-3">Return to Main Terminal</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
