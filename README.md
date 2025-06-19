# Medical Prescription Upload System

A secure web-based platform for users to upload medical prescriptions and receive quotations from pharmacies. Built with PHP, HTML, CSS, MySQL, and deployed locally using XAMPP with phpMyAdmin.

---

## 🛠️ Technologies Used

- **Backend:** PHP (vanilla)
- **Frontend:** HTML5, CSS3
- **Database:** MySQL (phpMyAdmin)
- **Server:** Apache (via XAMPP)
- **Others:** JavaScript, PHPMailer

---

## 🚀 Setup Instructions

1. Install [XAMPP](https://www.apachefriends.org/index.html)
2. Clone this repository or copy files to `C:/xampp/htdocs/`
3. Start Apache and MySQL via XAMPP
4. Open `http://localhost/phpmyadmin`, create a database (e.g., `prescription_system`)
5. Import your SQL file into the database
6. Configure `config/db.php` with your DB name
7. Open `http://localhost/medical-prescription-system/` in your browser


## 📁 Folder Structure

- `/auth` - Handles login, register, password reset
- `/user` - User dashboard and prescription uploads
- `/pharmacy` - Pharmacy dashboard and quotations
- `/config/db.php` - Database connection settings
- `/assets` - CSS, JS, and images


## 🔐 Features

- Role-based login (User and Pharmacy)
- Prescription image uploads (JPG/PNG/PDF)
- View and manage quotation status
- Password reset and notification via email (PHPMailer)

