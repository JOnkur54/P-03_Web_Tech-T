<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../../model/patientModel.php';

$doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$appointment_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);

$doctors = getApprovedDoctors($conn);
$slots = [];

if ($doctor_id > 0 && !empty($appointment_date)) {
    $slots = getAvailableSlots($conn, $doctor_id, $appointment_date);
}

?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Available Appointments</title>
</head>
<body>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>View Available Appointments</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <div style="margin-bottom: 16px;">
            <label for="doctorSelect">Doctor:</label>
            <select id="doctorSelect" style="min-width: 260px;" onchange="loadSlots()">
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo (int)$doctor['id']; ?>" <?php echo $doctor_id === (int)$doctor['id'] ? 'selected' : ''; ?>">
                        <?php echo htmlspecialchars($doctor['doctor_name'] . ' — ' . $doctor['specialization']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="appointmentDate" style="margin-left: 16px;">Date:</label>
            <input type="date" id="appointmentDate" value="<?php echo htmlspecialchars($appointment_date); ?>" onchange="loadSlots()" />
        </div>

        <div>
            <h3>Available Slots</h3>
            <div id="slotsContainer">
                <?php if (!empty($slots)): ?>
                    <ul>
                        <?php foreach ($slots as $slot): ?>
                            <li><?php echo htmlspecialchars($slot['label']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No slots to show.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>

<script>
    function loadSlots() {
        const doctorId = document.getElementById('doctorSelect').value;
        const appointmentDate = document.getElementById('appointmentDate').value;

        const container = document.getElementById('slotsContainer');
        container.innerHTML = '<p>Loading slots...</p>';

        if (!doctorId || !appointmentDate) {
            container.innerHTML = '<p>Select doctor and date.</p>';
            return;
        }

        fetch(`../../controllers/patientDoctorController.php?action=getSlots&doctor_id=${doctorId}&date=${appointmentDate}`)
            .then(r => r.json())
            .then(data => {
                if (data.slots && data.slots.length > 0) {
                    container.innerHTML = '<ul>' + data.slots.map(s => `<li>${s.label}</li>`).join('') + '</ul>';
                } else {
                    container.innerHTML = '<p>No slots available.</p>';
                }
            })
            .catch(() => {
                container.innerHTML = '<p>Unable to load slots.</p>';
            });
    }

    window.addEventListener('DOMContentLoaded', function () {
        const doctorId = document.getElementById('doctorSelect').value;
        const appointmentDate = document.getElementById('appointmentDate').value;
        if (doctorId && appointmentDate) {
            loadSlots();
        }
    });
</script>
</body>
</html>

