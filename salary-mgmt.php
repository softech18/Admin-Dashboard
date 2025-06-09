<?php
include "db.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Add Salary Record
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_salary'])) {
    $emp_id = $conn->real_escape_string($_POST['emp_id']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $date   = $conn->real_escape_string($_POST['date']);
    if (empty($emp_id)) {
        $message = "Please select an employee.";
    } else {
        $sql = "INSERT INTO salaries (emp_id, amount, date) VALUES ('$emp_id', '$amount', '$date')";
        if ($conn->query($sql)) {
            $message = "Salary record added!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Fetch all employees
$employees = $conn->query("SELECT emp_id, name FROM employees");

// Fetch salary records (latest 20)
$salary_records = $conn->query("SELECT s.*, e.name FROM salaries s JOIN employees e ON s.emp_id = e.emp_id ORDER BY s.date DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Salary Management</title>
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
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="add-employee.php">
          <i class="fa-solid fa-user-plus"></i> Add Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="update-employee.php">
          <i class="fa-solid fa-user-edit"></i> Update Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="delete-employee.php">
          <i class="fa-solid fa-user-minus"></i> Delete Employee
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="salary-mgmt.php">
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
      <div class="container mt-5">
        <h2>Salary Management</h2>
        <?php if ($message): ?>
          <div class="alert alert-info mt-4"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Add Salary Form -->
        <div class="card mb-4">
          <div class="card-header">Add Salary Record</div>
          <div class="card-body">
            <form method="POST">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Employee</label>
                  <select name="emp_id" class="form-select" required>
                    <option value="">Select Employee</option>
                    <?php while($emp = $employees->fetch_assoc()): ?>
                      <option value="<?= htmlspecialchars($emp['emp_id']) ?>"><?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['emp_id']) ?>)</option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Amount</label>
                  <input type="number" name="amount" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Date</label>
                  <input type="date" name="date" class="form-control" required>
                </div>
                <div class="col-12">
                  <button type="submit" name="add_salary" class="btn btn-success">Add Salary</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Salary Records Table -->
        <div class="card">
          <div class="card-header">Recent Salary Records</div>
          <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Amount</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($salary_records && $salary_records->num_rows > 0): ?>
                  <?php while($row = $salary_records->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['emp_id']) ?>)</td>
                      <td><?= htmlspecialchars($row['amount']) ?></td>
                      <td><?= htmlspecialchars($row['date']) ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="3" class="text-center">No salary records found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebarToggle = document.querySelector("#sidebarToggle");
    const wrapper = document.querySelector("#wrapper");
    sidebarToggle.addEventListener("click", function (e) {
      e.preventDefault();
      wrapper.classList.toggle("toggled");
    });
  </script>
</body>
</html>