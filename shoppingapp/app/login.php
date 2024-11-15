<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $userId = $user['id'];
                $hashedPasswordFromDb = $user['password'];
                $role = $user['role'];
                if (sha1($password) === $hashedPasswordFromDb) {
                    session_start();
                    $_SESSION['id'] = $userId;
                    $_SESSION['role'] = $role;

                    if ($role == 3 || $role == 4) {
                        header("Location: ../todo.php");
                    } else {
                        echo "Az accountod nem aktív.";
                    }
                    exit();
                } else {
                    echo "Hibás jelszó.";
                }
            } else {
                echo "Hibás email cím.";
            }
        } catch (PDOException $e) {
            echo "Hiba: " . $e->getMessage();
        }
    } else {
        echo "Kérlek, töltsd ki az összes mezőt.";
    }
}
$conn = null;
?>
