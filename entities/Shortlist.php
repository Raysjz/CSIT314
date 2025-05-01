<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

// entities/Shortlist.php
class Shortlist {
    public $shortlist_id;
    public $homeowner_account_id;
    public $service_id;
    public $is_deleted;
    
    public function __construct($shortlist_id, $homeowner_account_id, $service_id, $is_deleted) {
            $this->shortlist_id = $shortlist_id;
            $this->homeowner_account_id = $homeowner_account_id;
            $this->service_id = $service_id;
            $this->is_deleted = $is_deleted;
        }



    public static function add($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        // Check if an active (not deleted) record exists
        $stmt = $db->prepare("SELECT 1 FROM service_shortlists WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id AND is_deleted = FALSE");
        $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
        if ($stmt->fetch()) {
            return false; // Already in shortlist
        }
        // Insert new shortlist entry, regardless of any previous deleted ones
        $stmt = $db->prepare("INSERT INTO service_shortlists (homeowner_account_id, service_id, is_deleted) VALUES (:homeowner_id, :service_id, FALSE)");
        return $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
    }
    

    public static function getShortlistedServices($homeownerAccountId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT ss.shortlist_id, cs.* FROM service_shortlists ss
                              JOIN cleaner_services cs ON ss.service_id = cs.service_id
                              WHERE ss.homeowner_account_id = :homeowner_id AND ss.is_deleted = false");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    

    public static function removeFromShortlist($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE service_shortlists SET is_deleted = true WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getShortlistId($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT shortlist_id FROM service_shortlists WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id AND is_deleted = FALSE ORDER BY shortlisted_at DESC LIMIT 1");
        $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['shortlist_id'] : null;
    }
    
    
    
    
}

?>