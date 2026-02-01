# Full Stack PHP MVC Application

This is a full-stack web application developed using PHP following the MVC (Model-View-Controller) architecture. The application includes user authentication, product management, and supplier management with a MySQL database backend. It is designed to demonstrate full-stack development concepts including routing, session handling, CRUD operations, and separation of concerns.

---

## Tech Stack

Backend:
- PHP (Custom MVC Framework)
- MySQL
- Composer

Frontend:
- HTML5
- CSS3
- JavaScript (Vanilla)

Server & Tools:
- Apache Server
- .htaccess for routing
- Blade Templating Engine
- CarbonPHP library

---

## Project Structure

project_root/
- config/        → Database configuration
- controllers/  → Application controllers
- core/         → Router, Autoloader, Blade engine
- models/       → Database models
- includes/     → Helper and security files
- public/       → Public entry point
  - assets/     → CSS and JavaScript
  - index.php   → Main application entry
- vendor/       → Composer dependencies
- cache/        → Cached files
- database.sql  → Database schema
- composer.json → Composer configuration

---

## Implemented Features

User Authentication:
- User registration with hashed passwords
- User login and logout
- Session-based authentication
- Protected routes accessible only to logged-in users

Product Management:
- View all products
- Add new products
- Update existing products
- Delete products
- Products are associated with suppliers

Supplier Management:
- View all suppliers
- Add new suppliers
- Update supplier details
- Delete suppliers

Architecture:
- MVC pattern used throughout the application
- Models handle database operations
- Controllers handle application logic
- Views render data using Blade templates
- Central routing system for request handling

Security:
- Password hashing
- Session-based access control
- No direct database access from views

---

## Application Flow

1. All requests enter through public/index.php  
2. The router parses the URL and maps it to a controller and method  
3. Authentication is checked for protected routes  
4. Controllers request data from models  
5. Models interact with the database  
6. Data is returned to controllers  
7. Controllers render views using Blade  
8. User interacts with the interface  

Authentication Flow:
- User registers or logs in
- Credentials are validated
- Session is created on success
- User is redirected to dashboard
- Logout destroys session and redirects to login page

Product & Supplier Flow:
- User selects a module (Products or Suppliers)
- CRUD operations are performed through controllers
- Database is updated via models
- Updated data is displayed in views

---

## Data Flow

User Request  
→ Router  
→ Controller  
→ Model  
→ Database  
→ Model  
→ Controller  
→ View  
→ User Interface  



## Future Enhancements

- Improved input validation
- Search and pagination
- API integration
- Enhanced UI/UX


