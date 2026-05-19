<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$roleLabel = $role !== '' ? ucfirst($role) : 'Staff';
$error = '';
if (!empty($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($roleLabel); ?> Login</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
  <div class="topbar">
    <div class="brand">MediBook <?php echo htmlspecialchars($roleLabel); ?> Login</div>
    <div><a href="../../index.php" style="color: #eef9ff;">Back to home</a></div>
  </div>
</header>
<div class="page-layout">
  <aside class="sidebar">
    <nav>
      <a href="../../index.php">Home</a>
      <a href="login.php" class="active">Login</a>
      <a href="register.php">Register</a>
    </nav>
  </aside>
  <main class="content">
    <div class="page-panel">
      <h1><?php echo htmlspecialchars($roleLabel); ?> Login</h1>
      <?php if ($error !== ''): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form action="../../controllers/LoginController.php" method="post" class="form-grid">
        <?php if ($role !== ''): ?>
          <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
        <?php else: ?>
          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
              <option value="">Select role</option>
              <option value="patient">Patient</option>
              <option value="doctor">Doctor</option>
              <option value="receptionist">Receptionist</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group" style="align-self: end;">
          <button type="submit" class="btn">Login</button>
        </div>
      </form>
      <p style="margin-top: 18px; color: #475569;">Use any valid staff account from the hospital database. Default password: <strong>password123</strong>.</p>
    </div>
  </main>
</div>
<?php include '../../footer/footer.php'; ?>
</body>
</html>
