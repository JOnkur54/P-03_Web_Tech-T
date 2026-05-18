<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = isset($patient['id']) ? $patient['id'] : null;

if (!$patient_id) {
    close($conn);
    $_SESSION['errors'] = ["Patient not found."];
    header("Location: ../view/hospital appointment booking/dependents.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| SHOW EDIT FORM
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == "edit_form" && isset($_GET['id'])) {
    
    $dependent_id = (int)$_GET['id'];
    $dependent = getDependentById($conn, $dependent_id, $patient_id);
    
    if (!$dependent) {
        close($conn);
        $_SESSION['errors'] = ["Dependent not found."];
        header("Location: ../view/hospital appointment booking/dependents.php");
        exit();
    }
    
    $dependents = getPatientDependents($conn, $patient_id);
    close($conn);
    
    $_SESSION['dependents'] = $dependents;
    $_SESSION['edit_dependent'] = $dependent;
    
    header("Location: ../view/hospital appointment booking/dependents.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| DELETE Dependent
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {

    $dependent_id = (int)$_GET['id'];

    if (removeDependent($conn, $dependent_id, $patient_id)) {
        $_SESSION['success'] = "Dependent removed successfully.";
    } else {
        $_SESSION['errors'] = ["Unable to delete dependent."];
    }

    $dependents = getPatientDependents($conn, $patient_id);
    close($conn);
    $_SESSION['dependents'] = $dependents;

    header("Location: ../view/hospital appointment booking/dependents.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| ADD or EDIT Dependent
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $action = isset($_POST['action']) ? $_POST['action'] : "add";
    
    $data = [
        'name' => isset($_POST['name']) ? trim($_POST['name']) : "",
        'dob' => isset($_POST['dob']) ? $_POST['dob'] : "",
        'relationship' => isset($_POST['relationship']) ? trim($_POST['relationship']) : "",
        'blood_group' => isset($_POST['blood_group']) ? trim($_POST['blood_group']) : ""
    ];

    if ($data['name'] == "") {
        $_SESSION['errors'] = ["Name is required."];
        
        $dependents = getPatientDependents($conn, $patient_id);
        close($conn);
        $_SESSION['dependents'] = $dependents;

        header("Location: ../view/hospital appointment booking/dependents.php");
        exit();
    }

    if ($action == "edit") {
        // UPDATE existing dependent
        $dependent_id = isset($_POST['dependent_id']) ? (int)$_POST['dependent_id'] : 0;
        
        if (updateDependent($conn, $dependent_id, $patient_id, $data)) {
            $_SESSION['success'] = "Dependent updated successfully.";
        } else {
            $_SESSION['errors'] = ["Unable to update dependent."];
        }
        
    } else {
        // ADD new dependent
        if (addDependent($conn, $patient_id, $data)) {
            $_SESSION['success'] = "Dependent added successfully.";
        } else {
            $_SESSION['errors'] = ["Unable to add dependent."];
        }
    }

    $dependents = getPatientDependents($conn, $patient_id);
    close($conn);
    $_SESSION['dependents'] = $dependents;

    header("Location: ../view/hospital appointment booking/dependents.php");
    exit();
}

$dependents = getPatientDependents($conn, $patient_id);
close($conn);
$_SESSION['dependents'] = $dependents;

header("Location: ../view/hospital appointment booking/dependents.php");
exit();