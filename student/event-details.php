<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../login/db.php";

// تأكد أن المستخدم Student
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: ../login/login.php");
    exit;
}

// تأكد أن فيه ID للحدث
if (!isset($_GET["id"])) {
    die("Event not found.");
}

$eventId = $_GET["id"];

// جلب بيانات الحدث
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch();

if (!$event) {
    die("Event not found.");
}

// لو المستخدم ضغط Register
if (isset($_POST["register"])) {
    $userId = $_SESSION["user_id"];

    // تأكد أنه ما سجّل قبل
    $check = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->execute([$userId, $eventId]);

    if ($check->rowCount() == 0) {
        // سجّله
        $insert = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $insert->execute([$userId, $eventId]);
    }

    // بعد التسجيل → تحويل لصفحة My Events
    header("Location: my-events.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $event["title"] ?></title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1><?= $event["title"] ?></h1>
</header>

<main>
  <section class="details-section">

    <?php if (!empty($event["image"])): ?>
      <img src="../uploads/<?= $event['image'] ?>" class="event-image">
    <?php endif; ?>

    <p><strong>Description:</strong> <?= $event["description"] ?></p>
    <p><strong>Date:</strong> <?= $event["date"] ?></p>
    <p><strong>Time:</strong> <?= $event["time"] ?></p>
    <p><strong>Location:</strong> <?= $event["location"] ?></p>

    <form method="POST">
      <button type="submit" name="register" class="btn">Register</button>
    </form>

  </section>
</main>

<footer>
  <p>YIC Services – Event Management</p>
</footer>

</body>
</html>
