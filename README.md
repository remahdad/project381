# project381
 YIC Event Management System
A complete web application for managing events at YIC with secure login, role‑based access, and full database integration.

 Setup Instructions
 
1️⃣ Requirements
-PHP 8+
-MySQL
-Laragon / XAMPP
-Web browser

2️⃣ Installation Steps
1-Download or clone the project.
2-Place the project folder inside:
C:\laragon\www\

3-Import the database file (yic_events.sql) into phpMyAdmin.
4-Update database credentials inside db.php if needed.
5-Start Apache + MySQL from Laragon.
6-Open the project in your browser:
http://localhost/YIC-Event-System/

Login Credentials
Admin Account:
Email: admin@yic.edu.sa
Password: (your admin password)
Role: admin

Student Account
Students can register using any email.
New accounts are automatically assigned the student role.

 Features List
-Authentication
-Login
-Registration
-Password hashing
-CSRF protection
-Session handling

 Admin Features
-Admin dashboard
-Add / Edit / Delete events
-View all users
-Role‑based access

 Student Features
-Student dashboard
-View events
-Register for events
-Profile session

Security
-CSRF protection
-Input validation
-Password hashing
-Session security

Video Walkthrough
A full 3–5 minute demonstration video has been uploaded to Google Drive.
Watch it here:
https://drive.google.com/file/d/1m1oAa_9MhPebM7ejW22y3KPboVhCZ4ZD/view?usp=drivesdk
Project Structure:
/admin
/student
/css
/js
/images
db.php
csrf.php
login.php
register.php
index.php
README.md

Status:
Complete working application – All features integrated.

