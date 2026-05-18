<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['doctors'])) {
    header("Location: ../../controllers/patientDoctorsShowController.php");
    exit();
}

$doctors = $_SESSION['doctors'];
$search = isset($_SESSION['search']) ? $_SESSION['search'] : "";
$specializations = isset($_SESSION['specializations']) ? $_SESSION['specializations'] : [];

unset($_SESSION['doctors'], $_SESSION['search'], $_SESSION['specializations']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Doctors</title>
    <link rel="stylesheet" href="../css/doctors.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">
        <h2>Browse Doctors</h2>
        
        <form method="GET" action="../../controllers/patientDoctorsShowController.php" class="filter-form">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Doctor name or keyword" value="<?php echo $search; ?>">
                </div>
                
                <div class="form-group">
                    <label for="specialization">Specialization:</label>
                    <select name="specialization" id="specialization">
                        <option value="">All Specializations</option>
                        <?php foreach ($specializations as $spec) { ?>
                            <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fee_min">Min Fee (৳):</label>
                    <input type="number" name="fee_min" id="fee_min" placeholder="0">
                </div>
                
                <div class="form-group">
                    <label for="fee_max">Max Fee (৳):</label>
                    <input type="number" name="fee_max" id="fee_max" placeholder="5000">
                </div>
            </div>
            
            <div class="form-actions">
                <input type="submit" value="Search & Filter">
                <a href="../../controllers/patientDoctorsShowController.php" class="reset-btn">Reset</a>
            </div>
            
        </form>
    </div>

    <?php if (empty($doctors)) { ?>
        <div class="card">
            <p>No doctors matched your search.</p>
        </div>
    <?php } else { ?>
        <?php foreach ($doctors as $doctor) { ?>
            <div class="card doctor-card">
                <h3><?php echo $doctor['doctor_name']; ?></h3>
                <p><b>Specialization:</b> <?php echo $doctor['specialization']; ?></p>
                <p><b>Experience:</b> <?php echo $doctor['experience_years']; ?> years</p>
                <p><b>Consultation Fee:</b> &#2547; <?php echo $doctor['consultation_fee']; ?></p>
                <p><?php echo $doctor['bio']; ?></p>
                <a href="../../controllers/patientDoctorDetailsShowController.php?doctor_id=<?php echo $doctor['id']; ?>" class="view-link">View Profile</a>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>