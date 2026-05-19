<?php

class ModelDoctorBilling {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getEarningsReport($doctor_id) {
        // Get consultation fee
        $sql = "SELECT consultation_fee FROM doctors WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $fee = $row['consultation_fee'] ?? 0;

        // Daily earnings
        $sql_daily = "SELECT appointment_date, COUNT(*) as count 
                      FROM appointments 
                      WHERE doctor_id = ? AND status = 'completed' 
                      GROUP BY appointment_date 
                      ORDER BY appointment_date DESC LIMIT 7";
        $stmt_daily = mysqli_prepare($this->conn, $sql_daily);
        mysqli_stmt_bind_param($stmt_daily, "i", $doctor_id);
        mysqli_stmt_execute($stmt_daily);
        $res_daily = mysqli_stmt_get_result($stmt_daily);
        
        $daily = [];
        while ($r = mysqli_fetch_assoc($res_daily)) {
            $r['earnings'] = $r['count'] * $fee;
            $daily[] = $r;
        }

        // Monthly earnings
        $sql_monthly = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as count 
                        FROM appointments 
                        WHERE doctor_id = ? AND status = 'completed' 
                        GROUP BY month 
                        ORDER BY month DESC LIMIT 12";
        $stmt_monthly = mysqli_prepare($this->conn, $sql_monthly);
        mysqli_stmt_bind_param($stmt_monthly, "i", $doctor_id);
        mysqli_stmt_execute($stmt_monthly);
        $res_monthly = mysqli_stmt_get_result($stmt_monthly);
        
        $monthly = [];
        while ($r = mysqli_fetch_assoc($res_monthly)) {
            $r['earnings'] = $r['count'] * $fee;
            $monthly[] = $r;
        }

        return [
            'fee' => $fee,
            'daily' => $daily,
            'monthly' => $monthly
        ];
    }

    public function getStats($doctor_id) {
        $stats = [
            'total_completed' => 0,
            'total_cancelled' => 0,
            'total_noshow' => 0,
            'no_show_rate' => 0,
            'busiest_day' => 'N/A',
            'busiest_time' => 'N/A'
        ];

        // Counts
        $sql = "SELECT status, COUNT(*) as count FROM appointments WHERE doctor_id = ? GROUP BY status";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $total = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status'] === 'completed') $stats['total_completed'] = $row['count'];
            if ($row['status'] === 'cancelled') $stats['total_cancelled'] = $row['count'];
            if ($row['status'] === 'no_show') $stats['total_noshow'] = $row['count'];
            $total += $row['count'];
        }

        if ($total > 0) {
            $stats['no_show_rate'] = round(($stats['total_noshow'] / $total) * 100, 2);
        }

        // Busiest Day
        $sql_day = "SELECT appointment_date, COUNT(*) as count FROM appointments WHERE doctor_id = ? GROUP BY appointment_date ORDER BY count DESC LIMIT 1";
        $stmt_day = mysqli_prepare($this->conn, $sql_day);
        mysqli_stmt_bind_param($stmt_day, "i", $doctor_id);
        mysqli_stmt_execute($stmt_day);
        $res_day = mysqli_stmt_get_result($stmt_day);
        if ($r = mysqli_fetch_assoc($res_day)) {
            $stats['busiest_day'] = date('l', strtotime($r['appointment_date']));
        }

        // Busiest Time
        $sql_time = "SELECT appointment_time, COUNT(*) as count FROM appointments WHERE doctor_id = ? GROUP BY appointment_time ORDER BY count DESC LIMIT 1";
        $stmt_time = mysqli_prepare($this->conn, $sql_time);
        mysqli_stmt_bind_param($stmt_time, "i", $doctor_id);
        mysqli_stmt_execute($stmt_time);
        $res_time = mysqli_stmt_get_result($stmt_time);
        if ($r = mysqli_fetch_assoc($res_time)) {
            $stats['busiest_time'] = date('h:i A', strtotime($r['appointment_time']));
        }

        return $stats;
    }
}
?>
