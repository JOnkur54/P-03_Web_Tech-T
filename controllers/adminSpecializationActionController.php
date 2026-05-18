<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminSpecializationModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../controllers/adminManageSpecializationsController.php");
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : "";

if ($action == "add") {

    $name = trim(isset($_POST['name']) ? $_POST['name'] : "");
    $desc = trim(isset($_POST['description']) ? $_POST['description'] : "");

    if ($name == "") {
        $_SESSION['errors'] = ["Specialization name is required."];
        header("Location: ../controllers/adminManageSpecializationsController.php");
        exit();
    }

    $conn   = connect();
    $status = adminAddSpecialization($conn, $name, $desc);
    close($conn);

    $_SESSION['success'] = $status ? "Specialization added." : "Failed. Name may already exist.";
    header("Location: ../controllers/adminManageSpecializationsController.php");
    exit();
}

if ($action == "edit") {

    $id   = isset($_POST['spec_id']) ? (int)$_POST['spec_id'] : 0;
    $name = trim(isset($_POST['name']) ? $_POST['name'] : "");
    $desc = trim(isset($_POST['description']) ? $_POST['description'] : "");

    if ($name == "") {
        $_SESSION['errors'] = ["Specialization name is required."];
        header("Location: ../controllers/adminManageSpecializationsController.php");
        exit();
    }

    $conn   = connect();
    $status = adminUpdateSpecialization($conn, $id, $name, $desc);
    close($conn);

    $_SESSION['success'] = $status ? "Specialization updated." : "Update failed.";
    header("Location: ../controllers/adminManageSpecializationsController.php");
    exit();
}

if ($action == "delete") {

    $id   = isset($_POST['spec_id']) ? (int)$_POST['spec_id'] : 0;
    $conn = connect();
    $status = adminDeleteSpecialization($conn, $id);
    close($conn);

    $_SESSION['success'] = $status ? "Specialization deleted." : "Cannot delete — doctors are assigned to it.";
    header("Location: ../controllers/adminManageSpecializationsController.php");
    exit();
}

header("Location: ../controllers/adminManageSpecializationsController.php");
exit();