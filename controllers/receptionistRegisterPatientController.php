<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] != "POST") { header("Location: ../view/hospital_receptionist/receptionistRegisterPatient.php"); exit(); }
$errors = [];
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : "";
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : "";
$phone   = isset($_POST['phone'])   ? trim($_POST['phone'])   : "";
$password = isset($_POST['password']) ? $_POST['password']    : "";
$dob     = isset($_POST['dob'])     ? $_POST['dob']           : "";
if ($name   == "") { $errors[] = "Name is required."; }
if ($email  == "") { $errors[] = "Email is required."; }
if ($phone  == "") { $errors[] = "Phone is required."; }
if ($password == "") { $errors[] = "Password is required."; }
if ($dob    == "") { $errors[] = "Date of birth is required."; }
if (!empty($errors)) { $_SESSION['errors'] = $errors; header("Location: ../view/hospital_receptionist/receptionistRegisterPatient.php"); exit(); }
$conn = connect();
if (receptionistEmailExists($conn, $email)) { close($conn); $_SESSION['errors'] = ["Email already exists."]; header("Location: ../view/hospital_receptionist/receptionistRegisterPatient.php"); exit(); }
$data = [
    'name' => $name, 'email' => $email, 'phone' => $phone, 'password' => $password,
    'dob' => $dob,
    'blood_group'            => isset($_POST['blood_group'])            ? $_POST['blood_group']            : "",
    'gender'                 => isset($_POST['gender'])                 ? $_POST['gender']                 : "",
    'address'                => isset($_POST['address'])                ? trim($_POST['address'])           : "",
    'emergency_contact_name' => isset($_POST['emergency_contact_name']) ? trim($_POST['emergency_contact_name']) : "",
    'emergency_contact_phone'=> isset($_POST['emergency_contact_phone'])? trim($_POST['emergency_contact_phone']):"",
];
$status = receptionistRegisterPatient($conn, $data);
close($conn);
if ($status) { $_SESSION['success'] = "Patient registered successfully."; } else { $_SESSION['errors'] = ["Registration failed. Please try again."]; }
header("Location: ../view/hospital_receptionist/receptionistRegisterPatient.php");
exit();