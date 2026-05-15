<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$dependents = getPatientDependents($conn, $patient['id'] ?? 0);
$errors = $_SESSION['errors'] ?? []; 
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Dependents</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <form action="../../controllers/patientDependentsController.php" method="POST">
            <input type="text" name="name" placeholder="Dependent name" required>
            <input type="date" name="dob">
            <input type="text" name="relationship" placeholder="Relationship">
            <input type="text" name="blood_group" placeholder="Blood group">
            <input type="submit" value="Add Dependent">
        </form>
    </div>

    <div class="card">
        <h3>Your Dependents</h3>
        <?php if (empty($dependents)): ?>
            <p>No dependents added yet.</p>
        <?php else: ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#f1f3f6;"><th>Name</th><th>DOB</th><th>Relationship</th><th>Blood Group</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($dependents as $dependent): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dependent['name']); ?></td>
                            <td><?php echo htmlspecialchars($dependent['date_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($dependent['relationship']); ?></td>
                            <td><?php echo htmlspecialchars($dependent['blood_group']); ?></td>
                            <td><a href="../../controllers/patientDependentsController.php?action=delete&id=<?php echo (int)$dependent['id']; ?>" style="color:#0d6efd; text-decoration:none;">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>