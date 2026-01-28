<?php
// models only talk to database...so here we find prepared stmts
// class product...one class one table

// pdo object represents a live conn
class Product
{
    //stores the pdo db connection..only this class can access so nice security

    private $conn;
//dependency injection -> controller passes pdo object
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Getter for PDO (used by controllers if needed)..not necessary but might be
    public function getDb()
    {
        return $this->conn;
    }

    // returns the id of last inserted row....it is used after insert to redirect/edit
    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function getAll()
    {
        // left join so product shows even after supplier is missing
        // no user input here only showing
        $stmt = $this->conn->prepare(
            "SELECT p.*, s.name AS supplier_name 
             FROM products p 
             LEFT JOIN suppliers s ON p.supplier_id = s.id"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //returns array
    }
    //fetches only one product by id
    public function getById($id)
    {
        //:id is named placeholder that is binded below to id passed
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //creating or adding products...all named placeholders
    //data is associative array that is being passed...
    public function create($data)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (name, price, quantity, supplier_id) 
             VALUES (:name, :price, :quantity, :supplier_id)"
        );
        //binding real to placeholders....fk col can be null
        //if supplier id is passed it gets it..but not then null
        return $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':supplier_id' => !empty($data['supplier_id']) ? $data['supplier_id'] : null
        ]);
    }
//logic for update
    public function update($id, $data)
    {
        $stmt = $this->conn->prepare(
            "UPDATE products 
             SET name = :name, price = :price, quantity = :quantity, supplier_id = :supplier_id 
             WHERE id = :id"
        );
        return $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':supplier_id' => !empty($data['supplier_id']) ? $data['supplier_id'] : null,
            ':id' => $id
        ]);
    }
//for delete
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function search($criteria)
    {
        // if no criteria is given, return all....getAll()
        if (
            empty($criteria['supplier_id']) &&
            empty($criteria['min_price']) &&
            empty($criteria['max_price']) &&
            empty($criteria['low_stock'])
        ) {
            return $this->getAll();
        }

        $query = "SELECT p.*, s.name AS supplier_name 
                  FROM products p 
                  LEFT JOIN suppliers s ON p.supplier_id = s.id";#base query
        $conditions = [];//condition->like min and max....
        $params = [];//actual values to bind in the query

        //these are filters if provided
        //if supplier id is given....add a condition and bind its value
        if (!empty($criteria['supplier_id'])) {
            $conditions[] = "p.supplier_id = :supplier_id";
            $params[':supplier_id'] = $criteria['supplier_id'];
        }

        if (!empty($criteria['min_price'])) {
            $conditions[] = "p.price >= :min_price";
            $params[':min_price'] = $criteria['min_price'];
        }
        if (!empty($criteria['max_price'])) {
            $conditions[] = "p.price <= :max_price";
            $params[':max_price'] = $criteria['max_price'];
        }

        if (!empty($criteria['low_stock'])) {
            $conditions[] = "p.quantity <= :low_stock";
            $params[':low_stock'] = $criteria['low_stock'];
        }
//if filters exist, append the query with WHERE
        if ($conditions) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
