<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

// entities/Shortlist.php
class Shortlist {
    public static function add($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        // Prevent duplicate entries
        $stmt = $db->prepare("SELECT 1 FROM service_shortlists WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id");
        $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
        if ($stmt->fetch()) {
            return false; // Already in shortlist
        }
        // Insert new shortlist entry
        $stmt = $db->prepare("INSERT INTO service_shortlists (homeowner_account_id, service_id) VALUES (:homeowner_id, :service_id)");
        return $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
    }

    public static function getShortlistedServices($homeownerAccountId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT cs.* FROM service_shortlists ss
                              JOIN cleaner_services cs ON ss.service_id = cs.service_id
                              WHERE ss.homeowner_account_id = :homeowner_id");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function removeFromShortlist($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("DELETE FROM service_shortlists WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    
}

?>