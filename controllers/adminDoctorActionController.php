<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminDoctorModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : "";

if ($action == "add") {

    $errors = [];
    if ($_POST['name'] == "")            { $errors[] = "Name is required."; }
    if ($_POST['email'] == "")           { $errors[] = "Email is required."; }
    if ($_POST['password'] == "")        { $errors[] = "Password is required."; }
    if ($_POST['specialization_id'] == "") { $errors[] = "Specialization is required."; }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../controllers/adminManageDoctorsController.php");
        exit();
    }

    $conn   = connect();
    $status = adminAddDoctor($conn, $_POST);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Doctor added successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to add doctor. Email may already exist."];
    }

    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

if ($action == "edit") {

    $conn   = connect();
    $status = adminUpdateDoctor($conn, $_POST);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Doctor updated successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to update doctor."];
    }

    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

if ($action == "approve") {

    $doctor_id = (int)$_POST['doctor_id'];
    $conn      = connect();
    $status    = adminApproveDoctorById($conn, $doctor_id);
    close($conn);

    $_SESSION['success'] = $status ? "Doctor approved." : "Action failed.";
    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

if ($action == "reject") {

    $doctor_id = (int)$_POST['doctor_id'];
    $conn      = connect();
    $status    = adminRejectDoctorById($conn, $doctor_id);
    close($conn);

    $_SESSION['success'] = $status ? "Doctor rejected and deactivated." : "Action failed.";
    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

if ($action == "deactivate") {

    $doctor_id = (int)$_POST['doctor_id'];
    $conn      = connect();
    $status    = adminDeactivateDoctorById($conn, $doctor_id);
    close($conn);

    $_SESSION['success'] = $status ? "Doctor deactivated." : "Action failed.";
    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

if ($action == "activate") {

    $doctor_id = (int)$_POST['doctor_id'];
    $conn      = connect();
    $status    = adminActivateDoctorById($conn, $doctor_id);
    close($conn);

    $_SESSION['success'] = $status ? "Doctor activated." : "Action failed.";
    header("Location: ../controllers/adminManageDoctorsController.php");
    exit();
}

header("Location: ../controllers/adminManageDoctorsController.php");
exit();