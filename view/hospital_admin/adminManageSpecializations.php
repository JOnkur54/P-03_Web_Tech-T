<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_SESSION['specializations'])) {
    header("Location: ../../controllers/adminManageSpecializationsController.php");
    exit();
}

$specializations = $_SESSION['specializations'];
$errors          = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success         = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['specializations'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Specializations</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>

<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Manage Specializations</h2>

        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $e) { ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <!-- Add form -->
        <div class="form-section">
            <h3>Add New Specialization</h3>
            <form action="../../controllers/adminSpecializationActionController.php" method="POST" onsubmit="return validateSpec(this)" novalidate>
                <input type="hidden" name="action" value="add">

                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="e.g. Cardiology">
                <span id="nameErr"></span>

                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="2" placeholder="Optional description"></textarea>

                <input type="submit" value="Add Specialization">
            </form>
        </div>

        <!-- List table -->
        <?php if (empty($specializations)) { ?>
            <p>No specializations found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Doctors</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($specializations as $spec) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($spec['name']); ?></td>
                        <td><?php echo htmlspecialchars($spec['description']); ?></td>
                        <td><?php echo $spec['doctor_count']; ?></td>
                        <td>
                            <button class="btn-edit" onclick="openEditSpec('<?php echo $spec['id']; ?>','<?php echo htmlspecialchars($spec['name'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($spec['description'], ENT_QUOTES); ?>')">Edit</button>

                            <form action="../../controllers/adminSpecializationActionController.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete this specialization?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="spec_id" value="<?php echo $spec['id']; ?>">
                                <button type="submit" class="btn-reject">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editSpecModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editSpecModal')">&times;</button>
        <h3>Edit Specialization</h3>
        <form action="../../controllers/adminSpecializationActionController.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="spec_id" id="edit_spec_id">

            <label>Name:</label>
            <input type="text" name="name" id="edit_spec_name">

            <label>Description:</label>
            <textarea name="description" id="edit_spec_desc" rows="2"></textarea>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminManageSpecializations.js"></script>

<?php include "../partials/adminFooter.php"; ?>