<?php
$host = getenv('DB_HOST') ?: '172.17.0.1';
$db   = getenv('DB_NAME') ?: 'applicationDB';
$user = getenv('DB_USER') ?: 'app_user';
$pass = getenv('DB_PASSWORD') ?: 'YourStrongPassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $phone = $_POST['phone'];

        // 1. إدخال بيانات الطالب أولاً
        $stmt = $pdo->prepare("INSERT INTO students (name, age) VALUES (?, ?)");
        $stmt->execute([$name, $age]);
        $student_id = $pdo->lastInsertId();

        // 2. إدخال رقم الهاتف إذا وُجد
        if (!empty($phone)) {
            $stmtPhone = $pdo->prepare("INSERT INTO st_phones (student_id, phone_number) VALUES (?, ?)");
            $stmtPhone->execute([$student_id, $phone]);
        }

        $success = true;
    }
} catch (\PDOException $e) {
    $success = false;
    $errorMsg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-serif="refresh" content="3;url=index.php">
    <title>Processing - App 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-5 shadow border-0 rounded-3">
                    <?php if (isset($success) && $success): ?>
                        <div class="text-success mb-4">
                            <i class="fa-solid fa-circle-check fa-4x-step fa-bounce"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Data Processed Successfully!</h3>
                        <p class="text-muted">Transaction handled by <span class="badge bg-primary">App 1 Container</span></p>
                        <div class="spinner-border spinner-border-sm text-secondary mt-3" role="status"></div>
                        <span class="text-muted small ms-2">Redirecting to Registry...</span>
                    <?php else: ?>
                        <div class="text-danger mb-4">
                            <i class="fa-solid fa-circle-xmark fa-4x"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Execution Error</h3>
                        <p class="text-danger"><?= htmlspecialchars($errorMsg ?? 'Unknown internal error') ?></p>
                        <a href="add_student.php" class="btn btn-outline-secondary mt-3">Try Again</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

