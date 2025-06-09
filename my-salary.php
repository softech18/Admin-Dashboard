<?php
include "db.php";
session_start();
if (!isset($_SESSION['emp_id'])) {
    header("Location: employee-login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];
$salaries = $conn->query("SELECT * FROM salaries WHERE emp_id='$emp_id' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Salary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2><i class="fa-solid fa-indian-rupee-sign"></i> My Salary</h2>
    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th>Amount</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($salaries && $salaries->num_rows > 0): ?>
          <?php while($row = $salaries->fetch_assoc()): ?>
            <tr>
              <td>₹<?= htmlspecialchars($row['amount']) ?></td>
              <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="2" class="text-center">No salary records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="employee-profile.php" class="btn btn-secondary mt-3">Back to Profile</a>
  </div>
</body>
</html>