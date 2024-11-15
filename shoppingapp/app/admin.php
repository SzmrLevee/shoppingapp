<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("./connect.php");

$userId = $_SESSION['id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$role = $stmt->fetchColumn();

if ($role == 1) {
    header("Location: ../index.php");
    exit();
} elseif ($role != 4) {
    header("Location: ../todo.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $userIdToChange = $_POST['user_id'];
        $action = $_POST['action'];

        switch ($action) {
            case 'ban':
                $stmt = $conn->prepare("UPDATE users SET role = 2 WHERE id = ?");
                break;
            case 'restore':
                $stmt = $conn->prepare("UPDATE users SET role = 3 WHERE id = ?");
                break;
            case 'make_admin':
                $stmt = $conn->prepare("UPDATE users SET role = 4 WHERE id = ?");
                break;
            case 'remove_admin':
                $stmt = $conn->prepare("UPDATE users SET role = 3 WHERE id = ?");
                break;
            default:
                echo "Rossz gomb...";
                exit();
        }

        if ($stmt->execute([$userIdToChange])) {
            if ($action == 'ban') {
                header("Location: admin.php");
                exit();
            } elseif ($action == 'remove_admin') {
                if ($userIdToChange == $userId) {
                    header("Location: ../todo.php");
                    exit();
                } else {
                    header("Location: admin.php");
                    exit();
                }
            }
        } else {
            echo "Error: " . implode(", ", $stmt->errorInfo());
        }
    }
}

$pendingStmt = $conn->prepare("SELECT id, firstName, lastName, email, role FROM users WHERE role = 1");
$bannedStmt = $conn->prepare("SELECT id, firstName, lastName, email, role FROM users WHERE role = 2");
$adminsStmt = $conn->prepare("SELECT id, firstName, lastName, email, role FROM users WHERE role = 4");
$activeStmt = $conn->prepare("SELECT id, firstName, lastName, email, role FROM users WHERE role = 3");

$pendingStmt->execute();
$pendingResult = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);

$bannedStmt->execute();
$bannedResult = $bannedStmt->fetchAll(PDO::FETCH_ASSOC);

$adminsStmt->execute();
$adminsResult = $adminsStmt->fetchAll(PDO::FETCH_ASSOC);

$activeStmt->execute();
$activeResult = $activeStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Oldal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Admin Oldal</h1>
        <h2>Jóváhagyásra vár</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Email</th>
                    <th>Gombok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pendingResult) > 0): ?>
                    <?php foreach ($pendingResult as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="action-buttons">
                                <form method="post" style="display:inline;" onsubmit="return confirm('Biztosan aktív felhasználóvá szeretnéd tenni?');">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="restore">
                                    <button type="submit" class="btn">Jóváhagyás</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nem vár senki jóváhagyásra.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Tiltott felhasználók</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Email</th>
                    <th>Gombok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bannedResult) > 0): ?>
                    <?php foreach ($bannedResult as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="action-buttons">
                                <form method="post" style="display:inline;" onsubmit="return confirm('Biztosan vissza szeretnéd vonni a felhasználó tiltását?');">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="restore">
                                    <button type="submit" class="btn">Tiltás visszavonása</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nincsenek tiltott felhasználók.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Adminok</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Email</th>
                    <th>Gombok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($adminsResult) > 0): ?>
                    <?php foreach ($adminsResult as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="action-buttons">
                                <form method="post" style="display:inline;" onsubmit="return confirm('Biztosan el szeretnéd venni az admin jogait?');">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="remove_admin">
                                    <button type="submit" class="btn">Admin jogok elvétele</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nincsenek adminok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Aktív felhasználók</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Email</th>
                    <th>Gombok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($activeResult) > 0): ?>
                    <?php foreach ($activeResult as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="action-buttons">
                                <div class="action-button-container">
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Biztosan ki akarod tiltani a felhasználót?');">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <input type="hidden" name="action" value="ban">
                                        <button type="submit" class="btn">Kitiltás</button>
                                    </form>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Biztosan admin jogosultságokat szeretnél neki adni?');">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <input type="hidden" name="action" value="make_admin">
                                        <button type="submit" class="btn">Admin jogosultságok</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nincsenek aktív felhasználók.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="./logout.php" class="btn logout">Kijelentkezés</a>
    </div>
</body>
</html>