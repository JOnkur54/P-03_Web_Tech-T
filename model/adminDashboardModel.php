<?php

// ------------------------------------------------------------------
// adminDashboardModel.php
// All database queries needed for the Admin Dashboard (Feature 1)
// Follows wt-sample-code pattern: plain functions, mysqli, no OOP
// ------------------------------------------------------------------

/* ── Today's total appointments ─────────────────────────────────── */
function adminGetTodayAppointmentCount($conn)
{
    $today = date('Y-m-d');
    $sql   = "SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = '$today'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}

/* ── Total registered patients ──────────────────────────────────── */
function adminGetTotalPatients($conn)
{
    $sql    = "SELECT COUNT(*) AS total FROM users WHERE role = 'patient' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}

/* ── Total active doctors ───────────────────────────────────────── */
function adminGetTotalActiveDoctors($conn)
{
    $sql    = "SELECT COUNT(*) AS total FROM doctors d JOIN users u ON u.id = d.user_id WHERE d.is_approved = 1 AND u.is_active = 1";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}

/* ── Total pending billings ─────────────────────────────────────── */
function adminGetTotalPendingBillings($conn)
{
    $sql    = "SELECT COUNT(*) AS total FROM billing WHERE payment_status = 'pending'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}

/* ── Today's appointments list (for dashboard table) ────────────── */
function adminGetTodayAppointments($conn)
{
    $today = date('Y-m-d');
    $sql   = "SELECT a.id, a.appointment_time, a.status, a.booked_by,
                     u_p.name AS patient_name,
                     u_d.name AS doctor_name,
                     s.name   AS specialization
              FROM appointments a
              JOIN patients p  ON p.id  = a.patient_id
              JOIN users u_p   ON u_p.id = p.user_id
              JOIN doctors d   ON d.id  = a.doctor_id
              JOIN users u_d   ON u_d.id = d.user_id
              JOIN specializations s ON s.id = d.specialization_id
              WHERE a.appointment_date = '$today'
              ORDER BY a.appointment_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows   = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ── Recent 5 registrations (patients) ─────────────────────────── */
function adminGetRecentPatients($conn)
{
    $sql    = "SELECT u.name, u.email, u.created_at
               FROM users u
               WHERE u.role = 'patient'
               ORDER BY u.created_at DESC
               LIMIT 5";
    $result = mysqli_query($conn, $sql);
    $rows   = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ── Pending doctor approvals count ────────────────────────────── */
function adminGetPendingDoctorCount($conn)
{
    $sql    = "SELECT COUNT(*) AS total FROM doctors WHERE is_approved = 0";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}