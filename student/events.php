<?php
session_start();
require_once "../login/db.php";

// تأكد أن المستخدم Student
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
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
  <title>Events - YIC Event Management</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1>YIC Service Portal</h1>
  <p>Simple Event Management System</p>
</header>

<nav>
  <a href="events.php">Events</a>
  <a href="my-events.php">My Events</a>
  <a href="../login/login.php">Logout</a>
</nav>

<main>
  <section class="events-section">
    <h2>Upcoming Events</h2>

    <input
      type="text"
      id="searchInput"
      placeholder="Search events..."
      class="search-box"
      onkeyup="searchEvents()"
    >

    <div id="eventsContainer">

      <?php if (count($events) === 0): ?>
        <p>No events available.</p>
      <?php endif; ?>

      <?php foreach ($events as $event): ?>
        <article class="event-card">

          <?php if (!empty($event["image"])): ?>
            <img src="../uploads/<?= $event['image'] ?>" class="event-image">
          <?php endif; ?>

          <h3><?= htmlspecialchars($event["title"]) ?></h3>
          <p><strong>Date:</strong> <?= $event["date"] ?></p>
          <p><strong>Time:</strong> <?= $event["time"] ?></p>
          <p><strong>Location:</strong> <?= $event["location"] ?></p>

          <a href="event-details.php?id=<?= $event['id'] ?>" class="btn">View Details</a>
        </article>
      <?php endforeach; ?>

    </div>

  </section>
</main>

<footer>
  <p>YIC Services – Event Management</p>
</footer>

<script>
// ========== Search Events ==========
function searchEvents() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const cards = document.querySelectorAll(".event-card");

  cards.forEach(card => {
    const title = card.querySelector("h3").textContent.toLowerCase();
    card.style.display = title.includes(input) ? "block" : "none";
  });
}
</script>

</body>
</html>

