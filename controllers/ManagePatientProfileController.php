<?php
require_once '../model/connect.php';
require_once '../model/PatientModel.php';
require_once '../model/Close.php';
if (session_status() === PHP_SESSION_NONE) if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION['error'] = '';
$_SESSION['msg'] = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = htmlspecialchars($_POST['patient_id']);
    $name = htmlspecialchars($_POST['first_name'] . ' ' . $_POST['last_name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    if ($patientId === '' || $name === '' || $phone === '') {
        $_SESSION['error'] = 'Patient ID, name and phone are required to update a profile.';
    } else {
        $conn = Connect();
        $nameClean = mysqli_real_escape_string($conn, $name);
        $phoneClean = mysqli_real_escape_string($conn, $phone);
        $emailClean = mysqli_real_escape_string($conn, $email);
        $addressClean = mysqli_real_escape_string($conn, $address);
        $idClean = mysqli_real_escape_string($conn, $patientId);

        $sql = "UPDATE users SET name = '$nameClean', phone = '$phoneClean', email = '$emailClean' WHERE id = '$idClean'";
        mysqli_query($conn, $sql);

        $checkSql = "SELECT id FROM patients WHERE user_id = '$idClean' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkSql);
        if (mysqli_num_rows($checkResult) > 0) {
            $sql2 = "UPDATE patients SET address = '$addressClean' WHERE user_id = '$idClean'";
        } else {
            $sql2 = "INSERT INTO patients (user_id, address, created_at) VALUES ('$idClean', '$addressClean', NOW())";
        }
        mysqli_query($conn, $sql2);
        close($conn);
        $_SESSION['msg'] = 'Patient profile updated successfully.';
    }
    header('Location: ../view/receptionist_profile.php');
    exit;
}
