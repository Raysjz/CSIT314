<?php
require_once(__DIR__ . '/db.php');

class UserProfile {
    protected $id;
    protected $name;
    protected $isSuspended;

    // Constructor
    public function __construct($id, $name, $isSuspended) {
        $this->id = $id;
        $this->name = $name;
        $this->isSuspended = $isSuspended;
    }

    // --- Getters ---
    public function getProfileId()   { return $this->id; }
    public function getName()        { return $this->name; }
    public function getIsSuspended() { return $this->isSuspended; }

    // Get all active (not suspended) profiles
    public static function getProfiles() {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT profile_id, profile_name FROM user_profiles WHERE is_suspended = false ORDER BY profile_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get profile ID by profile name
    public static function getProfileIdByName($profileName) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT profile_id FROM user_profiles WHERE profile_name = :name");
        $stmt->bindParam(':name', $profileName);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['profile_id'] : null;
    }

}

class UserProfileController
{
    // Fetch all profiles (for dropdowns)
    public function getProfiles()
    {
        return UserProfile::getProfiles();
    }

    // Fetch profile ID by profile name
    public function getProfileIdByName($profileName)
    {
        return UserProfile::getProfileIdByName($profileName);
    }
}
?>
