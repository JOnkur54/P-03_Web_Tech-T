<?php
require_once 'connect.php';

function patientEmailExists($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

function getPatientByEmailAndRole($conn, $email, $role = 'patient') {
    $email = mysqli_real_escape_string($conn, $email);
    $role = mysqli_real_escape_string($conn, $role);
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = '$role' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function updateUserPasswordHash($conn, $userId, $passwordHash) {
    $userId = (int)$userId;
    $passwordHash = mysqli_real_escape_string($conn, $passwordHash);
    $sql = "UPDATE users SET password_hash = '$passwordHash' WHERE id = $userId";
    return mysqli_query($conn, $sql);
}

function markBillingPaid($conn, $billId) {
    $billId = (int)$billId;
    $sql = "UPDATE billing SET payment_status = 'paid', paid_at = NOW() WHERE id = $billId";
    return mysqli_query($conn, $sql);
}

function registerPatient($conn, $data) {
    mysqli_begin_transaction($conn);

    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $role = mysqli_real_escape_string($conn, $data['role']);
    $profile_pic = $data['profile_pic'];

    $sql = "INSERT INTO users (name, email, password_hash, phone, role, profile_pic) VALUES ('$name', '$email', '$password', '$phone', '$role', '$profile_pic')";
    $status = mysqli_query($conn, $sql);

    if (!$status) {
        mysqli_rollback($conn);
        return false;
    }

    $user_id = mysqli_insert_id($conn);

    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $blood_group = mysqli_real_escape_string($conn, $data['blood_group']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $emergency_name = mysqli_real_escape_string($conn, $data['emergency_name']);
    $emergency_phone = mysqli_real_escape_string($conn, $data['emergency_phone']);
    $medical_history = mysqli_real_escape_string($conn, $data['medical_history']);

    $sql = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, emergency_contact_name, emergency_contact_phone, medical_history_notes) VALUES ($user_id, '$dob', '$blood_group', '$gender', '$address', '$emergency_name', '$emergency_phone', '$medical_history')";
    $status = mysqli_query($conn, $sql);

    if ($status) {
        mysqli_commit($conn);
        return true;
    }

    mysqli_rollback($conn);
    return false;
}

function getPatientByUserId($conn, $user_id) {
    $user_id = (int)$user_id;
    $sql = "SELECT p.*, u.name, u.email, u.phone, u.profile_pic FROM patients p JOIN users u ON u.id = p.user_id WHERE p.user_id = $user_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function updatePatientProfile($conn, $data) {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $user_id = (int)$data['user_id'];

    $sql = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE id = $user_id";
    $status = mysqli_query($conn, $sql);

    if (!$status) {
        return false;
    }

    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $blood_group = mysqli_real_escape_string($conn, $data['blood_group']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $emergency_name = mysqli_real_escape_string($conn, $data['emergency_name']);
    $emergency_phone = mysqli_real_escape_string($conn, $data['emergency_phone']);

    $sql = "UPDATE patients SET date_of_birth = '$dob', blood_group = '$blood_group', gender = '$gender', address = '$address', emergency_contact_name = '$emergency_name', emergency_contact_phone = '$emergency_phone' WHERE user_id = $user_id";
    return mysqli_query($conn, $sql);
}

function getPatientMedicalNotes($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT medical_history_notes FROM patients WHERE id = $patient_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $notes = [];
        if (!empty($row['medical_history_notes'])) {
            $notes = array_filter(array_map('trim', explode("\n", $row['medical_history_notes'])));
        }
        return $notes;
    }
    return [];
}

function addPatientMedicalNote($conn, $patient_id, $note_text) {
    $patient_id = (int)$patient_id;
    $note_text = mysqli_real_escape_string($conn, $note_text);
    $sql = "UPDATE patients SET medical_history_notes = CONCAT(IFNULL(medical_history_notes, ''), CASE WHEN medical_history_notes IS NULL OR medical_history_notes = '' THEN '$note_text' ELSE CONCAT('\\n', '$note_text') END) WHERE id = $patient_id";
    return mysqli_query($conn, $sql);
}

function getDoctorReviews($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT r.*, u.name AS doctor_name FROM doctor_reviews r JOIN doctors d ON d.id = r.doctor_id JOIN users u ON u.id = d.user_id WHERE r.patient_id = $patient_id ORDER BY r.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $reviews = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
    }
    return $reviews;
}

function addDependent($conn, $patient_id, $data) {
    $patient_id = (int)$patient_id;
    $name = mysqli_real_escape_string($conn, $data['name']);
    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $relationship = mysqli_real_escape_string($conn, $data['relationship']);
    $blood_group = mysqli_real_escape_string($conn, $data['blood_group']);
    $sql = "INSERT INTO dependents (primary_patient_id, name, date_of_birth, relationship, blood_group) VALUES ($patient_id, '$name', '$dob', '$relationship', '$blood_group')";
    return mysqli_query($conn, $sql);
}

function removeDependent($conn, $dependent_id, $patient_id) {
    $dependent_id = (int)$dependent_id;
    $patient_id = (int)$patient_id;
    $sql = "DELETE FROM dependents WHERE id = $dependent_id AND primary_patient_id = $patient_id";
    return mysqli_query($conn, $sql);
}

function getPatientDependents($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT * FROM dependents WHERE primary_patient_id = $patient_id ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $dependents = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dependents[] = $row;
        }
    }
    return $dependents;
}

function getAnnouncementsForPatient($conn) {
    $sql = "SELECT * FROM announcements WHERE target_role IN ('all','patient') ORDER BY published_at DESC";
    $result = mysqli_query($conn, $sql);
    $announcements = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $announcements[] = $row;
        }
    }
    return $announcements;
}

function getApprovedDoctors($conn, $search = '', $specialization = '', $feeMin = 0, $feeMax = 0) {
    $query = "SELECT d.id, u.name AS doctor_name, d.consultation_fee, d.experience_years, d.bio, s.name AS specialization FROM doctors d JOIN users u ON u.id = d.user_id JOIN specializations s ON s.id = d.specialization_id WHERE d.is_approved = 1";

    if ($search !== '') {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (u.name LIKE '%$search%' OR s.name LIKE '%$search%' OR d.bio LIKE '%$search%')";
    }

    if ($specialization !== '') {
        $specialization = mysqli_real_escape_string($conn, $specialization);
        $query .= " AND s.id = '$specialization'";
    }

    if ($feeMin > 0) {
        $feeMin = (float)$feeMin;
        $query .= " AND d.consultation_fee >= $feeMin";
    }

    if ($feeMax > 0) {
        $feeMax = (float)$feeMax;
        $query .= " AND d.consultation_fee <= $feeMax";
    }

    $query .= " ORDER BY u.name ASC";
    $result = mysqli_query($conn, $query);
    $doctors = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $doctors[] = $row;
        }
    }
    return $doctors;
}

function getDoctorById($conn, $doctor_id) {
    $doctor_id = (int)$doctor_id;
    $sql = "SELECT d.*, u.name AS doctor_name, u.email, u.phone, s.name AS specialization FROM doctors d JOIN users u ON u.id = d.user_id JOIN specializations s ON s.id = d.specialization_id WHERE d.id = $doctor_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getDoctorAvailability($conn, $doctor_id) {
    $doctor_id = (int)$doctor_id;
    $sql = "SELECT * FROM doctor_availability WHERE doctor_id = $doctor_id AND is_available = 1 ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time";
    $result = mysqli_query($conn, $sql);
    $availability = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $availability[] = $row;
        }
    }
    return $availability;
}

function getBookedTimes($conn, $doctor_id, $date) {
    $doctor_id = (int)$doctor_id;
    $date = mysqli_real_escape_string($conn, $date);
    $sql = "SELECT appointment_time FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$date' AND status NOT IN ('cancelled','no_show')";
    $result = mysqli_query($conn, $sql);
    $times = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $times[] = $row['appointment_time'];
        }
    }
    return $times;
}

function getAvailableSlots($conn, $doctor_id, $date) {
    // Simple logic:
    // 1) Find the doctor's availability row for that day.
    // 2) Create time slots from start_time to end_time using slot_duration_minutes.
    // 3) Remove any times that are already booked.

    $dayName = date('l', strtotime($date));
    $bookedTimes = getBookedTimes($conn, $doctor_id, $date);

    $slots = [];

    $availability = getDoctorAvailability($conn, $doctor_id);
    foreach ($availability as $row) {
        if ($row['day_of_week'] !== $dayName) {
            continue;
        }

        $start = $row['start_time']; // e.g. 09:00:00
        $end = $row['end_time'];     // e.g. 12:00:00
        $minutesStep = (int)$row['slot_duration_minutes'];
        if ($minutesStep <= 0) {
            continue;
        }

        $currentTs = strtotime($date . ' ' . $start);
        $endTs = strtotime($date . ' ' . $end);

        while ($currentTs < $endTs) {
            $slotValue = date('H:i:s', $currentTs);

            // If not booked, include it
            if (!in_array($slotValue, $bookedTimes)) {
                $slots[] = [
                    'value' => $slotValue,
                    'label' => date('h:i A', $currentTs)
                ];
            }

            // Move to next slot
            $currentTs = strtotime('+'.$minutesStep.' minutes', $currentTs);

        }

        break; // only one availability row per day is expected
    }

    return $slots;
}

function bookAppointment($conn, $data) {
    $patient_id = (int)$data['patient_id'];
    $doctor_id = (int)$data['doctor_id'];
    $appointment_date = mysqli_real_escape_string($conn, $data['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $data['appointment_time']);
    $reason = mysqli_real_escape_string($conn, $data['reason']);
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by) VALUES ($patient_id, $doctor_id, '$appointment_date', '$appointment_time', '$reason', 'pending', 'patient')";
    return mysqli_query($conn, $sql);
}

function getUpcomingAppointments($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT a.*, u.name AS doctor_name, s.name AS specialization FROM appointments a JOIN doctors d ON d.id = a.doctor_id JOIN users u ON u.id = d.user_id JOIN specializations s ON s.id = d.specialization_id WHERE a.patient_id = $patient_id AND a.appointment_date >= CURDATE() ORDER BY a.appointment_date, a.appointment_time";
    $result = mysqli_query($conn, $sql);
    $appointments = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }
    return $appointments;
}

function getPastAppointments($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT a.*, u.name AS doctor_name, s.name AS specialization FROM appointments a JOIN doctors d ON d.id = a.doctor_id JOIN users u ON u.id = d.user_id JOIN specializations s ON s.id = d.specialization_id WHERE a.patient_id = $patient_id AND a.appointment_date < CURDATE() ORDER BY a.appointment_date DESC";
    $result = mysqli_query($conn, $sql);
    $appointments = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }
    return $appointments;
}

function getBillingHistory($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT b.*, a.appointment_date, u.name AS doctor_name FROM billing b JOIN appointments a ON a.id = b.appointment_id JOIN doctors d ON d.id = a.doctor_id JOIN users u ON u.id = d.user_id WHERE b.patient_id = $patient_id ORDER BY b.id DESC";
    $result = mysqli_query($conn, $sql);
    $billing = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $billing[] = $row;
        }
    }
    return $billing;
}

function getAppointmentsPendingReview($conn, $patient_id) {
    $patient_id = (int)$patient_id;
    $sql = "SELECT a.id, a.doctor_id, a.appointment_date, u.name AS doctor_name FROM appointments a JOIN doctors d ON d.id = a.doctor_id JOIN users u ON u.id = d.user_id WHERE a.patient_id = $patient_id AND a.status = 'completed' AND a.id NOT IN (SELECT appointment_id FROM doctor_reviews)";
    $result = mysqli_query($conn, $sql);
    $appointments = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }
    return $appointments;
}

function addDoctorReview($conn, $data) {
    $appointment_id = (int)$data['appointment_id'];
    $patient_id = (int)$data['patient_id'];
    $doctor_id = (int)$data['doctor_id'];
    $rating = (int)$data['rating'];
    $review_text = mysqli_real_escape_string($conn, $data['review_text']);
    $sql = "INSERT INTO doctor_reviews (appointment_id, patient_id, doctor_id, rating, review_text) VALUES ($appointment_id, $patient_id, $doctor_id, $rating, '$review_text')";
    return mysqli_query($conn, $sql);
}

function getConsultationNoteByAppointment($conn, $appointment_id, $patient_id) {
    $appointment_id = (int)$appointment_id;
    $patient_id = (int)$patient_id;
    $sql = "SELECT * FROM consultation_notes WHERE appointment_id = $appointment_id AND patient_id = $patient_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function cancelAppointment($conn, $appointment_id, $patient_id) {
    $appointment_id = (int)$appointment_id;
    $patient_id = (int)$patient_id;
    $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = $appointment_id AND patient_id = $patient_id AND status NOT IN ('cancelled','completed','no_show')";
    return mysqli_query($conn, $sql);
}

function rescheduleAppointment($conn, $appointment_id, $patient_id, $new_date, $new_time) {
    $appointment_id = (int)$appointment_id;
    $patient_id = (int)$patient_id;
    $new_date = mysqli_real_escape_string($conn, $new_date);
    $new_time = mysqli_real_escape_string($conn, $new_time);
    $sql = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time', status = 'pending' WHERE id = $appointment_id AND patient_id = $patient_id AND status IN ('pending','confirmed')";
    return mysqli_query($conn, $sql);
}

?>
