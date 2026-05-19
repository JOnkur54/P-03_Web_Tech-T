<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientRegisterModel.php";
require_once "../model/close.php";

if (isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientDashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../view/hospital_patient/patientRegister.php");
    exit();
}

$errors = [];

$name = isset($_POST['name']) ? trim($_POST['name']) : "";
$email = isset($_POST['email']) ? trim($_POST['email']) : "";
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";
$dob = isset($_POST['dob']) ? $_POST['dob'] : "";
$blood_group = isset($_POST['blood_group']) ? $_POST['blood_group'] : "";
$gender = isset($_POST['gender']) ? $_POST['gender'] : "";
$address = isset($_POST['address']) ? trim($_POST['address']) : "";
$emergency_contact = isset($_POST['emergency_contact']) ? trim($_POST['emergency_contact']) : "";

// Validation
if ($name == "") {
    $errors[] = "Name is required.";
}

if ($email == "") {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if ($phone == "") {
    $errors[] = "Phone is required.";
}

if ($password == "") {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}

if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
}

if ($dob == "") {
    $errors[] = "Date of birth is required.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../view/hospital_patient/patientRegister.php");
    exit();
}

$conn = connect();

// Check if email exists
if (patientEmailExists($conn, $email)) {
    close($conn);
    $_SESSION['errors'] = ["Email already registered."];
    header("Location: ../view/hospital_patient/patientRegister.php");
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Prepare data
$data = [
    'name' => $name,
    'email' => $email,
    'password_hash' => $password_hash,
    'phone' => $phone,
    'dob' => $dob,
    'blood_group' => $blood_group,
    'gender' => $gender,
    'address' => $address,
    'emergency_contact' => $emergency_contact
];

// Register patient
if (registerPatient($conn, $data)) {
    close($conn);
    $_SESSION['success'] = "Registration successful! Please login to access your account.";
    header("Location: ../view/hospital_patient/patientRegister.php");
    exit();
} else {
    close($conn);
    $_SESSION['errors'] = ["Registration failed. Please try again."];
    header("Location: ../view/hospital_patient/patientRegister.php");
    exit();
}