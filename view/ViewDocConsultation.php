<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorConsultation.php';

$appointment_id = $_GET['appointment_id'] ?? 0;
if (!$appointment_id) {
    header("Location: ViewDocDashboard.php");
    exit;
}

$consultationModel = new ModelDoctorConsultation($conn);
$appointment = $consultationModel->getAppointmentDetails($appointment_id);

if (!$appointment) {
    header("Location: ViewDocDashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Notes - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
    <style>
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
        .form-container { background-color: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow); max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 6px; color: var(--dark); }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--light-gray); border-radius: 8px; font-size: 14px; }
        .form-group textarea { height: 100px; resize: vertical; }
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
            <h1>Consultation Notes</h1>
            <a href="ViewDocPatientHistory.php?patient_id=<?php echo $appointment['patient_id']; ?>" class="btn btn-secondary" style="background-color: var(--secondary); color: var(--primary); text-decoration: none; padding: 10px 15px; border-radius: 8px; font-size: 14px; font-weight: 500;">View Patient History</a>
        </div>

        <div class="card" style="background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 30px;">
            <p><strong>Patient:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
            <p><strong>Reason for Visit:</strong> <?php echo htmlspecialchars($appointment['reason']); ?></p>
        </div>

        <div class="form-container">
            <form action="../controllers/ContDocConsultation.php" method="POST">
                <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                <input type="hidden" name="patient_id" value="<?php echo $appointment['patient_id']; ?>">
                
                <div class="form-group">
                    <label for="symptoms">Symptoms</label>
                    <textarea id="symptoms" name="symptoms" required placeholder="Describe patient symptoms..."></textarea>
                </div>
                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" required placeholder="Enter diagnosis..."></textarea>
                </div>
                <div class="form-group">
                    <label for="prescription">Prescription Details</label>
                    <textarea id="prescription" name="prescription" required placeholder="Medication, dosage, instructions..."></textarea>
                </div>
                <div class="form-group">
                    <label for="follow_up_date">Follow-up Date (Optional)</label>
                    <input type="date" id="follow_up_date" name="follow_up_date" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Complete Consultation</button>
            </form>
        </div>
    </div>
</body>
</html>
