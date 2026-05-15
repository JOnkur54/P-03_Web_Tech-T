<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../../view/partials/header.php'; ?>
<?php include '../../view/partials/left.php'; ?>

<div class="main">

<div class="card">

<h2>Patient Registration</h2>


<?php if ($success): ?>
    <div class="success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form action="../../controllers/patientRegisterController.php" method="POST" enctype="multipart/form-data" novalidate>

<input type="text" name="name" placeholder="Full Name" required><br><br>

<input type="email" name="email" placeholder="Email" required><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>

<input type="date" name="dob" required><br><br>

<input type="text" name="blood_group" placeholder="Blood Group"><br><br>

<select name="gender">
<option value="">Gender</option>
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select><br><br>

<input type="text" name="phone" placeholder="Phone"><br><br>

<textarea name="address" placeholder="Address"></textarea><br><br>

<input type="text" name="emergency_name" placeholder="Emergency Contact Name"><br><br>

<input type="text" name="emergency_phone" placeholder="Emergency Contact Phone"><br><br>

<textarea name="medical_history" placeholder="Personal medical history notes"></textarea><br><br>

<input type="file" name="profile_pic"><br><br>

<input type="submit" value="Register">

</form>

</div>

</div>

<?php include '../../view/partials/right.php'; ?>

<?php include '../../view/partials/footer.php'; ?>