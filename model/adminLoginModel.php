<?php

// ------------------------------------------------------------------
// adminLoginModel.php
// DB functions for admin authentication
// ------------------------------------------------------------------

function adminGetUserByEmailAndRole($conn, $email, $role)
{
    $email = mysqli_real_escape_string($conn, $email);
    $role  = mysqli_real_escape_string($conn, $role);
    $sql   = "SELECT * FROM users WHERE email = '$email' AND role = '$role' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
        
    }
    return null;
}

function adminUpdatePasswordHash($conn, $userId, $passwordHash)
{
    $userId       = (int)$userId;
    $passwordHash = mysqli_real_escape_string($conn, $passwordHash);
    $sql = "UPDATE users SET password_hash = '$passwordHash' WHERE id = $userId";
    return mysqli_query($conn, $sql);
}