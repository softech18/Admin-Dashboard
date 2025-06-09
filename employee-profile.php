<?php
include "db.php";
session_start();

if (!isset($_SESSION['emp_id'])) {
    header("Location: employee-login.php");
    exit;
}

$emp_id = $conn->real_escape_string($_SESSION['emp_id']);

$sql = "SELECT * FROM employees WHERE emp_id='$emp_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
    $data = $result->fetch_assoc();
} else {
    session_unset();
    session_destroy();
    header("Location: employee-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-photo {
      width: 140px;
      height: 140px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #0d6efd;
      margin-top: -70px;
      background: #fff;
    }
    .header-bg {
      height: 120px;
      background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
      border-radius: 10px 10px 0 0;
      position: relative;
    }
    .welcome-text {
      position: absolute;
      bottom: 15px;
      left: 20px;
      color: white;
      font-weight: 600;
      font-size: 1.5rem;
    }
    .profile-card {
      max-width: 700px;
      margin: 40px auto;
      box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
      border-radius: 10px;
      background: #fff;
    }
    .sidebar-heading {
      font-weight: bold;
      font-size: 1.2rem;
    }
    .list-group-item.active {
      background: #0d6efd;
      color: #fff;
      border: none;
    }
    @media (max-width: 991.98px) {
      #sidebar-wrapper {
        min-width: 0;
        width: 0;
        display: none;
      }
      #wrapper.toggled #sidebar-wrapper {
        display: block;
        width: 220px;
        min-width: 220px;
        position: absolute;
        z-index: 1000;
        background: #fff;
        height: 100vh;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
      <div class="sidebar-heading border-bottom bg-light text-center py-4">
        <?php if (!empty($data['photo'])): ?>
          <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo" style="width:90px;height:90px;object-fit:cover;border-radius:50%;border:3px solid #0d6efd;">
        <?php else: ?>
          <img src="https://via.placeholder.com/90" alt="Profile Photo" style="width:90px;height:90px;object-fit:cover;border-radius:50%;border:3px solid #0d6efd;">
        <?php endif; ?>
        <div class="mt-2 fw-bold" style="font-size:1.2rem;">
          Welcome,<br><?= htmlspecialchars($data['name']) ?>
        </div>
      </div>
      <div class="list-group list-group-flush">
        
        <a class="list-group-item list-group-item-action list-group-item-light p-3<?= basename($_SERVER['PHP_SELF']) == 'employee-profile.php' ? ' active' : '' ?>" href="employee-profile.php">
          <i class="fa-solid fa-id-card"></i> My Profile
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3<?= basename($_SERVER['PHP_SELF']) == 'my-leaves.php' ? ' active' : '' ?>" href="my-leaves.php">
          <i class="fa-solid fa-calendar-exclamation"></i> My Leaves
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3<?= basename($_SERVER['PHP_SELF']) == 'my-salary.php' ? ' active' : '' ?>" href="my-salary.php">
          <i class="fa-solid fa-indian-rupee-sign"></i> My Salary
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="logout.php">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
    <!-- /#sidebar-wrapper-->

    <!-- Page content wrapper-->
    <div id="page-content-wrapper" class="w-100">
      <!-- Top navigation-->
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
          <button class="btn btn-primary" id="sidebarToggle">
            <i class="fa-solid fa-bars"></i>
          </button>
          <span class="navbar-text ms-auto">
            <i class="fa-solid fa-user"></i>
            <?= htmlspecialchars($data['name']) ?>
          </span>
        </div>
      </nav>
      <!-- Page content-->
      <div class="container mt-4">
        <div class="profile-card">
          <div class="header-bg">
            <div class="welcome-text">
              <i class="fa-solid fa-id-badge"></i> Welcome, <?= htmlspecialchars($data['name']) ?>
            </div>
          </div>
          <div class="text-center">
            <?php if (!empty($data['photo'])): ?>
              <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo" class="profile-photo shadow" />
            <?php else: ?>
              <img src="https://via.placeholder.com/140" alt="Profile Photo" class="profile-photo shadow" />
            <?php endif; ?>
          </div>
          <div class="p-4">
            <div class="row mb-3">
              <div class="col-sm-4 fw-semibold"><i class="fa-solid fa-id-card"></i> Employee ID:</div>
              <div class="col-sm-8"><?= htmlspecialchars($data['emp_id']) ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 fw-semibold"><i class="fa-solid fa-envelope"></i> Email:</div>
              <div class="col-sm-8"><?= htmlspecialchars($data['email']) ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 fw-semibold"><i class="fa-solid fa-phone"></i> Phone:</div>
              <div class="col-sm-8"><?= htmlspecialchars($data['phone']) ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 fw-semibold"><i class="fa-solid fa-building"></i> Department:</div>
              <div class="col-sm-8"><?= htmlspecialchars($data['department']) ?></div>
            </div>
            <!-- Add more fields if needed -->
            <a href="logout.php" class="btn btn-danger w-100 mt-4">
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper-->
  </div>
  <!-- /#wrapper-->

  <!-- Bootstrap JS Bundle (Popper + Bootstrap) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle the sidebar
    const sidebarToggle = document.querySelector("#sidebarToggle");
    const wrapper = document.querySelector("#wrapper");
    sidebarToggle.addEventListener("click", function (e) {
      e.preventDefault();
      wrapper.classList.toggle("toggled");
    });
  </script>
</body>
</html>
