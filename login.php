<?php
require "pdo_connection.php";
$user = [
    'email' => '',
    'password' => '',
];
if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        $user["password"] = $_POST['password'];
        $user["email"] = $_POST['email'];
        $email = $user["email"];
        $res = $conn->query("SELECT count(*) FROM users where email like '$email'")->fetch();
        if ($res['count(*)'] > 0) {
            $userDB = $conn->query("SELECT * FROM users where email like '$email'")->fetch();
            if (password_verify($user["password"], $userDB["hash_pass"])) {
                session_start();
                $_SESSION['user'] = $userDB;
                header("Location: index.php");
            } else {
                echo '<script>alert("Пароль неверный!")</script>';
            }
        } else {
            echo '<script>alert("Пользователь с такой почтой не найден!")</script>';
        }
    } else {
        echo '<script>alert("Заполните все поля!")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Вход</title>
</head>

<body>
    <?php require "UI/navbar.php"; ?>
    <div class="container d-flex justify-content-center align-items-center mt-0 mb-0">
        <div class="card d-flex align-items-center shadow">
            <form action="login.php" method="POST" class="container d-flex flex-column gap-3">
                <div class="align-self-center">
                    <h1>Авторизация</h1>
                </div>
                <div class="d-flex gap-3 flex-column w-100 align-items-center">
                    <input class="form-control w-75" name="email" type="email" value="<?php echo $user["email"] ?>" placeholder="Введите email">
                    <input class="form-control w-75" name="password" type="password" value="<?php echo $user["password"] ?>" placeholder="Введите пароль">
                </div>
                <div class="align-self-center w-50">
                    <button type="submit" class="btn btn-outline-primary w-100">Войти</button>
                </div>
            </form>
        </div>
    </div>
    <?php require "UI/footer.php"; ?>
</body>

</html>