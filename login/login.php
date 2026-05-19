<?php

session_start();
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/csrf.php";

$error = "";

// نتحقق إن الطلب جاي من POST 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // نتحقق من CSRF token للحماية
    if (!validateCsrfToken($_POST["csrf_token"])) {
        die("Invalid CSRF token");
    }

    $email = filter_var(trim(strtolower($_POST["email"])), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST["password"]);

    if (!$email) {
        die("Invalid email format");
    }

    if (empty($password)) {
        die("Password is required");
    }
  // نبحث عن المستخدم في قاعدة البيانات حسب الإيميل
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
// الحالة الأولى: المستخدم موجود مسبقًا
// نتحقق من كلمة المرور (مشفرة)
        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                header("Location: ../student/events.php");
                exit;
            }

        } else {
            $error = "Incorrect password";
        }

    } else {
 // الحالة الثانية: المستخدم غير موجود → نسجله جديد
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = ($email === "admin@yic.edu.sa") ? "admin" : "student";

        $insert = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $insert->execute([$email, $hashedPassword, $role]);

        $_SESSION["user_id"] = $pdo->lastInsertId();
        $_SESSION["role"] = $role;

        if ($role === "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../student/events.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - YIC Event Management</title>
<link rel="stylesheet" href="../css/style.css">

</head>
<body>

  <header>
    <h1>YIC Service Portal</h1>
    <p>Simple Event Management System</p>
  </header>

  <main>
    <section class="login-container">
      <h2>Login</h2>

      <form method="POST">
        <?= csrfField() ?>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
      </form>

      <p class="note">Login as Student or Admin</p>

      <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
      <?php endif; ?>

    </section>
  </main>

  <footer>
    <p>YIC Services – Event Management</p>
  </footer>

<script>
   document.querySelector("form").addEventListener("submit", function(e) {
    const email = document.querySelector("input[name='email']").value.trim().toLowerCase();
    const password = document.querySelector("input[name='password']").value.trim();

    // استثناء الأدمن
    if (email === "admin@yic.edu.sa") {
        return; // خليه يدخل بدون أي فحص
    }
    // باقي الشروط للطلاب فقط
    const universityPattern = /^[a-zA-Z0-9._%+-]+@yic\.edu\.sa$/i;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!universityPattern.test(email)) {
        alert("You must use your university email (example: name@yic.edu.sa)");
        e.preventDefault();
        return;
    }

    if (!passwordPattern.test(password)) {
        alert("Password must contain:\n- At least 8 characters\n- One uppercase letter\n- One lowercase letter\n- One number");
        e.preventDefault();
        return;
    }
});
</script>

</body>
</html>
