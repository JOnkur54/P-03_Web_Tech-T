<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['dependents'])) {
    header("Location: ../../controllers/patientDependentsShowController.php");
    exit();
}

$dependents = $_SESSION['dependents'];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$edit_dependent = isset($_SESSION['edit_dependent']) ? $_SESSION['edit_dependent'] : null;

unset($_SESSION['dependents'], $_SESSION['errors'], $_SESSION['success'], $_SESSION['edit_dependent']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dependents</title>
    <link rel="stylesheet" href="../css/dependents.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2><?php echo $edit_dependent ? "Edit Dependent" : "Add Dependent"; ?></h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="../../controllers/patientDependentsController.php" method="POST" onsubmit="return validate(this)" novalidate>
            
            <?php if ($edit_dependent) { ?>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="dependent_id" value="<?php echo $edit_dependent['id']; ?>">
            <?php } else { ?>
                <input type="hidden" name="action" value="add">
            <?php } ?>

            <label for="dep_name">Name:</label>
            <input type="text" name="name" id="dep_name" placeholder="Dependent name" value="<?php echo $edit_dependent ? $edit_dependent['name'] : ''; ?>">
            <span id="nameErr"></span>

            <label for="dep_dob">Date of Birth:</label>
            <input type="date" name="dob" id="dep_dob" value="<?php echo $edit_dependent ? $edit_dependent['date_of_birth'] : ''; ?>">

            <label for="dep_relationship">Relationship:</label>
            <input type="text" name="relationship" id="dep_relationship" placeholder="Relationship" value="<?php echo $edit_dependent ? $edit_dependent['relationship'] : ''; ?>">

            <label for="dep_blood_group">Blood Group:</label>
            <input type="text" name="blood_group" id="dep_blood_group" placeholder="Blood group" value="<?php echo $edit_dependent ? $edit_dependent['blood_group'] : ''; ?>">

            <input type="submit" value="<?php echo $edit_dependent ? 'Update Dependent' : 'Add Dependent'; ?>">

            <?php if ($edit_dependent) { ?>
                <a href="dependents.php" class="cancel-btn">Cancel</a>
            <?php } ?>

        </form>

    </div>

    <div class="card">
        <h3>Your Dependents</h3>
        <?php if (empty($dependents)) { ?>
            <p>No dependents added yet.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Relationship</th>
                        <th>Blood Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dependents as $dependent) { ?>
                        <tr>
                            <td><?php echo $dependent['name']; ?></td>
                            <td><?php echo $dependent['date_of_birth']; ?></td>
                            <td><?php echo $dependent['relationship']; ?></td>
                            <td><?php echo $dependent['blood_group']; ?></td>
                            <td>
                                <a href="../../controllers/patientDependentsController.php?action=edit_form&id=<?php echo $dependent['id']; ?>" class="edit-link">Edit</a> |
                                <a href="../../controllers/patientDependentsController.php?action=delete&id=<?php echo $dependent['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this dependent?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/dependents.js"></script>

</body>
</html>