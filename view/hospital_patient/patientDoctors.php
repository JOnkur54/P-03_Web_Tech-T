<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['doctors'])) { header("Location: ../../controllers/patientDoctorsShowController.php"); exit(); }
$doctors         = $_SESSION['doctors'];
$search          = isset($_SESSION['search'])          ? $_SESSION['search']          : "";
$specialization  = isset($_SESSION['specialization'])  ? $_SESSION['specialization']  : "";
$specializations = isset($_SESSION['specializations']) ? $_SESSION['specializations'] : [];
unset($_SESSION['doctors'], $_SESSION['search'], $_SESSION['specialization'], $_SESSION['specializations']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Doctors</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Browse Doctors</h2>
        <form method="GET" action="../../controllers/patientDoctorsShowController.php" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label>Search:</label>
                    <input type="text" name="search" placeholder="Doctor name or keyword" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="form-group">
                    <label>Specialization:</label>
                    <select name="specialization">
                        <option value="">All Specializations</option>
                        <?php foreach ($specializations as $spec) { ?>
                            <option value="<?php echo $spec['id']; ?>" <?php if ($specialization == $spec['id']) { echo "selected"; } ?>>
                                <?php echo htmlspecialchars($spec['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Min Fee ৳:</label>
                    <input type="number" name="fee_min" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Max Fee ৳:</label>
                    <input type="number" name="fee_max" placeholder="5000">
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" value="Search & Filter">
                <a href="../../controllers/patientDoctorsShowController.php" class="reset-btn">Reset</a>
            </div>
        </form>
    </div>
    <?php if (empty($doctors)) { ?>
        <div class="card"><p>No doctors found.</p></div>
    <?php } else { ?>
        <?php foreach ($doctors as $doc) { ?>
        <div class="card doctor-card">
            <h3><?php echo htmlspecialchars($doc['doctor_name']); ?></h3>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doc['specialization']); ?></p>
            <p><strong>Experience:</strong> <?php echo $doc['experience_years']; ?> years</p>
            <p><strong>Fee:</strong> ৳ <?php echo $doc['consultation_fee']; ?></p>
            <p style="font-size:13px;color:#666;margin-top:6px;"><?php echo htmlspecialchars($doc['bio']); ?></p>
            <a href="../../controllers/patientDoctorDetailsShowController.php?doctor_id=<?php echo $doc['id']; ?>" class="view-link">View Profile &rarr;</a>
        </div>
        <?php } ?>
    <?php } ?>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>