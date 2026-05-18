<?php

/* ── FEATURE 6: Appointment Policies ───────────────────────────── */

function adminGetPolicies($conn)
{
    $sql = "SELECT * FROM appointment_policies LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return ['min_cancel_hours' => 24, 'max_advance_days' => 30, 'default_fee' => 500];
}

function adminSavePolicies($conn, $min_cancel, $max_advance, $default_fee)
{
    $min_cancel   = (int)$min_cancel;
    $max_advance  = (int)$max_advance;
    $default_fee  = (float)$default_fee;
    $sql = "SELECT id FROM appointment_policies LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id  = (int)$row['id'];
        return mysqli_query($conn, "UPDATE appointment_policies SET min_cancel_hours=$min_cancel, max_advance_days=$max_advance, default_fee=$default_fee WHERE id=$id");
    }
    return mysqli_query($conn, "INSERT INTO appointment_policies (min_cancel_hours, max_advance_days, default_fee) VALUES ($min_cancel, $max_advance, $default_fee)");
}

/* ── FEATURE 7: All Appointments ───────────────────────────────── */

function adminGetAllAppointments($conn, $filters = [])
{
    $where = "1=1";
    if (!empty($filters['doctor_id'])) {
        $did = (int)$filters['doctor_id'];
        $where .= " AND a.doctor_id = $did";
    }
    if (!empty($filters['date'])) {
        $d = mysqli_real_escape_string($conn, $filters['date']);
        $where .= " AND a.appointment_date = '$d'";
    }
    if (!empty($filters['status'])) {
        $s = mysqli_real_escape_string($conn, $filters['status']);
        $where .= " AND a.status = '$s'";
    }
    if (!empty($filters['booked_by'])) {
        $b = mysqli_real_escape_string($conn, $filters['booked_by']);
        $where .= " AND a.booked_by = '$b'";
    }
    $sql = "SELECT a.*, u_p.name AS patient_name, u_d.name AS doctor_name, s.name AS specialization
            FROM appointments a
            JOIN patients p  ON p.id  = a.patient_id
            JOIN users u_p   ON u_p.id = p.user_id
            JOIN doctors d   ON d.id  = a.doctor_id
            JOIN users u_d   ON u_d.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE $where
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminGetDoctorsSimple($conn)
{
    $sql = "SELECT d.id, u.name FROM doctors d JOIN users u ON u.id = d.user_id ORDER BY u.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ── FEATURE 8: Revenue Report ─────────────────────────────────── */

function adminGetRevenueReport($conn, $period = "month")
{
    if ($period == "day") {
        $group = "DATE(b.paid_at)";
        $label = "DATE(b.paid_at) AS period_label";
    } else if ($period == "week") {
        $group = "YEARWEEK(b.paid_at, 1)";
        $label = "CONCAT('Week ', WEEK(b.paid_at,1), ' ', YEAR(b.paid_at)) AS period_label";
    } else {
        $group = "DATE_FORMAT(b.paid_at, '%Y-%m')";
        $label = "DATE_FORMAT(b.paid_at, '%b %Y') AS period_label";
    }
    $sql = "SELECT $label, SUM(b.amount) AS total_revenue, COUNT(*) AS total_paid,
                   u.name AS doctor_name, s.name AS specialization
            FROM billing b
            JOIN appointments a ON a.id = b.appointment_id
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE b.payment_status = 'paid'
            GROUP BY $group, d.id
            ORDER BY b.paid_at DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ── FEATURE 9: Appointment Volume Report ──────────────────────── */

function adminGetVolumeReport($conn)
{
    $busyDoctors = [];
    $res = mysqli_query($conn, "SELECT u.name AS doctor_name, s.name AS specialization, COUNT(*) AS total
                                FROM appointments a JOIN doctors d ON d.id=a.doctor_id
                                JOIN users u ON u.id=d.user_id JOIN specializations s ON s.id=d.specialization_id
                                GROUP BY a.doctor_id ORDER BY total DESC LIMIT 10");
    if ($res) { while ($r = mysqli_fetch_assoc($res)) { $busyDoctors[] = $r; } }

    $busySpecs = [];
    $res2 = mysqli_query($conn, "SELECT s.name AS specialization, COUNT(*) AS total
                                 FROM appointments a JOIN doctors d ON d.id=a.doctor_id
                                 JOIN specializations s ON s.id=d.specialization_id
                                 GROUP BY d.specialization_id ORDER BY total DESC LIMIT 10");
    if ($res2) { while ($r = mysqli_fetch_assoc($res2)) { $busySpecs[] = $r; } }

    $peakDays = [];
    $res3 = mysqli_query($conn, "SELECT DAYNAME(appointment_date) AS day_name, COUNT(*) AS total
                                 FROM appointments GROUP BY day_name ORDER BY total DESC");
    if ($res3) { while ($r = mysqli_fetch_assoc($res3)) { $peakDays[] = $r; } }

    return ['doctors' => $busyDoctors, 'specializations' => $busySpecs, 'peak_days' => $peakDays];
}

/* ── FEATURE 10: Doctor Performance Report ─────────────────────── */

function adminGetDoctorPerformance($conn)
{
    $sql = "SELECT u.name AS doctor_name, s.name AS specialization,
                   COUNT(CASE WHEN a.status='completed' THEN 1 END) AS completed,
                   COUNT(CASE WHEN a.status='no_show'   THEN 1 END) AS no_shows,
                   COUNT(*) AS total,
                   ROUND(AVG(r.rating), 1) AS avg_rating,
                   COUNT(r.id) AS review_count
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            LEFT JOIN appointments a ON a.doctor_id = d.id
            LEFT JOIN doctor_reviews r ON r.doctor_id = d.id
            GROUP BY d.id
            ORDER BY completed DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* ── FEATURE 11: Complaints ────────────────────────────────────── */

function adminGetComplaints($conn)
{
    $sql = "SELECT c.*, u.name AS patient_name
            FROM complaints c
            JOIN users u ON u.id = c.patient_id
            ORDER BY c.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminResolveComplaint($conn, $complaint_id, $response)
{
    $complaint_id = (int)$complaint_id;
    $response     = mysqli_real_escape_string($conn, $response);
    $sql = "UPDATE complaints SET status='resolved', admin_response='$response', resolved_at=NOW() WHERE id=$complaint_id";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 12: Announcements ─────────────────────────────────── */

function adminGetAnnouncements($conn)
{
    $sql = "SELECT a.*, u.name AS author_name
            FROM announcements a
            JOIN users u ON u.id = a.author_id
            ORDER BY a.published_at DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminPostAnnouncement($conn, $admin_id, $title, $body, $target)
{
    $admin_id = (int)$admin_id;
    $title    = mysqli_real_escape_string($conn, $title);
    $body     = mysqli_real_escape_string($conn, $body);
    $target   = mysqli_real_escape_string($conn, $target);
    $sql = "INSERT INTO announcements (author_id, title, body, target_role, published_at)
            VALUES ($admin_id, '$title', '$body', '$target', NOW())";
    return mysqli_query($conn, $sql);
}

function adminDeleteAnnouncement($conn, $id)
{
    $id  = (int)$id;
    $sql = "DELETE FROM announcements WHERE id=$id";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 13: Billing Dashboard ─────────────────────────────── */

function adminGetBillingSummary($conn)
{
    $sql = "SELECT
                SUM(CASE WHEN payment_status='paid'    THEN amount ELSE 0 END) AS total_paid,
                SUM(CASE WHEN payment_status='pending' THEN amount ELSE 0 END) AS total_pending,
                COUNT(CASE WHEN payment_status='pending' AND paid_at IS NULL THEN 1 END) AS overdue_count
            FROM billing";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return ['total_paid' => 0, 'total_pending' => 0, 'overdue_count' => 0];
}

function adminGetAllBills($conn)
{
    $sql = "SELECT b.*, a.appointment_date, u_p.name AS patient_name, u_d.name AS doctor_name
            FROM billing b
            JOIN appointments a ON a.id = b.appointment_id
            JOIN patients p ON p.id = b.patient_id
            JOIN users u_p  ON u_p.id = p.user_id
            JOIN doctors d  ON d.id = a.doctor_id
            JOIN users u_d  ON u_d.id = d.user_id
            ORDER BY b.id DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}