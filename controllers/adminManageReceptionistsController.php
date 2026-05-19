<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminStaffPatientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../controllers/adminManageReceptionistsController.php");
    exit();
}

$action = isset($_POST['action']) ? trim($_POST['action']) : "";
$conn   = connect();

/* ── ADD ─────────────────────────────────────────────────────────── */
if ($action == "add") {

    $name     = isset($_POST['name'])     ? trim($_POST['name'])     : "";
    $email    = isset($_POST['email'])    ? trim($_POST['email'])    : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $phone    = isset($_POST['phone'])    ? trim($_POST['phone'])    : "";

    if ($name == "" || $email == "" || $password == "") {
        close($conn);
        $_SESSION['errors'] = ["Name, email and password are required."];
        header("Location: ../controllers/adminManageReceptionistsController.php");
        exit();
    }

    $data   = ['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone];
    $status = adminAddReceptionist($conn, $data);

    if ($status) {
        $_SESSION['success'] = "Receptionist '$name' added successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to add receptionist. Email may already be in use."];
    }
}

/* ── EDIT ────────────────────────────────────────────────────────── */
if ($action == "edit") {

    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $name    = isset($_POST['name'])    ? trim($_POST['name'])    : "";
    $email   = isset($_POST['email'])   ? trim($_POST['email'])   : "";
    $phone   = isset($_POST['phone'])   ? trim($_POST['phone'])   : "";

    if ($user_id == 0 || $name == "" || $email == "") {
        close($conn);
        $_SESSION['errors'] = ["Name and email are required."];
        header("Location: ../controllers/adminManageReceptionistsController.php");
        exit();
    }

    $status = adminUpdateReceptionist($conn, $user_id, $name, $email, $phone);
    $_SESSION['success'] = $status ? "Receptionist updated." : "Update failed.";
}

/* ── DEACTIVATE ──────────────────────────────────────────────────── */
if ($action == "deactivate") {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    if ($user_id > 0) {
        adminToggleReceptionistStatus($conn, $user_id, 0);
        $_SESSION['success'] = "Receptionist deactivated.";
    }
}

/* ── ACTIVATE ────────────────────────────────────────────────────── */
if ($action == "activate") {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    if ($user_id > 0) {
        adminToggleReceptionistStatus($conn, $user_id, 1);
        $_SESSION['success'] = "Receptionist activated.";
    }
}

close($conn);
header("Location: ../controllers/adminManageReceptionistsController.php");
exit();