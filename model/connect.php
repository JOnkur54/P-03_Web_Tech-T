<?php

function connect()
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "hospital_db";
    $port = 3307;

    $conn = mysqli_connect($host, $user, $pass, $db, $port);

    if (!$conn) {
        die("Database Connection Failed : " . mysqli_connect_error());
    }

    return $conn;
}

function close($conn)
{
    mysqli_close($conn);
}

?>