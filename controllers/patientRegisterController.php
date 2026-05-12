<?php

session_start();

require_once '../model/patientModel.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $name              = trim($_POST['name']);
    $email             = trim($_POST['email']);
    $password          = trim($_POST['password']);
    $confirm_password  = trim($_POST['confirm_password']);
    $phone             = trim($_POST['phone']);

    $dob               = $_POST['dob'];
    $blood_group       = $_POST['blood_group'];
    $gender            = $_POST['gender'];
    $address           = trim($_POST['address']);

    $emergency_name    = trim($_POST['emergency_name']);
    $emergency_phone   = trim($_POST['emergency_phone']);

    $medical_history   = trim($_POST['medical_history']);

    $errors = [];

    if(empty($name)){
        $errors[] = "Name Required";
    }

    if(empty($email)){
        $errors[] = "Email Required";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid Email Format";
    }

    if(patientEmailExists($email)){
        $errors[] = "Email Already Exists";
    }

    if(strlen($password) < 6){
        $errors[] = "Password Must Be Minimum 6 Characters";
    }

    if($password != $confirm_password){
        $errors[] = "Password And Confirm Password Not Matched";
    }

    $profile_pic = null;

    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['tmp_name'] != ""){

        $profile_pic = file_get_contents($_FILES['profile_pic']['tmp_name']);
    }

    if(count($errors) > 0){

        $_SESSION['errors'] = $errors;

        header("Location: ../view/hospital appointment booking/register.php");

        exit();
    }

    $data = [

        'name'              => $name,
        'email'             => $email,
        'password'          => password_hash($password, PASSWORD_DEFAULT),
        'phone'             => $phone,
        'role'              => 'patient',
        'profile_pic'       => $profile_pic,

        'dob'               => $dob,
        'blood_group'       => $blood_group,
        'gender'            => $gender,
        'address'           => $address,

        'emergency_name'    => $emergency_name,
        'emergency_phone'   => $emergency_phone,

        'medical_history'   => $medical_history
    ];

    $status = registerPatient($data);

    if($status){

        $_SESSION['success'] = "Registration Successful";

        header("Location: ../view/hospital appointment booking/register.php");

    }else{

        $_SESSION['errors'][] = "Registration Failed";

        header("Location: ../view/hospital appointment booking/register.php");
    }
}
?>