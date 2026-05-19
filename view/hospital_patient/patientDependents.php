<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['dependents'])) { header("Location: ../../controllers/patientDependentsShowController.php"); exit(); }
$dependents     = $_SESSION['dependents'];
$errors         = isset($_SESSION['errors'])         ? $_SESSION['errors']         : [];
$success        = isset($_SESSION['success'])        ? $_SESSION['success']        : "";
$edit_dependent = isset($_SESSION['edit_dependent']) ? $_SESSION['edit_dependent'] : null;
unset($_SESSION['dependents'], $_SESSION['errors'], $_SESSION['success'], $_SESSION['edit_dependent']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dependents</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2><?php echo $edit_dependent ? "Edit Dependent" : "Add Dependent"; ?></h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
        <form action="../../controllers/patientDependentsController.php" method="POST" onsubmit="return validate(this)" novalidate>
            <?php if ($edit_dependent) { ?>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="dependent_id" value="<?php echo $edit_dependent['id']; ?>">
            <?php } else { ?>
                <input type="hidden" name="action" value="add">
            <?php } ?>
            <label>Name:</label>
            <input type="text" name="name" id="dep_name" placeholder="Full name" value="<?php echo $edit_dependent ? htmlspecialchars($edit_dependent['name']) : ''; ?>">
            <span id="nameErr"></span>
            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $edit_dependent ? htmlspecialchars($edit_dependent['date_of_birth']) : ''; ?>">
            <label>Relationship:</label>
            <input type="text" name="relationship" placeholder="e.g. Son, Daughter, Spouse" value="<?php echo $edit_dependent ? htmlspecialchars($edit_dependent['relationship']) : ''; ?>">
            <label>Blood Group:</label>
            <input type="text" name="blood_group" placeholder="e.g. A+" value="<?php echo $edit_dependent ? htmlspecialchars($edit_dependent['blood_group']) : ''; ?>">
            <input type="submit" value="<?php echo $edit_dependent ? 'Update Dependent' : 'Add Dependent'; ?>">
            <?php if ($edit_dependent) { ?><a href="patientDependents.php" class="cancel-btn" style="margin-left:10px;">Cancel</a><?php } ?>
        </form>
    </div>
    <div class="card">
        <h3>Your Dependents</h3>
        <?php if (empty($dependents)) { ?>
            <p>No dependents added yet.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Name</th><th>DOB</th><th>Relationship</th><th>Blood Group</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($dependents as $dep) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($dep['name']); ?></td>
                    <td><?php echo htmlspecialchars($dep['date_of_birth']); ?></td>
                    <td><?php echo htmlspecialchars($dep['relationship']); ?></td>
                    <td><?php echo htmlspecialchars($dep['blood_group']); ?></td>
                    <td>
                        <a href="../../controllers/patientDependentsController.php?action=edit_form&id=<?php echo $dep['id']; ?>" class="edit-link">Edit</a> |
                        <a href="../../controllers/patientDependentsController.php?action=delete&id=<?php echo $dep['id']; ?>" class="delete-link" onclick="return confirm('Delete this dependent?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientDependents.js"></script>