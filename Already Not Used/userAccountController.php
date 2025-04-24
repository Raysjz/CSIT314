<?php
require_once(__DIR__ . '/entities/CreateUserAccount.php');


class UserAccountController {
    public function create($data) {
        // Include isSuspended as part of the input data
        $user = new UserAccount(
            null,
            $data['username'],
            $data['password'],
            $data['profile'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false  // Default to false if not set
        );

        // Validate user data
        $validation = $user->validateUA();
        if ($validation === "Validation passed.") {
            return $user->saveUser();
        } else {
            return $validation;
        }
    }
}
