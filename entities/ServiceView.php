<?php
class ServiceView {
    public static function logView($serviceId, $viewerAccountId = null) {
        $db = Database::getPDO();
        $stmt = $db->prepare("INSERT INTO service_views (service_id, viewer_account_id) VALUES (:service_id, :viewer_account_id)");
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
}

?>