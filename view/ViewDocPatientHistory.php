<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorConsultation.php';

$patient_id = $_GET['patient_id'] ?? 0;
$doctor_id = $_SESSION['doctor_id'];

if (!$patient_id) {
    header("Location: ViewDocDashboard.php");
    exit;
}

$consultationModel = new ModelDoctorConsultation($conn);
$history = $consultationModel->getPatientHistory($doctor_id, $patient_id);

// Get patient name for header
$patient_name = "Patient";
if (!empty($history)) {
    // We can get patient name from the first record if we joined it, but we didn't in getPatientHistory.
    // Let's quickly get it or assume we can just say "Patient History".
    // I'll update getPatientHistory to include patient name or just leave it.
    // Let's assume we know it or just use "Patient".
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient History - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reuse layout */
        body { display: flex; min-height: 100vh; background-color: #f3f4f6; }
        .sidebar { width: 250px; background-color: white; border-right: 1px solid var(--light-gray); padding: 20px; display: flex; flex-direction: column; }
        .sidebar .logo { font-size: 20px; font-weight: 700; margin-bottom: 30px; color: var(--dark); }
        .sidebar .logo span { color: var(--primary); }
        .sidebar-nav { list-style: none; flex-grow: 1; }
        .sidebar-nav li { margin-bottom: 10px; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px; border-radius: 8px; text-decoration: none; color: var(--gray); font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: var(--secondary); color: var(--primary); }
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .history-card { background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 20px; }
        .history-card h3 { font-size: 16px; margin-bottom: 10px; color: var(--primary); }
        .history-card p { font-size: 14px; margin-bottom: 5px; }
        .logout-btn { margin-top: auto; color: var(--danger); text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px; display: flex; align-items: center; border-radius: 8px; }
        .logout-btn:hover { background-color: #fee2e2; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Patient History</h1>
            <a href="ViewDocDashboard.php" class="btn btn-secondary" style="background-color: var(--secondary); color: var(--primary); text-decoration: none; padding: 10px 15px; border-radius: 8px; font-size: 14px; font-weight: 500;">Back to Dashboard</a>
        </div>

        <?php if (empty($history)): ?>
            <div class="card" style="background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); text-align: center; color: var(--gray);">
                No past consultations found for this patient.
            </div>
        <?php else: ?>
            <?php foreach ($history as $note): ?>
                <div class="history-card">
                    <h3>Visit Date: <?php echo date('M d, Y', strtotime($note['appointment_date'])); ?></h3>
                    <p><strong>Symptoms:</strong> <?php echo htmlspecialchars($note['symptoms']); ?></p>
                    <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($note['diagnosis']); ?></p>
                    <p><strong>Prescription:</strong> <?php echo htmlspecialchars($note['prescription']); ?></p>
                    <?php if ($note['follow_up_date']): ?>
                        <p><strong>Follow-up Date:</strong> <?php echo date('M d, Y', strtotime($note['follow_up_date'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
