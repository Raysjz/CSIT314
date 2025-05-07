<?php
//Service View Entity
class ServiceView {
    public static function logView($serviceId, $viewerAccountId = null) {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            INSERT INTO service_views (service_id, viewer_account_id)
            VALUES (:service_id, :viewer_account_id)
            ON CONFLICT (service_id, viewer_account_id)
            DO UPDATE SET viewed_at = CURRENT_TIMESTAMP
        ");
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        if ($viewerAccountId) {
            $stmt->bindParam(':viewer_account_id', $viewerAccountId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':viewer_account_id', null, PDO::PARAM_NULL);
        }
        $stmt->execute();
    }

    public static function countViews($serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT COUNT(*) FROM service_views WHERE service_id = :service_id");
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    //---------Platform Generate Report-----------------
    public static function countViewsDaily() {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) AS service_views
                FROM service_views
                WHERE viewed_at::date = CURRENT_DATE";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['service_views'] : 0;
    }

    public static function countViewsWeekly() {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) AS service_views
                FROM service_views
                WHERE viewed_at >= CURRENT_DATE - INTERVAL '6 days'";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['service_views'] : 0;
    }

    public static function countViewsMonthly() {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) AS service_views
                FROM service_views
                WHERE DATE_TRUNC('month', viewed_at) = DATE_TRUNC('month', CURRENT_DATE)";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['service_views'] : 0;
    }
}

?>