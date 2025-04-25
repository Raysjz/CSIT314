<?php
class UserAccountController {
    public function create($data) {
        // Ensure the profile is set and not empty
        if (empty($data['profile'])) {
            return "❌ Profile is required.";  // Ensure that profile is provided
        }

        // Create the user object
        $user = new UserAccount(
            null,  // ID auto-generated
            $data['username'],
            $data['password'],
            $data['profile'],
            isset($data['is_suspended']) ? $data['is_suspended'] : false  // Default to false if not set
        );

        // Validate user data
        $validation = $user->validateUserAccount();  // Check if the user data is valid
        if ($validation === "Validation passed.") {
            // Proceed to save user if validation is successful
            return $user->saveUser();
        } else {
            // Return validation error message if failed
            return $validation;
        }
    }
}

?>