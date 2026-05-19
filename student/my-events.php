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

$userId = $_SESSION["user_id"];

// جلب الأحداث اللي سجّل فيها المستخدم
$stmt = $pdo->prepare("
    SELECT events.*
    FROM events
    INNER JOIN registrations 
        ON events.id = registrations.event_id
    WHERE registrations.user_id = ?
    ORDER BY events.date ASC
");
$stmt->execute([$userId]);
$myEvents = $stmt->fetchAll();

// لو المستخدم ضغط Cancel
if (isset($_GET["cancel"])) {
    $eventId = $_GET["cancel"];

    $delete = $pdo->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
    $delete->execute([$userId, $eventId]);

    header("Location: my-events.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Events - YIC Event Management</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1>My Registered Events</h1>
</header>

<nav>
  <a href="events.php">Events</a>
  <a href="my-events.php">My Events</a>
  <a href="../login/login.php">Logout</a>
</nav>

<main>
  <section class="my-events-section">

    <?php if (count($myEvents) === 0): ?>
      <p>You have not registered for any events yet.</p>
    <?php endif; ?>

    <?php foreach ($myEvents as $event): ?>
      <article class="event-card">

        <?php if (!empty($event["image"])): ?>
          <img src="../uploads/<?= $event['image'] ?>" class="event-image">
        <?php endif; ?>

        <h3><?= htmlspecialchars($event["title"]) ?></h3>
        <p><strong>Date:</strong> <?= $event["date"] ?></p>
        <p><strong>Time:</strong> <?= $event["time"] ?></p>
        <p><strong>Location:</strong> <?= $event["location"] ?></p>

        <a href="my-events.php?cancel=<?= $event['id'] ?>" 
           class="btn cancel-btn"
           onclick="return confirm('Cancel this event?')">
           Cancel
        </a>

      </article>
    <?php endforeach; ?>

  </section>
</main>

<footer>
  <p>YIC Services – Event Management</p>
</footer>

</body>
</html>
