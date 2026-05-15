<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Manage Profile</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="../../controllers/patientManageProfileController.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($patient['name'] ?? ''); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>" required>
            <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>">
            <input type="date" name="dob" value="<?php echo htmlspecialchars($patient['date_of_birth'] ?? ''); ?>">
            <input type="text" name="blood_group" placeholder="Blood Group" value="<?php echo htmlspecialchars($patient['blood_group'] ?? ''); ?>">
            <select name="gender">
                <option value="">Gender</option>
                <option value="male" <?php echo (isset($patient['gender']) && $patient['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo (isset($patient['gender']) && $patient['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo (isset($patient['gender']) && $patient['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
            </select>
            <textarea name="address" placeholder="Address"><?php echo htmlspecialchars($patient['address'] ?? ''); ?></textarea>
            <input type="text" name="emergency_name" placeholder="Emergency Contact Name" value="<?php echo htmlspecialchars($patient['emergency_contact_name'] ?? ''); ?>">
            <input type="text" name="emergency_phone" placeholder="Emergency Contact Phone" value="<?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?>">
            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>