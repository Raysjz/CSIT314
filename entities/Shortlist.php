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

    /**
     * Add a service to the homeowner's shortlist.
     * Returns the new shortlist_id on success, or false if already shortlisted.
     */
    public static function add($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();

        // Check if an active (not deleted) record exists
        $stmt = $db->prepare("SELECT shortlist_id FROM service_shortlists WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id AND is_deleted = FALSE");
        $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            // Already in shortlist, return existing shortlist_id
            return false;
        }

        // Insert new shortlist entry
        $stmt = $db->prepare("INSERT INTO service_shortlists (homeowner_account_id, service_id, is_deleted) VALUES (:homeowner_id, :service_id, FALSE)");
        $success = $stmt->execute([
            ':homeowner_id' => $homeownerAccountId,
            ':service_id' => $serviceId
        ]);

        if ($success) {
            return $db->lastInsertId(); // return the new shortlist_id
        } else {
            return false;
        }
    }

    /**
     * Get all shortlisted services for a homeowner.
     * Returns an array of objects with shortlist_id and service info.
     */
    public static function getShortlistedServices($homeownerAccountId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT ss.shortlist_id, cs.* FROM service_shortlists ss
                              JOIN cleaner_services cs ON ss.service_id = cs.service_id
                              WHERE ss.homeowner_account_id = :homeowner_id AND ss.is_deleted = false");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Remove a shortlist entry by homeowner_account_id and service_id (soft delete).
     */
    public static function removeFromShortlist($homeownerAccountId, $serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE service_shortlists SET is_deleted = true WHERE homeowner_account_id = :homeowner_id AND service_id = :service_id");
        $stmt->bindParam(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Remove a shortlist entry by shortlist_id (soft delete).
     */
    public static function removeByShortlistId($shortlistId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE service_shortlists SET is_deleted = true WHERE shortlist_id = :shortlist_id");
        $stmt->bindParam(':shortlist_id', $shortlistId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get the shortlist_id for a given homeowner and service (active only).
     */
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

    /**
     * Get a shortlist entry by shortlist_id.
     */
    public static function getById($shortlistId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM service_shortlists WHERE shortlist_id = :shortlist_id");
        $stmt->execute([':shortlist_id' => $shortlistId]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ? $row : null;
    }

    /**
     * Count how many times a service has been shortlisted (including deleted).
     */
    public static function countShortlists($serviceId) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT COUNT(*) FROM service_shortlists WHERE service_id = :service_id");
        $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
?>
