<?php
// Database connection settings
$host = "localhost";
$dbname = "yic_events";   // اسم قاعدة البيانات اللي اخترتيه
$username = "root";       // الافتراضي في XAMPP / Laragon
$password = "";           // فاضي

try {
    // Create PDO connection
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // PDO settings
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
