<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$conn = connect();
$_SESSION['waiting_room'] = receptionistGetWaitingRoom($conn);
close($conn);
header("Location: ../view/hospital_receptionist/receptionistWaitingRoom.php");
exit();