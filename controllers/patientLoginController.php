<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

$_SESSION['errors'] = [];
$_SESSION['success'] = "";

$email = isset($_POST['email']) ? $_POST['email'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if ($email == "" || $password == "") {
    $_SESSION['errors'] = ["Email and password are required."];
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$conn = connect();
$user = getPatientByEmailAndRole($conn, $email, "patient");

if (!$user) {
    close($conn);
    $_SESSION['errors'] = ["Invalid email or password."];
    header("Location: ../view/hospital_patient/PatientLogin.php");
    exit();
}

$passwordHash = $user['password_hash'];
$isValid = false;

if ($passwordHash != "" && password_verify($password, $passwordHash)) {
    $isValid = true;
} elseif ($passwordHash == $password) {
    $isValid = true;
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    updateUserPasswordHash($conn, $user['id'], $newHash);
}

close($conn);

if ($isValid) {
    $_SESSION['patient_id'] = $user['id'];
    $_SESSION['patient_name'] = $user['name'];
    header("Location: ../view/hospital_patient/PatientDashboard.php");
    exit();
}

$_SESSION['errors'] = ["Invalid email or password."];
header("Location: ../view/hospital_patient/PatientLogin.php");
exit();