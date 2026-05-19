<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
  <div class="topbar">
    <div class="brand">MediBook Register</div>
    <div><a href="../../index.php" style="color: #eef9ff;">Back to home</a></div>
  </div>
</header>
<div class="page-layout">
  <aside class="sidebar">
    <nav>
      <a href="../../index.php">Home</a>
      <a href="login.php">Login</a>
      <a href="register.php" class="active">Register</a>
    </nav>
  </aside>
  <main class="content">
    <div class="page-panel">
      <h1>Register</h1>
      <p>This application currently uses seeded user accounts. If you need to register a new patient, please use the receptionist patient registration page after logging in.</p>
      <a href="../../view/receptionist_register.php" class="btn alt">Go to Patient Registration</a>
    </div>
  </main>
</div>
<?php include '../../footer/footer.php'; ?>
</body>
</html>
