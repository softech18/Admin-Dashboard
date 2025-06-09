<?php
// ems/index.php
include "db.php";
session_start();

// If already logged in as admin, send to dashboard:
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

// Process login form:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $u = $conn->real_escape_string($_POST['username']);
    $p = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM super_admin WHERE username='$u' AND password='$p' ";
    $res = $conn->query($sql);

    if ($res && $res->num_rows === 1) {
        $_SESSION['admin'] = $u;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Super Admin Login</title>
  <!-- Bootstrap CSS CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
      <div class="col-md-7 col-lg-6">
        <div class="text-center mb-4">
          <img src="images/IMG_5672.JPG" alt="Softech 18 Logo" style="max-width:120px; border-radius:16px; box-shadow:0 2px 16px #b6b6b6;">
          <h2 class="mt-3 mb-1 fw-bold" style="color:#0d6efd;">Best Web Designing</h2>
          <div class="mb-2" style="font-size:1.1rem;color:#555;">softech 18</div>
          <div class="mb-3" style="font-size:0.95rem;color:#888;">
            A website is like a house. It has to be attractive, inviting, and memorable to attract a good number of visitors.
          </div>
        </div>
        <div class="card shadow border-0">
          <div class="card-body p-4">
            <h3 class="card-title text-center mb-4 fw-semibold" style="color:#0d6efd;">Welcome to EMS</h3>
            <div class="d-grid gap-3 mb-4">
              <button class="btn btn-primary btn-lg" onclick="showAdminLogin()">Admin Login</button>
              <a href="employee-login.php" class="btn btn-success btn-lg">Employee Login</a>
            </div>
            <div id="adminLoginForm" style="display:<?= isset($error) ? 'block' : 'none' ?>;">
              <h5 class="text-center mb-3">Super Admin Login</h5>
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              <form method="POST">
                <div class="mb-3">
                  <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 btn-lg">Login</button>
              </form>
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
  <script>
    function showAdminLogin() {
      document.getElementById('adminLoginForm').style.display = 'block';
      window.scrollTo({top: 0, behavior: 'smooth'});
    }
  </script>
  <!-- Bootstrap JS Bundle CDN (Popper + Bootstrap) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>