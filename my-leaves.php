<?php
include "db.php";
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: employee-login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];
$leaves = $conn->query("SELECT * FROM leave_requests WHERE emp_id='$emp_id' ORDER BY date_from DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Leaves</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2><i class="fa-solid fa-calendar-exclamation"></i> My Leaves</h2>
    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th>From</th>
          <th>To</th>
          <th>Reason</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($leaves && $leaves->num_rows > 0): ?>
          <?php while($row = $leaves->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['date_from']) ?></td>
              <td><?= htmlspecialchars($row['date_to']) ?></td>
              <td><?= htmlspecialchars($row['reason']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center">No leave records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="employee-profile.php" class="btn btn-secondary mt-3">Back to Profile</a>
  </div>
</body>
</html>