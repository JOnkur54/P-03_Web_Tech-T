<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/PatientLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../view/hospital_patient/patientManageProfile.php");
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : "";

/*
|--------------------------------------------------------------------------
| ACTION 1: Upload Profile Picture
|--------------------------------------------------------------------------
*/
if ($action == "upload_picture") {

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['tmp_name'] != "") {
        $uploadDir = "../view/profilePicture/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExt = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $fileName = "user_" . $_SESSION['patient_id'] . "." . $fileExt;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filePath)) {
            $conn = connect();
            updateUserProfilePicturePath($conn, $_SESSION['patient_id'], $fileName);
            close($conn);
            $_SESSION['success'] = "Profile picture uploaded successfully.";
        } else {
            $_SESSION['errors'] = ["Failed to upload picture."];
        }
    } else {
        $_SESSION['errors'] = ["Please select a picture to upload."];
    }

    $conn = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);
    close($conn);
    $_SESSION['patient_profile'] = $patient;

    header("Location: ../view/hospital_patient/patientManageProfile.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| ACTION 2: Update Personal Information
|--------------------------------------------------------------------------
*/
if ($action == "update_info") {

    $data = [
        'user_id' => $_SESSION['patient_id'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'dob' => $_POST['dob'],
        'blood_group' => $_POST['blood_group'],
        'gender' => $_POST['gender'],
        'address' => $_POST['address'],
        'emergency_name' => $_POST['emergency_name'],
        'emergency_phone' => $_POST['emergency_phone']
    ];

    $conn = connect();
    $status = updatePatientProfile($conn, $data);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Information updated successfully.";
    } else {
        $_SESSION['errors'] = ["Unable to update information."];
    }

    $conn = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);
    close($conn);
    $_SESSION['patient_profile'] = $patient;

    header("Location: ../view/hospital_patient/patientManageProfile.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| ACTION 3: Change Password
|--------------------------------------------------------------------------
*/
if ($action == "change_password") {

    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword != $confirmPassword) {
        $_SESSION['errors'] = ["Passwords do not match."];
        
        $conn = connect();
        $patient = getPatientByUserId($conn, $_SESSION['patient_id']);
        close($conn);
        $_SESSION['patient_profile'] = $patient;

        header("Location: ../view/hospital_patient/patientManageProfile.php");
        exit();
    }

    $conn = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);
    $user = getPatientByEmailAndRole($conn, $patient['email'], 'patient');

    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        close($conn);
        $_SESSION['errors'] = ["Current password is incorrect."];
        
        $_SESSION['patient_profile'] = $patient;
        header("Location: ../view/hospital_patient/patientManageProfile.php");
        exit();
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    updateUserPasswordHash($conn, $_SESSION['patient_id'], $newHash);
    close($conn);

    $_SESSION['success'] = "Password changed successfully.";
    $_SESSION['patient_profile'] = $patient;

    header("Location: ../view/hospital_patient/patientManageProfile.php");
    exit();
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
close($conn);
$_SESSION['patient_profile'] = $patient;

header("Location: ../view/hospital_patient/patientManageProfile.php");
exit();