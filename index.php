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
</head>
<body class="bg-light">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:80vh;">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Super Admin Login</h3>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <input
                  type="text"
                  name="username"
                  class="form-control"
                  placeholder="Username"
                  required
                >
              </div>
              <div class="mb-3">
                <input
                  type="password"
                  name="password"
                  class="form-control"
                  placeholder="Password"
                  required
                >
              </div>
              <button type="submit" name="login" class="btn btn-primary w-100">
                Login
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle CDN (Popper + Bootstrap) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>