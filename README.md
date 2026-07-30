# 📦 Inventory Management System (PHP & Bootstrap)

A responsive **Inventory Management System** built using **PHP**, **MySQL**, **Bootstrap 5**, **HTML**, **CSS**, and **JavaScript**. The application enables businesses to efficiently manage products, categories, users, inventory, stocks and reports through a secure role-based system.

---

## 🚀 Features

### 🔐 Authentication

* Secure Login System
* Session Management
* Logout Functionality

### 👨‍💼 Administrator

* Dashboard
* View Products
* View Categories
* Monitor Stocks
* View Reports
* Generate Reports
* View Users
* Manage Users
* Monitor Inventory

### 📦 Store Keeper

* Dashboard
* View Products
* Manage Product
* View Categories
* Manage Categories
* Update Stock Quantities
* Manage Inventory
* View Reports
* Generate Reports
* View Users

---

## 🛠️ Technologies Used

* PHP
* MySQL
* Bootstrap 5
* HTML5
* CSS3
* JavaScript
* XAMPP(--localhosting--)

---

## 📂 Project Structure

```text
Inventory-Management-System-PHP-Bootstrap/
│
├── actions/
├── admin/
├── assets/
│   ├── css/
│   └── images/
├── auth/
├── config/
├── database/
├── includes/
├── store/
├── index.php
└── README.md
```

---

## 💻 Installation

### Prerequisites

* PHP 8.x
* MySQL
* XAMPP (Recommended)

### Setup

1. Clone the repository

```bash
git clone https://github.com/yourusername/Inventory-Management-System-PHP-Bootstrap.git
```

2. Copy the project folder into the **htdocs** directory.

3. Start **Apache** and **MySQL** using XAMPP.

4. Import the database:

```text
database/Inventory-Management-System.sql
```

5. Configure your database connection in:

```text
config/db.php
```
```
<?php                                             
$conn = mysqli_connect("localhost", "root", "", "inventory-management-system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

6. Open your browser:

```text
http://localhost/Inventory-Management-System-PHP-Bootstrap
```

---

## 📸 Screenshots

Include screenshots such as:

* Login Page
* Admin Dashboard
* Product Management
* Stock Management
* Category Management
* Reports
* User Management


---

## 🎯 Key Learning Outcomes

This project demonstrates:

* PHP CRUD Operations
* MySQL Database Integration
* Session-Based Authentication
* Role-Based Access Control
* Inventory Management
* Bootstrap Responsive Design
* Modular PHP Development

---


## 👨‍💻 Author

**Sumudu Vihanga**

GitHub: https://github.com/SumuduVihanga1

---

This project is created for educational and portfolio purposes.
