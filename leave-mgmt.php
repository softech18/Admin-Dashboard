<?php
include "db.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Approve/Reject logic
$message = "";
if (isset($_POST['action'], $_POST['leave_id'])) {
    $leave_id = intval($_POST['leave_id']);
    $status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';
    $conn->query("UPDATE leave_requests SET status='$status' WHERE id=$leave_id");
    $message = "Leave request has been $status.";
}

// Fetch leave requests
$leave_requests = $conn->query("SELECT l.*, e.name FROM leave_requests l JOIN employees e ON l.emp_id = e.emp_id ORDER BY l.date_from DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave Management</title>
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
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="salary-mgmt.php">
          <i class="fa-solid fa-dollar-sign"></i> Salary Mgmt
        </a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="leave-mgmt.php">
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
        <h2>Leave Management</h2>
        <?php if ($message): ?>
          <div class="alert alert-info mt-4"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Leave Requests Table -->
        <div class="card">
          <div class="card-header">Leave Requests</div>
          <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Reason</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($leave_requests && $leave_requests->num_rows > 0): ?>
                  <?php while($row = $leave_requests->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['emp_id']) ?>)</td>
                      <td><?= htmlspecialchars($row['date_from']) ?></td>
                      <td><?= htmlspecialchars($row['date_to']) ?></td>
                      <td><?= htmlspecialchars($row['reason']) ?></td>
                      <td><?= htmlspecialchars($row['status']) ?></td>
                      <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                          <form method="POST" class="d-inline">
                            <input type="hidden" name="leave_id" value="<?= $row['id'] ?>">
                            <button name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                            <button name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                          </form>
                        <?php else: ?>
                          <span class="text-muted">No action</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center">No leave requests found.</td></tr>
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