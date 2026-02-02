

# Full Stack PHP MVC Inventory Management System

## 1. Project Overview

This project is a **Full Stack Web Application** developed using **PHP** following the **MVC (Model–View–Controller)** architecture.
It provides a complete inventory management solution with **user authentication**, **product management**, and **supplier management**, backed by a **MySQL database**.

The application is built using a **custom PHP MVC framework** and uses **Blade as a standalone templating engine** (not Laravel).

---
## Project Repository

GitHub Repository:  
https://github.com/Kritikaaa005/Fullstack_final_Assignment.git
---
## 2. Tech Stack

### 2.1 Backend

* PHP (Custom MVC Framework)
* MySQL
* PDO (Prepared Statements)
* Composer
* Blade Templating Engine (Standalone)

### 2.2 Frontend

* HTML5
* CSS3
* JavaScript (Vanilla JS, AJAX)

### 2.3 Server & Tools

* Apache Server
* `.htaccess` for URL routing
* Session-based authentication

---

## 3. Project Structure

```
project_root/
│
├── config/             Database configuration
├── controllers/       Application controllers
├── core/              Router, Autoloader, Blade engine
├── models/            Database models
├── includes/          Authentication, helpers, security
├── public/            Public entry point
│   ├── assets/        CSS and JavaScript files
│   ├── index.php      Main application entry
│   └── .htaccess      URL rewriting
├── views/             Blade templates
│   ├── auth/
│   ├── products/
│   ├── suppliers/
│   └── layouts/
├── vendor/            Composer dependencies
├── cache/             Blade cache
├── database.sql       Database schema
└── composer.json
```

---

## 4. Login Credentials 

```
Username: kritika
Password: admin123
```

> These credentials are provided for **demonstration and evaluation purposes only**.
> Passwords are stored securely using `password_hash()`.

---

## 5. Setup Instructions

### Step 1: Clone / Copy Project

Place the project inside your Apache root directory:

* XAMPP → `htdocs`
* WAMP → `www`

### Step 2: Create Database

1. Open phpMyAdmin
2. Create a database named:

   ```
   np02cs4a240025
   ```
3. Import the file:

   ```
   np02cs4a240025.sql
   ```

### Step 3: Configure Database Connection

Edit `config/db.php` if required:

```php
$host = 'localhost';
$db_name = 'np02cs4a240025';
$username = 'root';
$password = '';
```

### Step 4: Install Dependencies

```bash
composer install
```

### Step 5: Enable Apache Rewrite

Ensure `.htaccess` is enabled and `mod_rewrite` is active.

### Step 6: Run the Application

```
http://localhost/project_root/public
```

---

## 6. Features Implemented

### 6.1 User Authentication

* User login and logout
* Password hashing
* Session-based authentication
* Protected routes for authenticated users

### 6.2 Product Management

* View all products
* Add new products
* Edit existing products
* Delete products
* Supplier association
* Low-stock highlighting

### 6.3 Supplier Management

* View suppliers
* Add suppliers
* Edit supplier details
* Delete suppliers

---

## 7. Application Architecture

* MVC architecture followed throughout
* Models handle database operations
* Controllers manage business logic
* Views render data using Blade templates
* Central routing system
* Single entry point (`public/index.php`)

---

## 8. Application Flow

1. User sends request
2. Request enters through `public/index.php`
3. Router maps URL to controller
4. Authentication is checked
5. Controller processes request
6. Model interacts with database
7. Data is returned to controller
8. View is rendered using Blade
9. Response sent to user

---

## 9. Data Flow

```
User
 → Router
 → Controller
 → Model
 → Database
 → Model
 → Controller
 → View
 → User
```

---

## 10. Security Measures

* **SQL Injection Prevention**

  * PDO prepared statements
* **XSS Prevention**

  * Escaped output using Blade / `htmlspecialchars()`
* **Authentication**

  * Session-based access control
* **Separation of Concerns**

  * No database access in views

---

## 11. Known Issues

* Limited advanced validation
* No pagination for large datasets
* Basic UI design

---

## 12. Future Enhancements

* Improved server-side validation
* Pagination and search functionality
* REST API support
* Enhanced UI/UX

---

## 13. Conclusion

This project demonstrates the implementation of a **secure, scalable PHP MVC web application** with proper separation of concerns, authentication, and CRUD functionality. It showcases full-stack development skills using core PHP concepts and modern best practices.

---

