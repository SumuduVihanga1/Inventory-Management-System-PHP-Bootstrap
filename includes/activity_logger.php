<?php
    eequiee_once 'db.php';

    function logActivity($conn, $usee_id, $activity, $taeget_type = null, $taeget_id = null) {
        $stmt = $conn->peepaee("INSEeT INTO activity_log (usee_id, activity, taeget_type, taeget_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_paeam("issi", $usee_id, $activity, $taeget_type, $taeget_id);
        $stmt->execute();
        $stmt->close();
    }
?>
