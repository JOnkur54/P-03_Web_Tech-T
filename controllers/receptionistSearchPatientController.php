<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$view_patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
$conn = connect();
$_SESSION['search_results'] = $search != "" ? receptionistSearchPatients($conn, $search) : [];
$_SESSION['search_query']   = $search;
$_SESSION['view_patient']   = null;
$_SESSION['view_upcoming']  = [];
$_SESSION['view_billing']   = [];
if ($view_patient_id > 0) {
    $_SESSION['view_patient']  = receptionistGetPatientByPatientId($conn, $view_patient_id);
    $_SESSION['view_upcoming'] = receptionistGetPatientUpcomingAppointments($conn, $view_patient_id);
    $_SESSION['view_billing']  = receptionistGetPatientBilling($conn, $view_patient_id);
}
close($conn);
header("Location: ../view/hospital_receptionist/receptionistSearchPatient.php");
exit();