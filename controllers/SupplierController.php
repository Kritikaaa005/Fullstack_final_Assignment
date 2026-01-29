<?php
//handles CRUD related tasks for suppliers

class SupplierController
{

    private $supplier;
    private $blade;

    public function __construct($conn, $blade)
    {
        $this->supplier = new Supplier($conn);
        $this->blade = $blade;
    }
//show all suppliers
    public function index()
    {
        $suppliers = $this->supplier->getAll();

        echo $this->blade->make('suppliers.index', [
            'suppliers' => $suppliers
        ])->render();
    }
//add supplier
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['name'])) {
               
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Supplier name is required']);
                exit;
            }

            $result = $this->supplier->create($_POST);

            if ($result) {
                // Get the newly created supplier
                $lastId = $this->supplier->getLastInsertId();
                $newSupplier = $this->supplier->getById($lastId);

                // Return JSON for AJAX
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Supplier added successfully',
                    'supplier' => $newSupplier
                ]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to add supplier']);
                exit;
            }
        }

        // for GET req
        $suppliers = $this->supplier->getAll();
        echo $this->blade->make('suppliers.index', [
            'suppliers' => $suppliers
        ])->render();
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id)
            die('id missing');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['name'])) {
               
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Supplier name is required']);
                exit;
            }

            $result = $this->supplier->update($id, $_POST);

           
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Supplier updated successfully' : 'Failed to update supplier'
            ]);
            exit;
        }

        $supplier = $this->supplier->getById($id);
        echo $this->blade->make('suppliers.edit', [
            'supplier' => $supplier
        ])->render();
    }

    public function delete()
    {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $id = $_POST['id'] ?? null;
        if ($id) {
            $result = $this->supplier->delete($id);

           
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Supplier deleted successfully' : 'Failed to delete supplier'
            ]);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID missing']);
        exit;
    }
}
?>