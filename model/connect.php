<?php

function connect()
{
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "hospital_db";
    $port       = 3307;

    $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
?>