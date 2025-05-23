# 📦 Inventory & Order Management System (PHP + SQL Server)

This is a simple web-based application built using **PHP** and **Microsoft SQL Server** (tested via SQL Server Management Studio) that simulates an eCommerce backend with supplier management, stock control via triggers, and order processing.

---

## 🚀 Features

- Supplier & Product management
- Trigger-based stock updates
- Customer order placement
- Dynamic reporting via SQL joins
- Business logic handled using **stored procedures** and **triggers**

---

## 🛠️ Technologies Used

- PHP (tested with PHP 8+)
- SQL Server (Tested with SSMS 20)
- HTML/CSS (basic UI)
- `sqlsrv` PHP extension

---

## 🧰 Requirements

- [XAMPP](https://www.apachefriends.org/) or similar PHP server environment
- SQL Server installed (with Management Studio - SSMS)
- SQLSRV driver for PHP: [Instructions](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server)

---

## 🗃️ Database Setup

1. Open **SQL Server Management Studio (SSMS)**.
2. Create a new database:  
   ```sql
  
   use BaloonLK;
