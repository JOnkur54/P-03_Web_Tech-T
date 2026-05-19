<?php
session_start();
require_once "../model/connect.php";
require_once "../model/adminReportsModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: ../view/hospital_admin/adminLogin.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] != "POST") { header("Location: ../controllers/adminAnnouncementsController.php"); exit(); }

$action = isset($_POST['action']) ? $_POST['action'] : "";

if ($action == "post") {
    $title  = trim(isset($_POST['title'])  ? $_POST['title']  : "");
    $body   = trim(isset($_POST['body'])   ? $_POST['body']   : "");
    $target = trim(isset($_POST['target']) ? $_POST['target'] : "all");

    if ($title == "" || $body == "") {
        $_SESSION['errors'] = ["Title and body are required."];
        header("Location: ../controllers/adminAnnouncementsController.php");
        exit();
    }

    $conn   = connect();
    $status = adminPostAnnouncement($conn, $_SESSION['admin_id'], $title, $body, $target);
    close($conn);

    $_SESSION['success'] = $status ? "Announcement posted." : "Failed to post.";
    header("Location: ../controllers/adminAnnouncementsController.php");
    exit();
}

if ($action == "delete") {
    $id   = isset($_POST['announcement_id']) ? (int)$_POST['announcement_id'] : 0;
    $conn = connect();
    adminDeleteAnnouncement($conn, $id);
    close($conn);
    $_SESSION['success'] = "Announcement deleted.";
    header("Location: ../controllers/adminAnnouncementsController.php");
    exit();
}

header("Location: ../controllers/adminAnnouncementsController.php");
exit();