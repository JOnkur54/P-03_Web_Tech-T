<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_SESSION['receptionists'])) {
    header("Location: ../../controllers/adminManageReceptionistsController.php");
    exit();
}

$receptionists = $_SESSION['receptionists'];
$errors        = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success       = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['receptionists'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Receptionists</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>

<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Manage Receptionists</h2>

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

        <button onclick="openModal('addRecModal')" style="background:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-bottom:16px;">
            + Add Receptionist
        </button>

        <?php if (empty($receptionists)) { ?>
            <p>No receptionists found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($receptionists as $rec) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($rec['name']); ?></td>
                        <td><?php echo htmlspecialchars($rec['email']); ?></td>
                        <td><?php echo htmlspecialchars($rec['phone']); ?></td>
                        <td>
                            <?php if ($rec['is_active'] == 1) { ?>
                                <span class="badge-approved">Active</span>
                            <?php } else { ?>
                                <span class="badge-inactive">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>
                            <button class="btn-edit" onclick="openEditRec('<?php echo $rec['id']; ?>','<?php echo htmlspecialchars($rec['name'],ENT_QUOTES); ?>','<?php echo htmlspecialchars($rec['email'],ENT_QUOTES); ?>','<?php echo htmlspecialchars($rec['phone'],ENT_QUOTES); ?>')">Edit</button>

                            <?php if ($rec['is_active'] == 1) { ?>
                                <form action="../../controllers/adminReceptionistActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="deactivate">
                                    <input type="hidden" name="user_id" value="<?php echo $rec['id']; ?>">
                                    <button type="submit" class="btn-deactivate">Deactivate</button>
                                </form>
                            <?php } else { ?>
                                <form action="../../controllers/adminReceptionistActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="activate">
                                    <input type="hidden" name="user_id" value="<?php echo $rec['id']; ?>">
                                    <button type="submit" class="btn-activate">Activate</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal-overlay" id="addRecModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('addRecModal')">&times;</button>
        <h3>Add Receptionist</h3>
        <form action="../../controllers/adminReceptionistActionController.php" method="POST" onsubmit="return validateRec(this)" novalidate>
            <input type="hidden" name="action" value="add">

            <label>Full Name:</label>
            <input type="text" name="name" id="rec_name">
            <span id="recNameErr"></span>

            <label>Email:</label>
            <input type="email" name="email" id="rec_email">
            <span id="recEmailErr"></span>

            <label>Password:</label>
            <input type="password" name="password" id="rec_password">
            <span id="recPassErr"></span>

            <label>Phone:</label>
            <input type="text" name="phone">

            <input type="submit" value="Add Receptionist">
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editRecModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editRecModal')">&times;</button>
        <h3>Edit Receptionist</h3>
        <form action="../../controllers/adminReceptionistActionController.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_rec_id">

            <label>Full Name:</label>
            <input type="text" name="name" id="edit_rec_name">

            <label>Email:</label>
            <input type="email" name="email" id="edit_rec_email">

            <label>Phone:</label>
            <input type="text" name="phone" id="edit_rec_phone">

            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminManageReceptionists.js"></script>

<?php include "../partials/adminFooter.php"; ?>