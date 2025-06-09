<?php
// ems/update-employee.php
include "db.php";
session_start();

// Only Super Admin can access:
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Initialize variables:
$emp_id       = "";
$name         = "";
$email        = "";
$phone        = "";
$department   = "";
$password     = "";
$message      = "";

// 1) If the “Search” form was submitted:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_emp'])) {
    $emp_id = $conn->real_escape_string($_POST['search_emp_id']);
    $res    = $conn->query("SELECT * FROM employees WHERE emp_id='$emp_id'");

    if ($res && $res->num_rows === 1) {
        // Load existing details into variables:
        $row         = $res->fetch_assoc();
        $name        = $row['name'];
        $email       = $row['email'];
        $phone       = $row['phone'];
        $department  = $row['department'];
        $password    = $row['password'];
    } else {
        $message = "No employee found with ID “{$emp_id}”.";
    }
}

// 2) If the “Update” form was submitted:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_emp'])) {
    // Grab all posted fields:
    $emp_id      = $conn->real_escape_string($_POST['emp_id']);
    $name        = $conn->real_escape_string($_POST['name']);
    $email       = $conn->real_escape_string($_POST['email']);
    $phone       = $conn->real_escape_string($_POST['phone']);
    $department  = $conn->real_escape_string($_POST['department']);
    $password    = $conn->real_escape_string($_POST['password']);

    // Run the UPDATE query:
    $sql = "
      UPDATE employees
      SET name='$name',
          email='$email',
          phone='$phone',
          department='$department',
          password='$password'
      WHERE emp_id='$emp_id'
    ";

    if ($conn->query($sql)) {
        $message = "Employee “{$emp_id}” updated successfully.";
    } else {
        $message = "Error updating: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Employee</title>
  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <!-- FontAwesome for icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  >
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
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="add-employee.php">
          <i class="fa-solid fa-user-plus"></i> Add Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="update-employee.php">
          <i class="fa-solid fa-user-edit"></i> Update Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="delete-employee.php">
          <i class="fa-solid fa-user-minus"></i> Delete Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="salary-mgmt.php">
          <i class="fa-solid fa-indian-rupee-sign"></i> Salary Mgmt
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
      <div class="container py-5">
        <h2 class="mb-4">Update Employee</h2>

        <?php if ($message): ?>
          <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- 1) Search Form -->
        <div class="card mb-4 shadow-sm">
          <div class="card-body">
            <form method="POST" class="row g-3">
              <div class="col-md-6">
                <label for="search_emp_id" class="form-label">Employee ID</label>
                <input
                  type="text"
                  id="search_emp_id"
                  name="search_emp_id"
                  class="form-control"
                  placeholder="Enter Employee ID to search"
                  required
                >
              </div>
              <div class="col-md-6 align-self-end">
                <button name="search_emp" class="btn btn-primary">
                  <i class="fa-solid fa-magnifying-glass"></i> Search
                </button>
                <a href="dashboard.php" class="btn btn-secondary ms-2">
                  <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>
              </div>
            </form>
          </div>
        </div>

        <!-- 2) Update Form (only show if $name is not empty) -->
        <?php if ($name !== ""): ?>
          <div class="card shadow-sm">
            <div class="card-body">
              <form method="POST" class="row g-3">
                <!-- emp_id (readonly) -->
                <div class="col-md-4">
                  <label for="emp_id" class="form-label">Employee ID</label>
                  <input
                    type="text"
                    id="emp_id"
                    name="emp_id"
                    class="form-control"
                    value="<?php echo htmlspecialchars($emp_id); ?>"
                    readonly
                  >
                </div>
                <!-- Name -->
                <div class="col-md-8">
                  <label for="name" class="form-label">Full Name</label>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($name); ?>"
                    required
                  >
                </div>
                <!-- Email -->
                <div class="col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                  >
                </div>
                <!-- Phone -->
                <div class="col-md-6">
                  <label for="phone" class="form-label">Phone</label>
                  <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="form-control"
                    value="<?php echo htmlspecialchars($phone); ?>"
                    required
                  >
                </div>
                <!-- Department -->
                <div class="col-md-6">
                  <label for="department" class="form-label">Department</label>
                  <input
                    type="text"
                    id="department"
                    name="department"
                    class="form-control"
                    value="<?php echo htmlspecialchars($department); ?>"
                    required
                  >
                </div>
                <!-- Password -->
                <div class="col-md-6">
                  <label for="password" class="form-label">Password</label>
                  <input
                    type="text"
                    id="password"
                    name="password"
                    class="form-control"
                    value="<?php echo htmlspecialchars($password); ?>"
                    required
                  >
                </div>
                <!-- Submit Button -->
                <div class="col-12">
                  <button name="update_emp" class="btn btn-success">
                    <i class="fa-solid fa-user-check"></i> Update Employee
                  </button>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>

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