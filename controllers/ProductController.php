<?php
//responsible for product related CRUD, search and stock checks

class ProductController
{

    private $product;
    private $blade;

    public function __construct($conn, $blade)
    {
        $this->product = new Product($conn);
        $this->blade = $blade;
    }

    // fetch all products with suppliers
    public function index()
    {
        $products = $this->product->getAll();

        // fetching all suppliers for dropdown in the view
        $supplierModel = new Supplier($this->product->getDb());
        $suppliers = $supplierModel->getAll();

        echo $this->blade->make('products.index', [
            'products' => $products,
            'suppliers' => $suppliers,
        ])->render();
        //renders the products.index view
        //passes both products and suppliers
    }

    // add new prod
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {



            if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['quantity'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Name, price, and quantity are required']);
                exit;
            }

            if (
                !is_numeric($_POST['price']) || $_POST['price'] < 0 ||
                !is_numeric($_POST['quantity']) || $_POST['quantity'] < 0
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Price and quantity must be positive numbers']);
                exit;
            }
//calling create for insertion
            $result = $this->product->create($_POST);
//if insertion successfull, fetch new inserted prod by id
            if ($result) {
                $lastId = $this->product->getLastInsertId();
                $newProduct = $this->product->getById($lastId);

                // if prod has a supplier, fetch supplier's name or else set it to '-'
                if (!empty($newProduct['supplier_id'])) {

                    $supplierModel = new Supplier($this->product->getDb());
                    $supplier = $supplierModel->getById($newProduct['supplier_id']);
                    $newProduct['supplier_name'] = $supplier['name'] ?? '-';
                } else {
                    $newProduct['supplier_name'] = '-';
                }
//json msg after successful add and failure msg
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Product added successfully',
                    'product' => $newProduct
                ]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to create product']);
                exit;
            }
        }

        // if not post req but GET , redirect to index
        header("Location: " . url('/products'));
        exit;
    }

    // edit product (GET shows form, POST updates via AJAX)
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id)
            die('ID missing');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['quantity'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                exit;
            }

            $result = $this->product->update($id, $_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Product updated successfully' : 'Failed to update product'
            ]);
            exit;
        }

        $product = $this->product->getById($id);

        // Fetch suppliers for the dropdown
        $supplierModel = new Supplier($this->product->getDb());
        $suppliers = $supplierModel->getAll();

        echo $this->blade->make('products.edit', [
            'product' => $product,
            'suppliers' => $suppliers,
        ])->render();
    }

    // delete product via AJAX
    public function delete()
    {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'GET method not allowed']);
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID is missing']);
            exit;
        }

        $result = $this->product->delete($id);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Product deleted successfully' : 'Failed to delete product'
        ]);
        exit;
    }

    // Advanced search
    public function search()
    {
        //collects search criterai from POST
        $criteria = $_POST ?? [];
        $products = $this->product->search($criteria);

        $supplierModel = new Supplier($this->product->getDb());
        $suppliers = $supplierModel->getAll();

        echo $this->blade->make('products.search', [
            'products' => $products,
            'suppliers' => $suppliers,

            'criteria' => $criteria
        ])->render();//render search result views
    }

    // get low stock notification data (AJAX)
    public function getLowStock()
    {
        header('Content-Type: application/json');
        try {
            $allProducts = $this->product->getAll();

            // filter products with low stock (≤10)
            $lowStockProducts = array_filter($allProducts, function ($p) {
                return $p['quantity'] <= 10;
            });

            echo json_encode([
                'success' => true,
                'count' => count($lowStockProducts),
                'products' => array_values($lowStockProducts),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to check stock levels'
            ]);
        }
    }
}
