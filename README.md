# library-management-laravel-crud-app
A Laravel 10/11 web application for managing books and categories with authentication, CRUD operations, filtering, pagination, and ownership-based authorization.

🔧 Features
-User Authentication (Register / Login / Logout)
-CRUD operations for Books
-CRUD operations for Categories
-Many-to-Many relationship between Books and Categories
-Search, category filtering, and sorting
-Pagination (5 items per page)
-Ownership Policy (users can edit/delete only their own books)
-Form validation and flash messages

🛠 Tech Stack
-Laravel 10/11
-PHP 8.2+
-MySQL 8
-Blade Templates
-Eloquent ORM

📚 Database Structure
-Users → Books (1:N)
-Books ↔ Categories (N:N) via pivot table

Thank you for checking out this project.
Feedback and suggestions are always welcome.
