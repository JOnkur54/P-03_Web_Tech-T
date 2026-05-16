<?php
session_start();
include '../model/connect.php';
include '../model/ModelDoctorAuth.php';

// Handle Logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: ../view/ViewDocLogin.php");
        exit;
    }

    $authModel = new ModelDoctorAuth($conn);
    $result = $authModel->login($email, $password);

    if (isset($result['error'])) {
        $_SESSION['error'] = $result['error'];
        header("Location: ../view/ViewDocLogin.php");
        exit;
    } else {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['role'] = $result['role'];
        $_SESSION['doctor_id'] = $result['doctor_id'];

        header("Location: ../view/ViewDocDashboard.php");
        exit;
    }
} else {
    header("Location: ../view/ViewDocLogin.php");
    exit;
}
?>
