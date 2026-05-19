<?php
session_start();
require_once "../login/db.php";

// تأكد أن المستخدم Admin فقط
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login/login.php");
    exit;
}

// نتأكد أن فيه ID للحدث
if (!isset($_GET["id"])) {
    die("Event not found.");
}

$eventId = $_GET["id"];

// نجيب بيانات الحدث من قاعدة البيانات
$eventStmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$eventStmt->execute([$eventId]);
$event = $eventStmt->fetch();

// نجيب الطلاب المسجلين في هذا الحدث
$stmt = $pdo->prepare("
    SELECT users.email
    FROM registrations
    INNER JOIN users 
        ON registrations.user_id = users.id
    WHERE registrations.event_id = ?
");

// تنفيذ الاستعلام
$stmt->execute([$eventId]);

// تخزين البيانات في متغير
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Registrations</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1>Registered Students</h1>
</header>

<main>
  <section>

    <!-- عرض اسم الحدث -->
    <h2><?= $event["title"] ?></h2>

    <!-- إذا ما فيه طلاب مسجلين -->
    <?php if (count($students) === 0): ?>
      <p>No students registered yet.</p>

    <?php else: ?>

      <!-- جدول عرض الطلاب -->
      <table>
        <thead>
          <tr>
            <th>Student Email</th>
          </tr>
        </thead>

        <tbody>

          <!-- عرض كل طالب -->
          <?php foreach ($students as $student): ?>
            <tr>
              <td><?= htmlspecialchars($student["email"]) ?></td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>

    <?php endif; ?>

  </section>
</main>

<footer>
  <p>YIC Services – Event Management</p>
</footer>

</body>
</html>