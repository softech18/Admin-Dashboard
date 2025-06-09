<?php
session_start();

if (isset($_SESSION['admin'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Admin login page
    exit;
}

if (isset($_SESSION['emp_id'])) {
    session_unset();
    session_destroy();
    header("Location: employee-login.php"); // Employee login page
    exit;
}

// If nobody is logged in, redirect to admin login page by default
header("Location: index.php");
exit;
?>
