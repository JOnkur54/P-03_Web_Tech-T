<?php

// ------------------------------------------------------------------
// adminLoginController.php
// Validates admin credentials and starts a session.
// ------------------------------------------------------------------

session_start();

require_once "../model/connect.php";
require_once "../model/adminLoginModel.php";
require_once "../model/close.php";

$_SESSION['errors']  = [];
$_SESSION['success'] = "";

$email    = isset($_POST['email'])    ? trim($_POST['email'])    : "";
$password = isset($_POST['password']) ? trim($_POST['password']) : "";

// ── Basic validation ──────────────────────────────────────────────
if ($email === "" || $password === "") {
    $_SESSION['errors'] = ["Email and password are required."];
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

// ── DB lookup ─────────────────────────────────────────────────────
$conn = connect();
$user = adminGetUserByEmailAndRole($conn, $email, "admin");

if (!$user) {
    close($conn);
    $_SESSION['errors'] = ["Invalid email or password."];
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

// ── Password check ────────────────────────────────────────────────
$isValid = false;

if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
    $isValid = true;
} elseif ($user['password_hash'] === $password) {
    // Plain-text fallback: upgrade to hash
    $isValid  = true;
    $newHash  = password_hash($password, PASSWORD_DEFAULT);
    adminUpdatePasswordHash($conn, $user['id'], $newHash);
}

close($conn);

if ($isValid && (int)$user['is_active'] === 1) {
    $_SESSION['admin_id']   = $user['id'];
    $_SESSION['name']       = $user['name'];
    $_SESSION['role']       = 'admin';
    header("Location: ../controllers/adminDashboardController.php");
    exit();
}

$_SESSION['errors'] = ["Invalid email or password, or account is inactive."];
header("Location: ../view/hospital_admin/adminLogin.php");
exit();