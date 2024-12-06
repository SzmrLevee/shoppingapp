<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('./app/connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['id'];

$stmt = $conn->prepare("SELECT role, firstName FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$role = $user['role'];
$firstName = $user['firstName'];

$isAdmin = ($role == 4);

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Termékek</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-section">
        <div class="add-section">
        <form action="app/add.php" method="POST" autocomplete="off">
    <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
        <input type="text" name="title" style="border-color: #ff6666" placeholder="Írj valamit!" maxlength="40"/>
        <button type="submit">Termék hozzáadása &nbsp; <span>&#43;</span></button>
    <?php } else { ?>
        <input type="text" name="title" placeholder="Mit szeretnél feljegyezni?" maxlength="40"/>
        <button type="submit">Termék hozzáadása &nbsp; <span>&#43;</span></button>
    <?php } ?>
</form>

        </div>

        <?php
            $todosQuery = $conn->query("
                SELECT todos.*, users.firstName 
                FROM todos 
                JOIN users ON todos.user_id = users.id 
                ORDER BY todos.id DESC
            ");

            if ($todosQuery === false) {
                echo "Database error: " . $conn->errorInfo();
            }
        ?>
        
        <div class="show-todo-section">
            <?php if($todosQuery->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="./img/f.png" width="100%" />
                        <img src="./img/Ellipsis.gif" width="80px">
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $todosQuery->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <div class="todo-checkbox">
                        <input type="checkbox"
                            class="check-box"
                            data-todo-id="<?php echo $todo['id']; ?>"
                            <?php echo $todo['checked'] ? 'checked' : ''; ?> />
                    </div>
                    <div class="todo-content">
                        <label class="todo-label <?php echo $todo['checked'] ? 'checked' : ''; ?>">
                            <?php echo htmlspecialchars($todo['title']); ?>
                        </label>
                        <small>Létrehozva: <?php echo $todo['created_at']; ?> és létrehozta: <?php echo htmlspecialchars($todo['firstName']); ?></small>
                    </div>
                    <div class="todo-remove">
                        <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="footer">
            <a href="./app/logout.php" class="btn logout">Kijelentkezés</a>
            <?php if ($isAdmin): ?>
                <a href="./app/admin.php" class="btn admin">Admin</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.check-box').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const id = this.dataset.todoId;
            const label = this.closest('.todo-item').querySelector('.todo-label');
            const isChecked = this.checked;

            if (isChecked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
            fetch('./app/check.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'error') {
                    alert('Hiba történt az állapot frissítése során.');
                }
            })
            .catch(err => console.error('Hiba:', err));
        });
    });

    document.querySelectorAll('.remove-to-do').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.id;
            const todoItem = this.closest('.todo-item');

            if (confirm('Biztosan törölni szeretnéd?')) {
                fetch('./app/remove.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === '1') {
                        todoItem.remove();
                    } else {
                        alert('Hiba történt a törlés során.');
                    }
                })
                .catch(err => console.error('Hiba:', err));
            }
        });
    });
});
</script>

</body>
</html>