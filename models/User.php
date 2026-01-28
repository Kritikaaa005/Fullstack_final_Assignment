<?php

class User
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE username = :username LIMIT 1"
        );
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
