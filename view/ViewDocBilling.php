<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorBilling.php';

$doctor_id = $_SESSION['doctor_id'];
$billingModel = new ModelDoctorBilling($conn);
$report = $billingModel->getEarningsReport($doctor_id);
$stats = $billingModel->getStats($doctor_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings & Statistics - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
        <!-- fixed CSS -->
    <style>
        body { display: flex; min-height: 100vh; background-color: #f3f4f6; }
        .sidebar { width: 250px; background-color: white; border-right: 1px solid var(--light-gray); padding: 20px; display: flex; flex-direction: column; }
        .sidebar .logo { font-size: 20px; font-weight: 700; margin-bottom: 30px; color: var(--dark); }
        .sidebar .logo span { color: var(--primary); }
        .sidebar-nav { list-style: none; flex-grow: 1; }
        .sidebar-nav li { margin-bottom: 10px; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px; border-radius: 8px; text-decoration: none; color: var(--gray); font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: var(--secondary); color: var(--primary); }
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .section-title { font-size: 18px; font-weight: 600; margin-bottom: 15px; color: var(--dark); margin-top: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); }
        .stat-card h3 { font-size: 14px; color: var(--gray); margin-bottom: 10px; }
        .stat-card .value { font-size: 28px; font-weight: 700; color: var(--dark); }
        .card { background-color: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px; border-bottom: 1px solid var(--light-gray); font-size: 14px; }
        th { background-color: #f9fafb; font-weight: 600; color: var(--gray); }
        .logout-btn { margin-top: auto; color: var(--danger); text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px; display: flex; align-items: center; border-radius: 8px; }
        .logout-btn:hover { background-color: #fee2e2; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php" class="active">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Earnings & Statistics</h1>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Completed</h3>
                <div class="value"><?php echo $stats['total_completed']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Cancelled</h3>
                <div class="value"><?php echo $stats['total_cancelled']; ?></div>
            </div>
            <div class="stat-card">
                <h3>No-Show Rate</h3>
                <div class="value"><?php echo $stats['no_show_rate']; ?>%</div>
            </div>
            <div class="stat-card">
                <h3>Consultation Fee</h3>
                <div class="value">$<?php echo htmlspecialchars($report['fee']); ?></div>
            </div>
        </div>

        <div class="section-title">Busiest Days & Times</div>
        <div class="card">
            <p><strong>Busiest Day:</strong> <?php echo $stats['busiest_day']; ?></p>
            <p><strong>Busiest Time:</strong> <?php echo $stats['busiest_time']; ?></p>
        </div>

        <div class="section-title">Daily Earnings (Last 7 Days with activity)</div>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Completed Appointments</th>
                        <th>Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($report['daily'])): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--gray);">No earnings data available for daily report.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($report['daily'] as $day): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($day['appointment_date'])); ?></td>
                                <td><?php echo $day['count']; ?></td>
                                <td>$<?php echo number_format($day['earnings'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section-title">Monthly Earnings</div>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Completed Appointments</th>
                        <th>Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($report['monthly'])): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--gray);">No earnings data available for monthly report.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($report['monthly'] as $month): ?>
                            <tr>
                                <td><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></td>
                                <td><?php echo $month['count']; ?></td>
                                <td>$<?php echo number_format($month['earnings'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
