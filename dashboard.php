<?php
// ems/dashboard.php
include "db.php";
session_start();

// If neither admin nor employee is logged in, redirect to admin-login:
if (!isset($_SESSION['admin']) && !isset($_SESSION['emp_id'])) {
    header("Location: index.php");
    exit;
}

// --------------
//  A) If Admin is logged in:
// --------------
if (isset($_SESSION['admin'])) {
    // Fetch counts/metrics from the database:
    $dept_q     = $conn->query("SELECT COUNT(DISTINCT department) AS cnt FROM employees") or die($conn->error);
    $dept_count = $dept_q->fetch_assoc()['cnt'];

    $staff_q     = $conn->query("SELECT COUNT(*) AS cnt FROM employees") or die($conn->error);
    $staff_count = $staff_q->fetch_assoc()['cnt'];

    // 3. Total Leave Requests
    $leave_count = 0;
    if ($conn->query("SHOW TABLES LIKE 'leave_requests'")->num_rows === 1) {
        $lr_q        = $conn->query("SELECT COUNT(*) AS cnt FROM leave_requests") or die($conn->error);
        $leave_count = $lr_q->fetch_assoc()['cnt'];
    }

    // 4. Total Salary Paid
    $salary_sum = 0;
    if ($conn->query("SHOW TABLES LIKE 'salaries'")->num_rows === 1) {
        $sal_q      = $conn->query("SELECT IFNULL(SUM(amount),0) AS total FROM salaries") or die($conn->error);
        $salary_sum = $sal_q->fetch_assoc()['total'];
    }

    // Render the Admin Dashboard HTML:
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Admin Dashboard</title>
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
      <link rel="stylesheet" href="style.css">
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
    <body>
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
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="dashboard.php"
            >
              <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="#department"
              onclick="alert('Department page not yet created.')"
            >
              <i class="fa-solid fa-building"></i> Department
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="view-employees.php"
            >
              <i class="fa-solid fa-user-tie"></i> View Employees
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="add-employee.php"
            >
              <i class="fa-solid fa-user-plus"></i> Add Employee
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="update-employee.php"
            >
              <i class="fa-solid fa-user-edit"></i> Update Employee
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="delete-employee.php"
            >
              <i class="fa-solid fa-user-minus"></i> Delete Employee
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="salary-mgmt.php">
              <i class="fa-solid fa-dollar-sign"></i> Salary Mgmt
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="leave-mgmt.php">
              <i class="fa-solid fa-calendar-check"></i> Leave Mgmt
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="logout.php"
            >
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
          </div>
        </div>
        <!-- /#sidebar-wrapper-->

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
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
          <div class="container-fluid mt-4">
            <h1 class="mt-2">Dashboard</h1>

            <!-- Row 1: Metrics Cards -->
            <div class="row mt-4">
              <!-- Card 1: Departments -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          <i class="fa-solid fa-building"></i> Departments
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php echo $dept_count; ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-building fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card 2: Staff -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                          <i class="fa-solid fa-user-tie"></i> Staff
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php echo $staff_count; ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-user-tie fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card 3: Leave Requests -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                          <i class="fa-solid fa-calendar-check"></i> Leave Requests
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php echo $leave_count; ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-calendar-check fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card 4: Salary Paid -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                          <i class="fa-solid fa-indian-rupee-sign"></i> Salary Paid
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          ₹<?php echo number_format($salary_sum, 2); ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-indian-rupee-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Row 2: CRUD Action Cards -->
            <div class="row mt-4">
              <!-- Add Employee Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        <i class="fa-solid fa-user-plus"></i> Add Employee
                      </div>
                      <a href="add-employee.php" class="btn btn-sm btn-outline-primary">
                        Go to Add Form
                      </a>
                    </div>
                    <div>
                      <i class="fa-solid fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Update Employee Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        <i class="fa-solid fa-user-edit"></i> Update Employee
                      </div>
                      <a href="update-employee.php" class="btn btn-sm btn-outline-success">
                        Go to Update Form
                      </a>
                    </div>
                    <div>
                      <i class="fa-solid fa-user-edit fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Delete Employee Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        <i class="fa-solid fa-user-minus"></i> Delete Employee
                      </div>
                      <a href="delete-employee.php" class="btn btn-sm btn-outline-danger">
                        Go to Delete Form
                      </a>
                    </div>
                    <div>
                      <i class="fa-solid fa-user-minus fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- ———————————————————————————————————————————— -->

          </div>
        </div>
        <!-- /#page-content-wrapper-->
      </div>
      <!-- /#wrapper-->

      <!-- Bootstrap JS Bundle (Popper + Bootstrap) -->
      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      ></script>
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
    <?php
    exit; // stop here so the Employee part below does not run
}

// --------------
//  B) If Employee is logged in:
// --------------
if (isset($_SESSION['emp_id'])) {
    // Fetch employee details
    $emp_id = $_SESSION['emp_id'];
    $emp_q  = $conn->query("SELECT * FROM employees WHERE emp_id='$emp_id'");
    if ($emp_q->num_rows !== 1) {
        // If somehow employee record is missing, force logout:
        header("Location: logout.php");
        exit;
    }
    $emp = $emp_q->fetch_assoc();

    // Count leaves for this employee (if table exists)
    $emp_leave_count = 0;
    if ($conn->query("SHOW TABLES LIKE 'leave_requests'")->num_rows === 1) {
        $le_q = $conn->query("
            SELECT COUNT(*) AS cnt 
            FROM leave_requests 
            WHERE emp_id = '$emp_id'
        ") or die($conn->error);
        $emp_leave_count = $le_q->fetch_assoc()['cnt'];
    }

    // Sum salary received by this employee (if table exists)
    $emp_salary_sum = 0;
    if ($conn->query("SHOW TABLES LIKE 'salaries'")->num_rows === 1) {
        $sa_q = $conn->query("
            SELECT IFNULL(SUM(amount),0) AS total 
            FROM salaries 
            WHERE emp_id = '$emp_id'
        ") or die($conn->error);
        $emp_salary_sum = $sa_q->fetch_assoc()['total'];
    }

    // Render the Employee Dashboard HTML:
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Employee Dashboard</title>
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
      <link rel="stylesheet" href="style.css">
    </head>
    <body>
      <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
          <div class="sidebar-heading border-bottom bg-light">
            <i class="fa-solid fa-user-circle"></i> My Panel
          </div>
          <div class="list-group list-group-flush">
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="dashboard.php"
            >
              <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="employee-profile.php"
            >
              <i class="fa-solid fa-id-card"></i> My Profile
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="#my-leaves"
              onclick="alert('Leaves page not yet created.')"
            >
              <i class="fa-solid fa-calendar-exclamation"></i> My Leaves
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="#my-salary"
              onclick="alert('Salary details page not yet created.')"
            >
              <i class="fa-solid fa-dollar-sign"></i> My Salary
            </a>
            <a
              class="list-group-item list-group-item-action list-group-item-light p-3"
              href="logout.php"
            >
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
          </div>
        </div>
        <!-- /#sidebar-wrapper-->

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
          <!-- Top navigation-->
          <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
              <button class="btn btn-primary" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
              </button>
              <span class="navbar-text ms-auto">
                <i class="fa-solid fa-user"></i>
                <?php echo htmlspecialchars($emp['name']); ?>
              </span>
            </div>
          </nav>
          <!-- Page content-->
          <div class="container-fluid mt-4">
            <h1 class="mt-2">Welcome, <?php echo htmlspecialchars($emp['name']); ?>!</h1>
            <div class="row mt-4">
              <!-- Card 1: Department -->
              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          <i class="fa-solid fa-building"></i> Your Department
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php echo htmlspecialchars($emp['department']); ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-building fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card 2: Leaves Taken -->
              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                          <i class="fa-solid fa-calendar-check"></i> Leaves Taken
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php echo $emp_leave_count; ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-calendar-check fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Card 3: Total Salary Received -->
              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                          <i class="fa-solid fa-dollar-sign"></i> Total Salary Received
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          ₹<?php echo number_format($emp_salary_sum, 2); ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fa-solid fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- You can add more rows or personal stats here -->
          </div>
        </div>
        <!-- /#page-content-wrapper-->
      </div>
      <!-- /#wrapper-->

      <!-- Bootstrap JS Bundle (Popper + Bootstrap) -->
      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      ></script>
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
    <?php
    exit;
}
?>