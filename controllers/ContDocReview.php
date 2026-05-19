<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate saving reply
    // Since we cannot modify the schema to add reply_text, we just simulate success.
    
    header("Location: ../view/ViewDocReviews.php?success=reply_simulated");
    exit;
} else {
    header("Location: ../view/ViewDocReviews.php");
    exit;
}
?>
