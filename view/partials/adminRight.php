<div class="right">
    <div class="card">
        <h3>Quick Actions</h3>
        <div class="quick-links">
            <a href="../../controllers/adminManageDoctorsController.php">Add Doctor</a>
            <a href="../../controllers/adminManageSpecializationsController.php">Add Specialization</a>
            <a href="../../controllers/adminManageReceptionistsController.php">Add Receptionist</a>
            <a href="../../controllers/adminAnnouncementsController.php">New Announcement</a>
            <a href="../../controllers/adminAllAppointmentsController.php">View Appointments</a>
            <a href="../../controllers/adminBillingDashboardController.php">Billing Dashboard</a>
        </div>
    </div>
</div>

<style>
.right {
    width: 240px;
    padding: 24px 14px;
    flex-shrink: 0;
}

.quick-links a {
    display: block;
    padding: 9px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
    margin-bottom: 6px;
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}

.quick-links a:hover {
    background-color: #e8f0fe;
    color: #0033a0;
}
</style>