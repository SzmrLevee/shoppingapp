<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('./connect.php');

if (isset($_POST['id'])) {
    $todoId = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$todoId, $_SESSION['id']])) {
        echo '1';
    } else {
        echo '0';
    }
} else {
    echo '0';
}
?>