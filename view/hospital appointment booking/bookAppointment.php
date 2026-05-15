<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$doctors = getApprovedDoctors($conn);
$selectedDoctorId = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$errors = $_SESSION['errors'] ?? []; 
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Book Appointment</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <form action="../../controllers/patientAppointmentController.php" method="POST" id="bookAppointmentForm">
            <input type="hidden" name="action" value="book">
            <select name="doctor_id" id="doctorSelect" required>
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo (int)$doctor['id']; ?>" <?php echo $selectedDoctorId === (int)$doctor['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($doctor['doctor_name'] . ' — ' . $doctor['specialization']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="appointment_date" id="appointmentDate" required>
            <select name="appointment_time" id="slotSelect" required>
                <option value="">Select a slot</option>
            </select>
            <textarea name="reason" placeholder="Reason for visit" rows="4"></textarea>
            <input type="submit" value="Confirm Booking">
        </form>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>

<script>
    const doctorSelect = document.getElementById('doctorSelect');
    const appointmentDate = document.getElementById('appointmentDate');
    const slotSelect = document.getElementById('slotSelect');

    function loadSlots() {
        const doctorId = doctorSelect.value;
        const date = appointmentDate.value;
        slotSelect.innerHTML = '<option value="">Loading slots...</option>';

        if (!doctorId || !date) {
            slotSelect.innerHTML = '<option value="">Select a doctor and date</option>';
            return;
        }

        fetch(`../../controllers/patientDoctorController.php?action=getSlots&doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                slotSelect.innerHTML = '<option value="">Select a slot</option>';
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.value;
                        option.textContent = slot.label;
                        slotSelect.appendChild(option);
                    });
                } else {
                    slotSelect.innerHTML = '<option value="">No slots available</option>';
                }
            })
            .catch(() => {
                slotSelect.innerHTML = '<option value="">Unable to load slots</option>';
            });
    }

    doctorSelect.addEventListener('change', loadSlots);
    appointmentDate.addEventListener('change', loadSlots);

    window.addEventListener('DOMContentLoaded', function () {
        const selectedDoctor = '<?php echo $selectedDoctorId; ?>';
        if (selectedDoctor && appointmentDate.value) {
            loadSlots();
        }
    });
</script>