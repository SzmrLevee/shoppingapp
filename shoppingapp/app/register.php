<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['fName']) && isset($_POST['lName'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $firstName = trim($_POST['fName']);
    $lastName = trim($_POST['lName']);

    if (!empty($email) && !empty($password) && !empty($firstName) && !empty($lastName)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "Ez az email cím már regisztrálva van.";
        } else {
            $hashedPassword = sha1($password);
            $role = 1;
            $stmt = $conn->prepare("INSERT INTO users (email, password, firstName, lastName, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$email, $hashedPassword, $firstName, $lastName, $role])) {
                header("Location: registration_success.php");
                exit();
            } else {
                echo "Hiba történt: " . $stmt->errorInfo()[2];
            }
        }
    } else {
        echo "Kérlek, töltsd ki az összes mezőt.";
    }
}

$conn = null;
?>