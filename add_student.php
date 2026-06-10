<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - App 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
        .card-header-custom { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; }
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
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header card-header-custom p-3">
                        <h5 class="mb-0"><i class="fa-solid fa-user-plus me-2"></i> Register New Student</h5>
                        <small class="text-white-50"><i class="fa-solid fa-code-branch me-1"></i> Action processed via App 1 Node</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="insert_student.php" method="POST">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-secondary">Student Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Hamza Amin" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="age" class="form-label fw-bold text-secondary">Age</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                    <input type="number" class="form-control" id="age" name="age" min="1" placeholder="e.g., 25" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label fw-bold text-secondary">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-phone text-muted"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="e.g., 01012345678">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-light border me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fa-solid fa-circle-check me-1"></i> Save Student
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
