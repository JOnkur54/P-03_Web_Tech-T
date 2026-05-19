<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_SESSION['doctors'])) {
    header("Location: ../../controllers/adminManageDoctorsController.php");
    exit();
}

$doctors         = $_SESSION['doctors'];
$specializations = isset($_SESSION['specializations']) ? $_SESSION['specializations'] : [];
$errors          = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success         = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['doctors'], $_SESSION['specializations'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>

<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">

    <div class="card">
        <h2>Manage Doctors</h2>

        <p id="msg">
            <?php echo $success ? htmlspecialchars($success) : ""; ?>
        </p>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $e) { ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <button onclick="openModal('addDoctorModal')" style="background-color:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-bottom:16px;">
            <h5>+ Add New Doctor </h5>
        </button>

        <?php if (empty($doctors)) { ?>
            <p>No doctors found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Fee (&#2547;)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($doctors as $doc) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($doc['name']); ?></td>
                        <td><?php echo htmlspecialchars($doc['email']); ?></td>
                        <td><?php echo htmlspecialchars($doc['specialization']); ?></td>
                        <td><?php echo $doc['consultation_fee']; ?></td>
                        <td>
                            <?php if ($doc['is_active'] == 0) { ?>
                                <span class="badge-inactive">Inactive</span>
                            <?php } else if ($doc['is_approved'] == 0) { ?>
                                <span class="badge-pending">Pending</span>
                            <?php } else { ?>
                                <span class="badge-approved">Approved</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($doc['is_approved'] == 0 && $doc['is_active'] == 1) { ?>
                                <form action="../../controllers/adminDoctorActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doc['id']; ?>">
                                    <button type="submit" class="btn-approve">Approve</button>
                                </form>
                                <form action="../../controllers/adminDoctorActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doc['id']; ?>">
                                    <button type="submit" class="btn-reject">Reject</button>
                                </form>
                            <?php } ?>

                            <button class="btn-edit"
                                data-id="<?php echo $doc['id']; ?>"
                                data-name="<?php echo htmlspecialchars($doc['name'], ENT_QUOTES); ?>"
                                data-email="<?php echo htmlspecialchars($doc['email'], ENT_QUOTES); ?>"
                                data-phone="<?php echo htmlspecialchars($doc['phone'], ENT_QUOTES); ?>"
                                data-spec="<?php echo $doc['specialization_id']; ?>"
                                data-fee="<?php echo $doc['consultation_fee']; ?>"
                                data-exp="<?php echo $doc['experience_years']; ?>"
                                data-license="<?php echo htmlspecialchars($doc['license_number'], ENT_QUOTES); ?>"
                                data-bio="<?php echo htmlspecialchars($doc['bio'], ENT_QUOTES); ?>"
                                onclick="openEditModal(this)">Edit</button>

                            <?php if ($doc['is_active'] == 1) { ?>
                                <form action="../../controllers/adminDoctorActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="deactivate">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doc['id']; ?>">
                                    <button type="submit" class="btn-deactivate">Deactivate</button>
                                </form>
                            <?php } else { ?>
                                <form action="../../controllers/adminDoctorActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="activate">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doc['id']; ?>">
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

<!-- ADD DOCTOR MODAL -->
<div class="modal-overlay" id="addDoctorModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('addDoctorModal')">&times;</button>
        <h3>Add New Doctor</h3>
        <form action="../../controllers/adminDoctorActionController.php" method="POST" onsubmit="return validateAddDoctor(this)" novalidate>
            <input type="hidden" name="action" value="add">

            <label for="name">Full Name:</label>
            <input type="text" name="name" id="add_name">
            <span id="addNameErr"></span>

            <label for="email">Email:</label>
            <input type="email" name="email" id="add_email">
            <span id="addEmailErr"></span>

            <label for="password">Password:</label>
            <input type="password" name="password" id="add_password">
            <span id="addPasswordErr"></span>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="add_phone">

            <label for="specialization_id">Specialization:</label>
            <select name="specialization_id" id="add_spec">
                <option value="">-- Select --</option>
                <?php foreach ($specializations as $spec) { ?>
                    <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name']); ?></option>
                <?php } ?>
            </select>
            <span id="addSpecErr"></span>

            <label for="consultation_fee">Consultation Fee (&#2547;):</label>
            <input type="number" name="consultation_fee" id="add_fee" value="0">

            <label for="experience_years">Experience (years):</label>
            <input type="number" name="experience_years" id="add_exp" value="0">

            <label for="license_number">License Number:</label>
            <input type="text" name="license_number" id="add_license">

            <label for="bio">Bio:</label>
            <textarea name="bio" id="add_bio" rows="3"></textarea>

            <input type="submit" value="Add Doctor">
        </form>
    </div>
</div>

<!-- EDIT DOCTOR MODAL -->
<div class="modal-overlay" id="editDoctorModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editDoctorModal')">&times;</button>
        <h3>Edit Doctor</h3>
        <form action="../../controllers/adminDoctorActionController.php" method="POST" novalidate>
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="doctor_id" id="edit_doctor_id">

            <label>Full Name:</label>
            <input type="text" name="name" id="edit_name">

            <label>Email:</label>
            <input type="email" name="email" id="edit_email">

            <label>Phone:</label>
            <input type="text" name="phone" id="edit_phone">

            <label>Specialization:</label>
            <select name="specialization_id" id="edit_spec">
                <?php foreach ($specializations as $spec) { ?>
                    <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name']); ?></option>
                <?php } ?>
            </select>

            <label>Consultation Fee (&#2547;):</label>
            <input type="number" name="consultation_fee" id="edit_fee">

            <label>Experience (years):</label>
            <input type="number" name="experience_years" id="edit_exp">

            <label>License Number:</label>
            <input type="text" name="license_number" id="edit_license">

            <label>Bio:</label>
            <textarea name="bio" id="edit_bio" rows="3"></textarea>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminManageDoctors.js"></script>

<?php include "../partials/adminFooter.php"; ?>