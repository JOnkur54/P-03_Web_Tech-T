<?php
session_start();

require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";

$_SESSION['errors']  = [];
$_SESSION['success'] = "";

$email    = isset($_POST['email'])    ? trim($_POST['email'])    : "";
$password = isset($_POST['password']) ? trim($_POST['password']) : "";

if ($email == "" || $password == "") {
    $_SESSION['errors'] = ["Email and password are required."];
    header("Location: ../view/hospital_receptionist/receptionistLogin.php");
    exit();
}

$conn = connect();
$user = receptionistGetUserByEmail($conn, $email);

if (!$user) {
    close($conn);
    $_SESSION['errors'] = ["Invalid email or password."];
    header("Location: ../view/hospital_receptionist/receptionistLogin.php");
    exit();
}

$isValid = false;
if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
    $isValid = true;
} elseif ($user['password_hash'] == $password) {
    $isValid  = true;
    $newHash  = password_hash($password, PASSWORD_DEFAULT);
    receptionistUpdatePasswordHash($conn, $user['id'], $newHash);
}
close($conn);

if ($isValid && (int)$user['is_active'] === 1) {
    $_SESSION['receptionist_id'] = $user['id'];
    $_SESSION['name']            = $user['name'];
    $_SESSION['role']            = 'receptionist';
    header("Location: ../controllers/receptionistDashboardController.php");
    exit();
}

$_SESSION['errors'] = ["Invalid email or password."];
header("Location: ../view/hospital_receptionist/receptionistLogin.php");
exit();