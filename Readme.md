# 🍽️ College Canteen Billing System (PHP & MySQL)

A full billing and menu management system for a college canteen built using **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**.  
Designed to make ordering, cart management, and bill generation fast, secure, and easy for both students and admins.

---

## 🚀 Features

### 🔐 Authentication
- Secure Login System with **encrypted passwords (hashed)**
- Role-based access: `admin` and `user`

### 🧑‍💼 Admin Panel
- Add/Delete menu items (food & beverages)
- Upload product images
- View all generated bills
- Manage bill history
- Common navigation bar across all pages

### 🧾 Billing System
- Add items to cart dynamically
- Update quantity using ➕ ➖ ❌ buttons
- Bill can be generated only if cart is not empty
- Unique bill number using timestamp
- Print bill feature
- Responsive UI with scrollable menu list

### 🍕 Menu Items Included
- Snacks (Sandwich, Vada Pav, Samosa, etc.)
- Fast Food (Fries, Burger, Biryani)
- Beverages (Tea, Lemon Juice, Coffee)
- Meals & Desserts
- Soft Drinks & Water

---

## 🛠️ Tech Stack
| Technology | Usage |
|---|---|
| PHP | Backend Logic |
| MySQL | Database |
| HTML/CSS | Frontend UI |
| JavaScript | Cart & Dynamic Menu |
| XAMPP | Local Server |

---

## 📁 Project Structure

```
canteen/
│── admin/
│   └── menu.php
│── images/
│── db.php
│── login.php
│── index.php
│── generate_bill.php
│── print_bill.php
│── load_items.php
│── README.md
```

---

## 🗄️ Database Setup

Run the following SQL in phpMyAdmin:

```sql
DROP DATABASE IF EXISTS canteen_db;
CREATE DATABASE canteen_db;
USE canteen_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);

CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL DEFAULT 'default.png'
);

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_no VARCHAR(50) UNIQUE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    cashier VARCHAR(100) NOT NULL,
    bill_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bill_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_no VARCHAR(50) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    qty INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (bill_no) REFERENCES bills(bill_no) ON DELETE CASCADE
);

INSERT INTO menu_items (item_name, price, category, image) VALUES
('Veg Sandwich', 40.00, 'Snacks', 'sandwich.png'),
('Cheese Sandwich', 60.00, 'Snacks', 'cheese_sandwich.png'),
('Vada Pav', 20.00, 'Snacks', 'vada_pav.png'),
('Samosa', 15.00, 'Snacks', 'samosa.png'),
('Cold Coffee', 50.00, 'Beverages', 'cold_coffee.png'),
('Tea', 10.00, 'Beverages', 'tea.png'),
('Lemon Juice', 30.00, 'Beverages', 'lemon_juice.png'),
('French Fries', 70.00, 'Fast Food', 'fries.png'),
('Veg Burger', 80.00, 'Fast Food', 'burger.png'),
('Biryani (Chicken)', 180.00, 'Meals', 'chicken_biryani.png'),
('Gulab Jamun (2 pcs)', 30.00, 'Desserts', 'gulab_jamun.png'),
('Coke / Pepsi', 25.00, 'Drinks', 'coke.png'),
('Water Bottle', 15.00, 'Drinks', 'water.png');
```

---

## 👤 Default Login Credentials

Create default users by running this once:

```php
<?php
include "db.php";
$admin = password_hash("admin123", PASSWORD_DEFAULT);
$user  = password_hash("user123", PASSWORD_DEFAULT);

$conn->query("INSERT INTO users (name, username, password, role) VALUES
('Admin', 'admin', '$admin', 'admin'),
('Student', 'user', '$user', 'user')
");
echo "Users created!";
?>
```

---

## ▶️ How to Run the Project

1. Install **XAMPP**
2. Place project in `htdocs/canteen/`
3. Start `Apache` and `MySQL`
4. Open `login.php` in browser
5. Login as Admin or Student
6. Start adding items and generate bills!

---

## ✅ Future Improvements (Optional)

- Export bill to **PDF**
- Analytics dashboard for admin
- AJAX bill list without page reload
- Dark mode UI
- Order notifications system
- Sales report generation

---

## 🤝 Contributing

Feel free to fork and improve this project. Pull requests are welcome!

---

## 📜 License
This project is for **educational purposes** and can be freely used or modified.

---

### ✍️ Author
**Alkaf Khan**
