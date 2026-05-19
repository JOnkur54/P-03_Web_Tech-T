<?php

/* ================================================================
   receptionistModel.php  -  All DB functions for Receptionist role
   Follows wt-sample pattern: plain functions, mysqli, no OOP
================================================================ */

/* ── AUTH ───────────────────────────────────────────────────────── */

function receptionistGetUserByEmail($conn, $email)
{
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'receptionist' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function receptionistUpdatePasswordHash($conn, $user_id, $hash)
{
    $user_id = (int)$user_id;
    $hash = mysqli_real_escape_string($conn, $hash);
    return mysqli_query($conn, "UPDATE users SET password_hash = '$hash' WHERE id = $user_id");
}

/* ── FEATURE 1 & 2: Today appointments & profile data ──────────── */

function receptionistGetTodayAppointments($conn, $status_filter = "")
{
    $today = date('Y-m-d');
    $where = "a.appointment_date = '$today'";
    if ($status_filter != "") {
        $sf = mysqli_real_escape_string($conn, $status_filter);
        $where .= " AND a.status = '$sf'";
    }
    $sql = "SELECT a.id, a.appointment_time, a.status, a.reason, a.booked_by,
                   u_p.name AS patient_name, u_p.phone AS patient_phone,
                   u_d.name AS doctor_name, s.name AS specialization
            FROM appointments a
            JOIN patients p  ON p.id  = a.patient_id
            JOIN users u_p   ON u_p.id = p.user_id
            JOIN doctors d   ON d.id  = a.doctor_id
            JOIN users u_d   ON u_d.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE $where
            ORDER BY u_d.name ASC, a.appointment_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; }
    }
    return $rows;
}

/* ── FEATURE 2: Search patients ─────────────────────────────────── */

function receptionistSearchPatients($conn, $search)
{
    $s = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT u.id AS user_id, u.name, u.email, u.phone, u.is_active,
                   p.id AS patient_id, p.gender, p.blood_group, p.date_of_birth
            FROM users u
            JOIN patients p ON p.user_id = u.id
            WHERE u.role = 'patient'
            AND (u.name LIKE '%$s%' OR u.phone LIKE '%$s%' OR p.id = '$s')
            ORDER BY u.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; }
    }
    return $rows;
}

function receptionistGetPatientByPatientId($conn, $patient_id)
{
    $patient_id = (int)$patient_id;
    $sql = "SELECT p.*, u.name, u.email, u.phone, u.is_active
            FROM patients p JOIN users u ON u.id = p.user_id
            WHERE p.id = $patient_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) { return mysqli_fetch_assoc($result); }
    return null;
}

function receptionistGetPatientUpcomingAppointments($conn, $patient_id)
{
    $patient_id = (int)$patient_id;
    $today = date('Y-m-d');
    $sql = "SELECT a.*, u.name AS doctor_name, s.name AS specialization
            FROM appointments a
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE a.patient_id = $patient_id AND a.appointment_date >= '$today'
            AND a.status NOT IN ('cancelled','no_show')
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

function receptionistGetPatientBilling($conn, $patient_id)
{
    $patient_id = (int)$patient_id;
    $sql = "SELECT b.*, a.appointment_date, u.name AS doctor_name
            FROM billing b
            JOIN appointments a ON a.id = b.appointment_id
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users u ON u.id = d.user_id
            WHERE b.patient_id = $patient_id
            ORDER BY b.id DESC LIMIT 5";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

/* ── FEATURE 3: Register patient ────────────────────────────────── */

function receptionistEmailExists($conn, $email)
{
    $email = mysqli_real_escape_string($conn, $email);
    $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
    return $result && mysqli_num_rows($result) > 0;
}

function receptionistRegisterPatient($conn, $data)
{
    mysqli_begin_transaction($conn);

    $name     = mysqli_real_escape_string($conn, $data['name']);
    $email    = mysqli_real_escape_string($conn, $data['email']);
    $phone    = mysqli_real_escape_string($conn, $data['phone']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active)
            VALUES ('$name', '$email', '$password', '$phone', 'patient', 1)";
    if (!mysqli_query($conn, $sql)) { mysqli_rollback($conn); return false; }

    $user_id   = mysqli_insert_id($conn);
    $dob       = mysqli_real_escape_string($conn, $data['dob']);
    $bg        = mysqli_real_escape_string($conn, $data['blood_group']);
    $gender    = mysqli_real_escape_string($conn, $data['gender']);
    $address   = mysqli_real_escape_string($conn, $data['address']);
    $emg_name  = mysqli_real_escape_string($conn, $data['emergency_contact_name']);
    $emg_phone = mysqli_real_escape_string($conn, $data['emergency_contact_phone']);

    $sql = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, emergency_contact_name, emergency_contact_phone)
            VALUES ($user_id, '$dob', '$bg', '$gender', '$address', '$emg_name', '$emg_phone')";
    if (!mysqli_query($conn, $sql)) { mysqli_rollback($conn); return false; }

    mysqli_commit($conn);
    return true;
}

/* ── FEATURE 4: Book walk-in appointment ────────────────────────── */

function receptionistGetApprovedDoctors($conn)
{
    $sql = "SELECT d.id, u.name AS doctor_name, d.consultation_fee, s.name AS specialization
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE d.is_approved = 1 AND u.is_active = 1
            ORDER BY u.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

function receptionistGetAllPatients($conn)
{
    $sql = "SELECT p.id AS patient_id, u.name, u.phone
            FROM patients p JOIN users u ON u.id = p.user_id
            WHERE u.is_active = 1 ORDER BY u.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

function receptionistGetAvailableSlots($conn, $doctor_id, $date)
{
    $doctor_id = (int)$doctor_id;
    $date = mysqli_real_escape_string($conn, $date);
    $day = date('l', strtotime($date));

    $result = mysqli_query($conn, "SELECT start_time, end_time, slot_duration_minutes
        FROM doctor_availability WHERE doctor_id = $doctor_id AND day_of_week = '$day' AND is_available = 1 LIMIT 1");
    if (!$result || mysqli_num_rows($result) == 0) { return []; }
    $row = mysqli_fetch_assoc($result);

    $booked_result = mysqli_query($conn, "SELECT appointment_time FROM appointments
        WHERE doctor_id = $doctor_id AND appointment_date = '$date' AND status NOT IN ('cancelled','no_show')");
    $booked = [];
    if ($booked_result) {
        while ($b = mysqli_fetch_assoc($booked_result)) { $booked[] = $b['appointment_time']; }
    }

    $slots = [];
    $cur = strtotime($row['start_time']);
    $end = strtotime($row['end_time']);
    $dur = (int)$row['slot_duration_minutes'];
    while ($cur < $end) {
        $ts = date('H:i:s', $cur);
        if (!in_array($ts, $booked)) {
            $slots[] = ['time' => $ts, 'label' => date('h:i A', $cur)];
        }
        $cur = strtotime("+{$dur} minutes", $cur);
    }
    return $slots;
}

function receptionistBookWalkIn($conn, $data)
{
    $patient_id = (int)$data['patient_id'];
    $doctor_id  = (int)$data['doctor_id'];
    $date       = mysqli_real_escape_string($conn, $data['appointment_date']);
    $time       = mysqli_real_escape_string($conn, $data['appointment_time']);
    $reason     = mysqli_real_escape_string($conn, $data['reason']);
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by)
            VALUES ($patient_id, $doctor_id, '$date', '$time', '$reason', 'confirmed', 'receptionist')";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 5: Check in ────────────────────────────────────────── */

function receptionistSearchTodayAppointments($conn, $search)
{
    $today = date('Y-m-d');
    $s = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT a.id, a.doctor_id, a.appointment_date, a.appointment_time, a.status,
                   u_p.name AS patient_name, u_p.phone AS patient_phone,
                   u_d.name AS doctor_name
            FROM appointments a
            JOIN patients p ON p.id = a.patient_id
            JOIN users u_p  ON u_p.id = p.user_id
            JOIN doctors d  ON d.id = a.doctor_id
            JOIN users u_d  ON u_d.id = d.user_id
            WHERE a.appointment_date = '$today'
            AND (u_p.name LIKE '%$s%' OR u_p.phone LIKE '%$s%' OR a.id = '$s')
            ORDER BY a.appointment_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

function receptionistCheckIn($conn, $appointment_id)
{
    $appointment_id = (int)$appointment_id;
    $sql = "UPDATE appointments SET status = 'checked_in'
            WHERE id = $appointment_id AND status IN ('pending','confirmed')";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 6: Waiting room ────────────────────────────────────── */

function receptionistGetWaitingRoom($conn)
{
    $today = date('Y-m-d');
    $sql = "SELECT a.id, a.appointment_time,
                   u_p.name AS patient_name, u_p.phone AS patient_phone,
                   u_d.name AS doctor_name, s.name AS specialization
            FROM appointments a
            JOIN patients p ON p.id = a.patient_id
            JOIN users u_p  ON u_p.id = p.user_id
            JOIN doctors d  ON d.id = a.doctor_id
            JOIN users u_d  ON u_d.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE a.appointment_date = '$today' AND a.status = 'checked_in'
            ORDER BY u_d.name ASC, a.appointment_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

/* ── FEATURE 7: Process payment ─────────────────────────────────── */

function receptionistGetPendingBills($conn)
{
    $sql = "SELECT b.id AS bill_id, b.amount, a.id AS appointment_id,
                   a.appointment_date, a.appointment_time,
                   u_p.name AS patient_name, u_d.name AS doctor_name
            FROM billing b
            JOIN appointments a ON a.id = b.appointment_id
            JOIN patients p ON p.id = b.patient_id
            JOIN users u_p  ON u_p.id = p.user_id
            JOIN doctors d  ON d.id = a.doctor_id
            JOIN users u_d  ON u_d.id = d.user_id
            WHERE b.payment_status = 'pending' AND a.status = 'completed'
            ORDER BY a.appointment_date DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

function receptionistMarkBillPaid($conn, $bill_id, $payment_method)
{
    $bill_id        = (int)$bill_id;
    $payment_method = mysqli_real_escape_string($conn, $payment_method);
    $sql = "UPDATE billing SET payment_status = 'paid', payment_method = '$payment_method', paid_at = NOW()
            WHERE id = $bill_id";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 8: Receipt ─────────────────────────────────────────── */

function receptionistGetBillById($conn, $bill_id)
{
    $bill_id = (int)$bill_id;
    $sql = "SELECT b.*, a.appointment_date, a.appointment_time,
                   u_p.name AS patient_name, u_p.phone AS patient_phone,
                   u_d.name AS doctor_name, s.name AS specialization
            FROM billing b
            JOIN appointments a ON a.id = b.appointment_id
            JOIN patients p ON p.id = b.patient_id
            JOIN users u_p ON u_p.id = p.user_id
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users u_d ON u_d.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE b.id = $bill_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) { return mysqli_fetch_assoc($result); }
    return null;
}

/* ── FEATURE 9: Cancel / Reschedule ─────────────────────────────── */

function receptionistGetAppointmentById($conn, $appointment_id)
{
    $appointment_id = (int)$appointment_id;
    $sql = "SELECT a.*, u_p.name AS patient_name, u_d.name AS doctor_name
            FROM appointments a
            JOIN patients p ON p.id = a.patient_id
            JOIN users u_p  ON u_p.id = p.user_id
            JOIN doctors d  ON d.id = a.doctor_id
            JOIN users u_d  ON u_d.id = d.user_id
            WHERE a.id = $appointment_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) { return mysqli_fetch_assoc($result); }
    return null;
}

function receptionistCancelAppointment($conn, $appointment_id)
{
    $appointment_id = (int)$appointment_id;
    return mysqli_query($conn, "UPDATE appointments SET status = 'cancelled' WHERE id = $appointment_id");
}

function receptionistRescheduleAppointment($conn, $appointment_id, $new_date, $new_time)
{
    $appointment_id = (int)$appointment_id;
    $new_date = mysqli_real_escape_string($conn, $new_date);
    $new_time = mysqli_real_escape_string($conn, $new_time);
    $sql = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time', status = 'confirmed'
            WHERE id = $appointment_id";
    return mysqli_query($conn, $sql);
}

/* ── FEATURE 10: Doctor availability for a date ─────────────────── */

function receptionistGetDoctorAvailabilityForDate($conn, $date)
{
    $date = mysqli_real_escape_string($conn, $date);
    $day  = date('l', strtotime($date));
    $sql = "SELECT d.id AS doctor_id, u.name AS doctor_name, s.name AS specialization,
                   da.start_time, da.end_time, da.slot_duration_minutes,
                   (SELECT COUNT(*) FROM appointments a
                    WHERE a.doctor_id = d.id AND a.appointment_date = '$date'
                    AND a.status NOT IN ('cancelled','no_show')) AS booked_count
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            JOIN doctor_availability da ON da.doctor_id = d.id
            WHERE da.day_of_week = '$day' AND da.is_available = 1
            AND d.is_approved = 1 AND u.is_active = 1
            AND d.id NOT IN (SELECT doctor_id FROM leave_dates WHERE leave_date = '$date')
            ORDER BY da.start_time ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result) { while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; } }
    return $rows;
}

/* ── FEATURE 11: Daily summary report ───────────────────────────── */

function receptionistGetDailySummary($conn, $date)
{
    $date = mysqli_real_escape_string($conn, $date);
    $sql = "SELECT COUNT(*) AS total_appointments,
                   COUNT(CASE WHEN status='checked_in'  THEN 1 END) AS total_checkins,
                   COUNT(CASE WHEN status='completed'   THEN 1 END) AS total_completed,
                   COUNT(CASE WHEN status='cancelled'   THEN 1 END) AS total_cancelled,
                   COUNT(CASE WHEN status='no_show'     THEN 1 END) AS total_noshows
            FROM appointments WHERE appointment_date = '$date'";
    $result = mysqli_query($conn, $sql);
    $summary = ['total_appointments'=>0,'total_checkins'=>0,'total_completed'=>0,'total_cancelled'=>0,'total_noshows'=>0,'revenue'=>0];
    if ($result && $row = mysqli_fetch_assoc($result)) { $summary = array_merge($summary, $row); }

    $rev = mysqli_query($conn, "SELECT COALESCE(SUM(b.amount),0) AS revenue
        FROM billing b JOIN appointments a ON a.id = b.appointment_id
        WHERE a.appointment_date = '$date' AND b.payment_status = 'paid'");
    if ($rev && $r = mysqli_fetch_assoc($rev)) { $summary['revenue'] = $r['revenue']; }

    return $summary;
}