<?php

$host = "localhost";
$db_name = "inventory_db";   // change this on college server
$username = "root";          // change this on college server
$password = "";              // change this on college server

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $conn;
