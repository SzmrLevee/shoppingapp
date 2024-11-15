<?php 
session_start();
require './connect.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['id'];

if (empty($_POST['title'])) {
    header("Location: ../todo.php?mess=error");
    exit();
}

$title = trim($_POST['title']);

if (strlen($title) > 40) {
    header("Location: ../todo.php?mess=error&reason=too_long");
    exit();
}

try {
    $stmt = $conn->prepare("INSERT INTO todos (title, user_id, checked) VALUES (?, ?, false)");
    $stmt->bindParam(1, $title);
    $stmt->bindParam(2, $userId);
    $stmt->execute();
    $stmt->closeCursor();
    $conn = null;

    header("Location: ../todo.php?mess=success");
} catch (PDOException $e) {
    header("Location: ../todo.php?mess=error&reason=db_error");
    exit();
}
exit();
?>