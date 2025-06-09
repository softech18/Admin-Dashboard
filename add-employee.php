<?php
include "db.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $conn->real_escape_string($_POST['emp_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $department = $conn->real_escape_string($_POST['department']);
    $password = $conn->real_escape_string($_POST['password']);

    // File upload logic
    $photo = $_FILES['photo']['name'];
    $target = "uploads/" . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $sql = "INSERT INTO employees (emp_id, name, email, phone, department, password, photo) 
            VALUES ('$emp_id', '$name', '$email', '$phone', '$department', '$password', '$photo')";

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: view-employees.php");
            exit;
        } else {
            if ($conn->errno == 1062) {
                $message = "Error: The Employee ID '$emp_id' is already used. Please use a different ID.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $message = "Error: The Employee ID '$emp_id' is already used. Please use a different ID.";
        } else {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    .admin-photo {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #0d6efd;
      margin-bottom: 10px;
      background: #fff;
    }
  </style>
</head>
<body class="bg-light">
  <div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
      <div class="sidebar-heading border-bottom bg-light text-center py-4">
        <img src="images/Untitled (1200 x 900 px).png" alt="Admin Photo" class="admin-photo" onerror="this.onerror=null;this.src='images/IMG_5672.JPG';">
        <div class="mt-2 fw-bold" style="font-size:1.2rem;">
          <?php echo htmlspecialchars($_SESSION['admin']); ?>
        </div>
      </div>
      <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dashboard.php">
          <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="view-employees.php">
          <i class="fa-solid fa-user-tie"></i> View Employees
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="add-employee.php">
          <i class="fa-solid fa-user-plus"></i> Add Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="update-employee.php">
          <i class="fa-solid fa-user-edit"></i> Update Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="delete-employee.php">
          <i class="fa-solid fa-user-minus"></i> Delete Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="salary-mgmt.php">
          <i class="fa-solid fa-dollar-sign"></i> Salary Mgmt
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="leave-mgmt.php">
          <i class="fa-solid fa-calendar-check"></i> Leave Mgmt
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
            <?php echo htmlspecialchars($_SESSION['admin']); ?>
          </span>
        </div>
      </nav>
      <!-- Page content-->
      <div class="container mt-5">
        <h2>Add New Employee</h2>

        <?php if ($message): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mt-3">
          <div class="mb-3">
            <label>Employee ID</label>
            <input type="text" name="emp_id" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Department</label>
            <input type="text" name="department" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Photo</label>
            <input type="file" name="photo" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-success">Add Employee</button>
          <a href="view-employees.php" class="btn btn-secondary">Cancel</a>
        </form>
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
