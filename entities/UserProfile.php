<?php
// User Profile Entity

// Include dependencies
require_once(__DIR__ . '/../ConnectiontoDB.php');

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

    // Validate profile before creation
    public function validateUP() {
        if (empty($this->name)) {
            return "All fields are required.";
        }
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT 1 FROM user_profiles WHERE profile_name = :name");
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        if ($stmt->fetch()) {
            return "Profile Name already exists.";
        }
        return "Validation passed.";
    }

    // Save profile to database
    public function saveUserProfile() {
        $db = Database::getPDO();
        $stmt = $db->prepare("INSERT INTO user_profiles (profile_name, is_suspended) VALUES (:name, :isSuspended)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    // Get user profile by ID
    public static function getUserProfileById($id) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM user_profiles WHERE profile_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserProfile(
                $user['profile_id'],
                $user['profile_name'],
                isset($user['is_suspended']) ? (bool)$user['is_suspended'] : false
            );
        }
        return null;
    }

    // Update profile in database
    public function updateUserProfile() {
        $db = Database::getPDO();
        $stmt = $db->prepare(
            "UPDATE user_profiles SET profile_name = :name, is_suspended = :isSuspended WHERE profile_id = :id"
        );
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    // Suspend this user profile
    public function suspendUserProfile() {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE user_profiles SET is_suspended = true WHERE profile_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

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

    // Get paginated user profiles
    public static function getPaginatedProfiles($perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM user_profiles ORDER BY profile_id ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userProfiles = [];
        foreach ($result as $row) {
            $userProfiles[] = new UserProfile(
                $row['profile_id'],
                $row['profile_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
        return $userProfiles;
    }

    // Count all user profiles
    public static function countAllProfiles() {
        $db = Database::getPDO();
        $stmt = $db->query("SELECT COUNT(*) FROM user_profiles");
        return (int)$stmt->fetchColumn();
    }

    // Search user profiles with pagination
    public static function searchUserProfilesPaginated($searchQuery, $perPage, $offset) {
        $db = Database::getPDO();
        $pattern = "%$searchQuery%";
        $stmt = $db->prepare(
            "SELECT * FROM user_profiles
             WHERE profile_name ILIKE :pattern OR profile_id::text ILIKE :pattern
             ORDER BY profile_id ASC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userProfiles = [];
        foreach ($result as $row) {
            $userProfiles[] = new UserProfile(
                $row['profile_id'],
                $row['profile_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
        return $userProfiles;
    }

    // Count profiles matching a search query
    public static function countSearchProfiles($searchQuery) {
        $db = Database::getPDO();
        $pattern = "%$searchQuery%";
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM user_profiles
             WHERE profile_name ILIKE :pattern OR profile_id::text ILIKE :pattern"
        );
        $stmt->bindValue(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
?>
