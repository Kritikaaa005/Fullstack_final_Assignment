<?php
//Controllers receive requests from routes and decide what kind of response is needed
//calls model and validates
//this controller handles login and logout 
//Headers are metadata about the response

class AuthController
{
    //talks to user table
    private $userModel;
    private $blade;//renders blade template

    //injecting pdo into user model
    public function __construct($pdo, $blade)
    {
        $this->userModel = new User($pdo);
        $this->blade = $blade;
        //stores blade engine for rendering views
    }
//shows login page

    public function showLogin($error = null)
    {
        echo $this->blade->make("auth.login", [
            //loads views/auth/login.blade.php and passes error var to view...why? bcuz if login fails, err msg shld show
            //renders html
            'error' => $error
        ])->render();
    }

    //reads user input
    public function login()
    {
        //using null coalescing op to pass empty string if no name so undefined index err isn't shown
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        //check whether the request is ajax or normal form submission
        //if ajax json return, if form html
        //HTTP_X_REQUESTED_WITH...specific HTTP header often sent by browsers when making AJAX req
        //xmlhttprequest-> standard val of HTTP_X_REQUESTED_WITH header when req is AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        //handling missing val
        if ($username === '' || $password === '') {
            if ($isAjax) {
                header('Content-Type: application/json');//if ajax and empty, send raw HTTP header to browser saying the content to be received is gonna be json
                echo json_encode(['success' => false, 'message' => 'All fields are required']);//converting php array to json format
                return;
            }
            //if not ajax req, render login page and pass error msg to view
            $this->showLogin("All fields are required");
            return;
        }

        $user = $this->userModel->findByUsername($username);
        //queries db for user
//verifying or handling invalid login
        if (!$user || !password_verify($password, $user['password'])) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
                return;
            }
            $this->showLogin("Invalid username or password");
            return;
        }
//storing user info in session so they stay logged in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirect' => url('/')]);
            return;
        }

        header("Location: " . url('/'));
        exit;
    }

    public function logout()
    {
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        session_destroy();

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirect' => url('/login')]);
            exit;
        }

        header("Location: " . url('/login'));
        exit;
    }
}
