<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('./app/connect.php');

session_start();

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
                    <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php } else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>Létrehozva: <?php echo $todo['created_at']; ?> és létrehozta: <?php echo $todo['firstName']; ?></small>
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

    <script src="./js/jquery-3.2.1.min.js"></script>
    <script>
        $(document).ready(function(){

            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                const element = $(this).parent();

                if (confirm('Biztosan ki akarod törölni?')) {
                    $.post("./app/remove.php", 
                          {
                              id: id
                          },
                          (data)  => {
                             if(data == '1'){
                                 element.fadeOut(600, function() {
                                     $(this).remove();
                                 });
                             } else {
                                 alert('Sikertelen törlés...');
                             }
                          }
                    );
                }
            });

            $(".check-box").click(function(){
                const id = $(this).attr('data-todo-id');
                const checkbox = $(this);
                const h2 = $(this).next();

                $.post('./app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data !== 'error'){
                              if(data === '1'){
                                  checkbox.prop('checked', true);
                                  h2.addClass('checked');
                              } else {
                                  checkbox.prop('checked', false);
                                  h2.removeClass('checked');
                              }
                          } else {
                              alert('Sikertelen státusz változtatás...');
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>