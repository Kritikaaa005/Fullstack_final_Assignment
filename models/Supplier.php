<?php
//conn is dependency injection
class Supplier {
    private $conn;
#ALL POSITIONAL PLACEHOLDERS ARE USED
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM suppliers WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO suppliers (name, contact) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['contact']]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE suppliers SET name=?, contact=? WHERE id=?");
        return $stmt->execute([$data['name'], $data['contact'], $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM suppliers WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function getDb() {
        return $this->conn;
    }
}
?>
