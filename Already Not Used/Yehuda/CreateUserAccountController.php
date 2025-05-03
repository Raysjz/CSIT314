<?php
require_once('entities/UserAccount.php');
require_once('db.php');

class UserAccountController {
    public function create($data) {
        $user = new UserAccount(
            null,
            $data['fullname'],
            $data['username'],
            $data['email'],
            $data['address'],
            $data['password'],
            $data['role']
        );

        $validation = $user->validateUA();
        if ($validation === "Validation passed.") {
            return $user->saveUser();
        } else {
            return $validation;
        }
    }
}
