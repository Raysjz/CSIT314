<?php
require 'db.php';

$hash = password_hash("1234", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password, profile) VALUES (?, ?, ?)");
$stmt->execute(['admin', $hash, 'User Admin']);

echo "User created!";
