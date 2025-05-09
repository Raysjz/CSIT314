<?php
require_once(__DIR__ . '/../../entities/UserProfile.php');

class SuspendUserProfileController {
    public function getUserProfileById($userId) {
        return UserProfile::getUserProfileById($userId);
    }
    public function suspendUserProfile($userId) {
        $user = $this->getUserProfileById($userId);
        if ($user) {
            return $user->suspendUserProfile();
        }
        return false;
    }
}

?>
