<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_SESSION['patients'])) {
    header("Location: ../../controllers/adminManagePatientsController.php");
    exit();
}

$patients = $_SESSION['patients'];
$search   = isset($_SESSION['patient_search']) ? $_SESSION['patient_search'] : "";
$success  = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['patients'], $_SESSION['patient_search'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>

<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Manage Patients</h2>

        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>

        <form method="GET" action="../../controllers/adminManagePatientsController.php" style="margin-bottom:16px;">
            <input type="text" name="search" placeholder="Search by name, email or phone" value="<?php echo htmlspecialchars($search); ?>" style="width:60%;padding:10px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="Search" style="background:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
            <a href="../../controllers/adminManagePatientsController.php" style="margin-left:10px;color:#0033a0;">Reset</a>
        </form>

        <?php if (empty($patients)) { ?>
            <p>No patients found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($patients as $pat) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($pat['name']); ?></td>
                        <td><?php echo htmlspecialchars($pat['email']); ?></td>
                        <td><?php echo htmlspecialchars($pat['phone']); ?></td>
                        <td><?php echo htmlspecialchars($pat['gender']); ?></td>
                        <td><?php echo htmlspecialchars($pat['blood_group']); ?></td>
                        <td><?php echo date('d M Y', strtotime($pat['created_at'])); ?></td>
                        <td>
                            <?php if ($pat['is_active'] == 1) { ?>
                                <span class="badge-approved">Active</span>
                            <?php } else { ?>
                                <span class="badge-inactive">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($pat['is_active'] == 1) { ?>
                                <form action="../../controllers/adminPatientActionController.php" method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this patient?');">
                                    <input type="hidden" name="action" value="deactivate">
                                    <input type="hidden" name="user_id" value="<?php echo $pat['id']; ?>">
                                    <button type="submit" class="btn-deactivate">Deactivate</button>
                                </form>
                            <?php } else { ?>
                                <form action="../../controllers/adminPatientActionController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="activate">
                                    <input type="hidden" name="user_id" value="<?php echo $pat['id']; ?>">
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

<?php include "../partials/adminRight.php"; ?>

</div>

<?php include "../partials/adminFooter.php"; ?>

</body>
</html>