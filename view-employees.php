<?php
include "db.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM employees");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Employees</title>
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
        <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="view-employees.php">
          <i class="fa-solid fa-user-tie"></i> View Employees
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="add-employee.php">
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
      <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2>All Employees</h2>
          <a href="add-employee.php" class="btn btn-success">
            <i class="fa fa-user-plus"></i> Add Employee
          </a>
        </div>

        <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Emp ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Department</th>
              <th>Photo</th>
              <th>Password</th>
              <th>Action</th> <!-- Add this line -->
            </tr>
          </thead>
          <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['emp_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td>
                  <?php if (!empty($row['photo'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['photo']) ?>" alt="Photo" width="60" height="60" class="rounded">
                  <?php else: ?>
                    <span class="text-muted">No photo</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['password']) ?></td>
                <td>
                  <form method="POST" action="delete-employee.php" onsubmit="return confirm('Are you sure you want to delete this employee?');" style="display:inline;">
                    <input type="hidden" name="emp_id" value="<?= htmlspecialchars($row['emp_id']) ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fa-solid fa-user-minus"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <div class="alert alert-warning">No employee records found.</div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn btn-secondary mt-3">
          <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
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
