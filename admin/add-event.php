<?php
session_start();
require_once "../login/db.php";

// تأكد أنه Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login/login.php");
    exit;
}

$event = null;

// لو جايين نعدل حدث
if (isset($_GET["id"])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$_GET["id"]]);
    $event = $stmt->fetch();
}

// لو تم إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $description = $_POST["description"];

    // معالجة الصورة
    $imageName = $event["image"] ?? null; // لو تعديل، نحتفظ بالصورة القديمة

    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/" . $imageName);
    }

    if (!empty($_POST["id"])) {
        // تعديل حدث
        $stmt = $pdo->prepare("UPDATE events 
            SET title=?, date=?, time=?, location=?, description=?, image=? 
            WHERE id=?");
        $stmt->execute([$title, $date, $time, $location, $description, $imageName, $_POST["id"]]);
    } else {
        // إضافة حدث جديد
        $stmt = $pdo->prepare("INSERT INTO events 
            (title, date, time, location, description, image) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $date, $time, $location, $description, $imageName]);
    }

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $event ? "Edit Event" : "Add Event" ?></title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <h1><?= $event ? "Edit Event" : "Add New Event" ?></h1>
</header>

<main>
  <section>
    <h2><?= $event ? "Update Event" : "Create Event" ?></h2>

    <form method="POST" enctype="multipart/form-data">

      <input type="hidden" name="id" value="<?= $event['id'] ?? '' ?>">

      <label>Event Title</label>
      <input type="text" name="title" required value="<?= $event['title'] ?? '' ?>">

      <label>Date</label>
      <input type="date" name="date" required value="<?= $event['date'] ?? '' ?>">

      <label>Time</label>
      <input type="time" name="time" required value="<?= $event['time'] ?? '' ?>">

      <label>Location</label>
      <input type="text" name="location" required value="<?= $event['location'] ?? '' ?>">

      <label>Description</label>
      <input type="text" name="description" required value="<?= $event['description'] ?? '' ?>">

      <label>Event Image</label>
      <input type="file" name="image">

      <?php if (!empty($event["image"])): ?>
        <p>Current Image:</p>
        <img src="../uploads/<?= $event['image'] ?>" width="150">
      <?php endif; ?>

      <button type="submit" class="btn"><?= $event ? "Save Changes" : "Add Event" ?></button>

    </form>

  </section>
</main>

<footer>
  <p>YIC Event Management System</p>
</footer>

</body>
</html>
