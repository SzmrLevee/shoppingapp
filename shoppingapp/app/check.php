<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('./connect.php');

if (!isset($_SESSION['id'])) {
    echo 'error';
    exit();
}

if (isset($_POST['id'])) {
    $todoId = intval($_POST['id']);

    $stmt = $conn->prepare("SELECT checked FROM todos WHERE id = ?");
    $stmt->execute([$todoId]);
    $todo = $stmt->fetch();

    if ($todo) {
        $newCheckedStatus = $todo['checked'] ? 0 : 1;

        $updateStmt = $conn->prepare("UPDATE todos SET checked = ? WHERE id = ?");
        if ($updateStmt->execute([$newCheckedStatus, $todoId])) {
            echo $newCheckedStatus;
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>