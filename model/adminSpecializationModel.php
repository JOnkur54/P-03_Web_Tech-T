<?php

function adminGetSpecializations($conn)
{
    $sql = "SELECT s.id, s.name, s.description,
                   COUNT(d.id) AS doctor_count
            FROM specializations s
            LEFT JOIN doctors d ON d.specialization_id = s.id
            GROUP BY s.id
            ORDER BY s.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminAddSpecialization($conn, $name, $description)
{
    $name        = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $sql = "INSERT INTO specializations (name, description) VALUES ('$name', '$description')";
    return mysqli_query($conn, $sql);
}

function adminUpdateSpecialization($conn, $id, $name, $description)
{
    $id          = (int)$id;
    $name        = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $sql = "UPDATE specializations SET name='$name', description='$description' WHERE id=$id";
    return mysqli_query($conn, $sql);
}

function adminDeleteSpecialization($conn, $id)
{
    $id  = (int)$id;
    $sql = "DELETE FROM specializations WHERE id=$id";
    return mysqli_query($conn, $sql);
}