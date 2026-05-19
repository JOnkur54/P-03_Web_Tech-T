<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: views/" . $_SESSION['role'] . "/dashboard.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MediBook — Hospital Appointment System</title>
<style>

  * { margin: 0; 
      padding: 0; 
       box-sizing: border-box; }

  body {
    font-family: 'DM Sans', sans-serif;
    background-color: #bdbed8;;
    color: navy;
    min-height: 100vh;
  }


  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 60px;
    background-color: navy;
  }

  .logo {
    font-family: 'DM Serif Display', serif;
    font-size: 22px;
    color: #fff;
    letter-spacing: -0.5px;
  }

  .logo span { color: cyan; }

  nav a {
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    color: #f8fafc;
    margin-left: 32px;
    transition: color .2s;
  }

  nav a:hover { color: cyan; }

  nav .cta {
    background-color: #fff;
    color: #1f2937 !important;
    padding: 10px 22px;
    border-radius: 100px;
    margin-left: 16px;
  }

  nav .cta:hover { background-color: teal; 
                  color: #fff !important; }

  /* HERO */
  .hero {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 60px;
    max-width: 1100px;
    margin: 60px auto 0;
    padding: 0 60px;
  }
  .hero > * {
    flex: 1;
  }

  .hero-text h1 {
    font-family: 'DM Serif Display', serif;
    font-size: 52px;
    line-height: 1.1;
    letter-spacing: -1px;
    margin-bottom: 20px;
  }

  .hero-text h1 i {
    font-style: italic;
    color: teal;
  }

  .hero-text p {
    font-size: 16px;
    color: navy;
    line-height: 1.7;
    margin-bottom: 36px;
    font-weight: 300;
  }

  .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

  .btn {
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    padding: 13px 28px;
    border-radius: 100px;
    transition: all .2s;
  }

  .btn-dark  { background-color: navy; color: #fff; }
  .btn-dark:hover  { background-color: teal; }
  .btn-ghost { border: 1.5px solid #c9c3bb; color: #1f2937; }
  .btn-ghost:hover { border-color: #1f2937; }

  /* VISUAL CARD */
  .hero-visual {
    background-color: #d9f2ef;
    border-radius: 24px;
    padding: 36px;
    color: navy;
    border: 1px solid #8dcfca;
  }

  .hero-visual .v-label {
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #1f2937;
    margin-bottom: 20px;
  }

  .stat-row {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
  }

  .stat {
    flex: 1;
    background-color: #eaf8f6;
    border-radius: 14px;
    padding: 18px;
  }

  .stat-num {
    font-family: 'DM Serif Display', serif;
    font-size: 28px;
    color: navy;
  }

  .stat-desc { font-size: 12px; color: #4f636a; margin-top: 4px; }

  .appt-list { display: flex; flex-direction: column; gap: 10px; }

  .appt-item {
    background-color: #eef7f6;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
  }

  .appt-item .doc { 
    color: navy; 
    font-weight: 500; 
  }
  .appt-item .time { 
    color: #4f636a; 
  }

  .badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 100px;
    font-weight: 500;
  }

  .badge-green {
     background-color: rgba(10,147,150,.12); 
     color: navy; }
  .badge-amber { 
    background-color: rgba(251,191,36,.12); 
    color: #92400e; }

  /* ROLES */
  .roles {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    max-width: 1100px;
    margin: 80px auto 0;
    padding: 0 60px;
    justify-content: space-between;
  }

  .role-card {
    flex: 1;
    min-width: 220px;
    background-color: #d9f2ef;
    border: 1px solid #e8e3dc;
    border-radius: 18px;
    padding: 28px 22px;
    text-decoration: none;
    color: #1f2937;
    transition: border-color .2s, background-color .2s;
    display: block;
  }

  .role-card:hover {
    border-color: teal;
    background-color: #f8f5f0;
  }

  .role-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    margin-bottom: 14px;
    border-radius: 16px;
    background-color: #d9f2ef;
    color: #1f2937;
    font-size: 32px;
    font-weight: 700;
  }
  .role-card h3 { font-size: 15px; font-weight: 500; margin-bottom: 6px; }
  .role-card p  { font-size: 13px; color: gray; line-height: 1.5; font-weight: 300; }

</style>
</head>
<body>

<nav>
  <div class="logo">Medi<span>Book</span></div>
  <div>
    <a href="#roles">Roles</a>
    <a href="view/hospital_patient/patientRegister.php">Register</a>
    <a href="view/hospital_patient/patientLogin.php" class="cta">Login</a>
  </div>
</nav>

<section class="hero">
  <div class="hero-text">
    <h1>Healthcare,<br><i>simplified</i><br>for everyone.</h1>
    <p>Book appointments, manage schedules, and streamline your hospital — all from one elegant platform.</p>
    <div class="hero-actions">
      <a href="view/hospital_patient/patientRegister.php" class="btn btn-dark">Get Started</a>
      <a href="view/hospital_patient/patientLogin.php" class="btn btn-ghost">Staff Login</a>
    </div>
  </div>

  <div class="hero-visual">
    <div class="v-label">Today's Overview</div>
    <div class="stat-row">
      <div class="stat">
        <div class="stat-num">24</div>
        <div class="stat-desc">Appointments</div>
      </div>
      <div class="stat">
        <div class="stat-num">8</div>
        <div class="stat-desc">Doctors on duty</div>
      </div>
    </div>
    <div class="appt-list">
      <div class="appt-item">
        <div class="doc">Dr. Onkur <span class="time">09:00 AM · Cardiology</span></div>
        <span class="badge badge-green">Confirmed</span>
      </div>
      <div class="appt-item">
        <div class="doc">Dr. Nowshin <span class="time">10:30 AM · Neurology</span></div>
        <span class="badge badge-amber">Pending</span>
      </div>
      <div class="appt-item">
        <div class="doc">Dr. Ridika <span class="time">11:00 AM · Pediatrics</span></div>
        <span class="badge badge-green">Confirmed</span>
      </div>
    </div>
  </div>
</section>

<section class="roles" id="roles">
  <a href="view/hospital_patient/patientLogin.php" class="role-card">
    <div class="role-icon">P</div>
    <h3>Patient</h3>
    <p>Book appointments and view your medical records</p>
  </a>
  <a href="view/hospital appointment booking/login.php?role=doctor" class="role-card">
    <div class="role-icon">D</div>
    <h3>Doctor</h3>
    <p>Manage your schedule and consultations</p>
  </a>
  <a href="view/hospital_receptionist/receptionistLogin.php" class="role-card">
    <div class="role-icon">R</div>
    <h3>Receptionist</h3>
    <p>Handle walk-ins, check-ins and payments</p>
  </a>
  <a href="view/hospital_admin/adminLogin.php" class="role-card">
    <div class="role-icon">A</div>
    <h3>Admin</h3>
    <p>Oversee doctors, staff and reports</p>
  </a>
</section>

<?php include 'view/partials/PatientFooter.php'; ?>

</body>
</html>