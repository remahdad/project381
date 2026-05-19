<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../login/db.php";


// حذف الحدث من نفس الصفحة
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $delete_id = intval($_POST["delete_id"]);
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$delete_id]);
}


// تأكد أن المستخدم Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login/login.php");
    exit;
}

// جلب الأحداث من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - YIC Event Management</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1>YIC Service Portal</h1>
  <p>Simple Event Management System</p>
</header>

<nav>
  <a href="dashboard.php">Dashboard</a>
  <a href="add-event.php">Add Event</a>
  <a href="../login/login.php">Logout</a>
</nav>

<main>
  <section class="admin-section">
    <h2>Admin Dashboard</h2>

    <button class="btn" onclick="window.location.href='add-event.php'">Add Event</button>

    <table>
      <thead>
        <tr>
          <th>Event Title</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $event): ?>
          <tr>
            <td><?= htmlspecialchars($event["title"]) ?></td>
            <td><?= htmlspecialchars($event["date"]) ?></td>
            <td>
              <a class="btn" href="add-event.php?id=<?= $event['id'] ?>">Edit</a>
             <form action="" method="POST" style="display:inline; margin:0; padding:0;">
             <input type="hidden" name="delete_id" value="<?= $event['id'] ?>">
             <button type="submit" class="btn" style="display:inline-block;" onclick="return confirm('Delete this event?')">Delete</button>
             </form>
              <a class="btn" href="view-registrations.php?id=<?= $event['id'] ?>">View Registrations</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </section>
</main>

<footer>
  <p>YIC Services – Event Management</p>
</footer>

</body>
</html>

