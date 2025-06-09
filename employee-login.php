<?php
include "db.php";
session_start();

if (isset($_SESSION['emp_id'])) {
    header("Location: employee-profile.php");
    exit;
}

// Employee login logic
$emp_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emp_login'])) {
    $emp_id = $conn->real_escape_string($_POST['emp_id']);
    $emp_password = $conn->real_escape_string($_POST['emp_password']);

    $sql = "SELECT * FROM employees WHERE emp_id='$emp_id' AND password='$emp_password'";
    $res = $conn->query($sql);

    if ($res && $res->num_rows === 1) {
        $_SESSION['emp_id'] = $emp_id;
        header("Location: employee-profile.php");
        exit;
    } else {
        $emp_error = "Invalid Employee ID or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EMS Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
      <div class="col-md-7 col-lg-6">
        <div class="text-center mb-4">
          <img src="images/IMG_5672.JPG" alt="Softech 18 Logo" style="max-width:120px; border-radius:16px; box-shadow:0 2px 16px #b6b6b6;">
          <h2 class="mt-3 mb-1 fw-bold" style="color:#0d6efd;">Employee Login</h2>
          <div class="mb-2" style="font-size:1.1rem;color:#555;">softech 18</div>
        </div>
        <div class="card shadow border-0">
          <div class="card-body p-4">
            <h3 class="card-title text-center mb-4 fw-semibold" style="color:#0d6efd;">Welcome to EMS</h3>
            <?php if (!empty($emp_error)): ?>
              <div class="alert alert-danger"><?php echo $emp_error; ?></div>
            <?php endif; ?>
            <form method="POST">
              <div class="mb-3">
                <input type="text" name="emp_id" class="form-control form-control-lg" placeholder="Employee ID" required>
              </div>
              <div class="mb-3">
                <input type="password" name="emp_password" class="form-control form-control-lg" placeholder="Password" required>
              </div>
              <button type="submit" name="emp_login" class="btn btn-success w-100 btn-lg">Login</button>
            </form>
            <div class="mt-4 text-center">
              <a href="index.php" class="btn btn-outline-primary btn-sm">Back to Admin Login</a>
            </div>
            <div class="mt-4 text-center" style="font-size:0.95rem;color:#888;">
              <i class="fa-solid fa-phone"></i> +91-9937857561, +91-9937621642<br>
              <i class="fa-solid fa-envelope"></i> info@softech18.com
            </div>
          </div>
        </div>
        <div class="text-center mt-4" style="font-size:0.95rem;color:#888;">
          <a href="#" class="me-2 text-decoration-none"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="me-2 text-decoration-none"><i class="fa-brands fa-twitter"></i></a>
          <a href="#" class="me-2 text-decoration-none"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="me-2 text-decoration-none"><i class="fa-brands fa-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
