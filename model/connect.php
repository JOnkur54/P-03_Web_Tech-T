<?php

$host = "mysql:3306";
$user = "admin";
$pass = "apass";
$db   = "hospital_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Database Connection Failed : " . mysqli_connect_error());
}

function close($conn)
{
    mysqli_close($conn);
}

?>