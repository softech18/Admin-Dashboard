<?php
include "db.php";
session_start();

if (isset($_SESSION['emp_id'])) {
    header("Location: employee-profile.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $conn->real_escape_string($_POST['emp_id']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM employees WHERE emp_id='$emp_id' AND password='$password'";
    $res = $conn->query($sql);

    if ($res && $res->num_rows === 1) {
        $_SESSION['emp_id'] = $emp_id;
        header("Location: employee-profile.php");
        exit;
    } else {
        $error = "Invalid Employee ID or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Employee Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="width: 350px;">
      <h3 class="mb-3 text-center">Employee Login</h3>

      <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <input type="text" name="emp_id" class="form-control mb-3" placeholder="Employee ID" required />
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required />
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
