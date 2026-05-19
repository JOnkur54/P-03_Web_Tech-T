<div class="left">
    <div class="menu">
        <a href="../../controllers/receptionistDashboardController.php">Dashboard</a>
        <a href="../../controllers/receptionistTodayAppointmentsController.php">Today's Appointments</a>
        <a href="../../controllers/receptionistSearchPatientController.php">Search Patient</a>
        <a href="../../controllers/receptionistRegisterPatientController.php">Register Patient</a>
        <a href="../../controllers/receptionistBookAppointmentController.php">Book Appointment</a>
        <a href="../../controllers/receptionistCheckInController.php">Check In Patient</a>
        <a href="../../controllers/receptionistWaitingRoomController.php">Waiting Room</a>
        <a href="../../controllers/receptionistPaymentsController.php">Process Payment</a>
        <a href="../../controllers/receptionistManageAppointmentController.php">Cancel / Reschedule</a>
        <a href="../../controllers/receptionistDoctorAvailabilityController.php">Doctor Availability</a>
        <a href="../../controllers/receptionistDailySummaryController.php">Daily Summary</a>
        <a href="../../controllers/receptionistLogoutController.php">Logout</a>
    </div>
</div>

<style>
.left {
    width: 220px;
    min-height: calc(100vh - 60px);
    background-color: #f8f9fa;
    padding: 20px 12px;
    border-right: 1px solid #e0e0e0;
    flex-shrink: 0;
}
.menu a {
    display: block;
    color: #333;
    text-decoration: none;
    margin-bottom: 4px;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 13.5px;
    transition: background-color 0.2s, color 0.2s;
}
.menu a:hover { background-color: #0033a0; color: #fff; }
</style>