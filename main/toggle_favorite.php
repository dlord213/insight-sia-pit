<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit();
}

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=insight", 'postgres', 'dlord213');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
  $movieID = $_POST['movie_id'];
  $title = $_POST['title'];
  $userID = $_SESSION['user_id'];

  $checkFavorite = $connection->query("SELECT DISTINCT * FROM user_favorites
  WHERE user_id = " . $_SESSION['user_id'] . " AND movie_id = $movieID")->fetch(PDO::FETCH_ASSOC);

  if ($checkFavorite) {
    try {
      $connection->beginTransaction();

      $stmt = $connection->prepare("DELETE FROM user_favorites
          WHERE user_id = ? AND movie_id = ?");
      $stmt->execute([$userID, $movieID]);

      $connection->commit();
      echo json_encode(['success' => true]);
    } catch (PDOException $e) {
      $connection->rollBack();
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  } else {
    try {
      $connection->beginTransaction();

      $stmt = $connection->prepare("INSERT INTO user_favorites (user_id, movie_id, title) VALUES (?, ?, ?)");
      $stmt->execute([$userID, $movieID, $title]);

      $connection->commit();
      echo json_encode(['success' => true]);
    } catch (PDOException $e) {
      $connection->rollBack();
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }
} else {
  echo json_encode(['success' => false]);
}
