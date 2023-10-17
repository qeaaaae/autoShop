<?php
require "pdo_connection.php";
$user = [
    'username' => '',
    'surname' => '',
    'password' => '',
    'confirm_password' => '',
    'email' => '',
    'phone' => '',
];
if (isset($_POST['username']) && isset($_POST['surname']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['email']) && isset($_POST['phone'])) {
    if ($_POST['username'] !== '' && $_POST['surname'] !== '' && $_POST['password'] !== '' && $_POST['confirm_password'] !== '' && $_POST['email'] !== '' && $_POST['phone'] !== '') {
        $user["username"] = $_POST['username'];
        $user["surname"] = $_POST['surname'];
        $user["password"] = $_POST['password'];
        $user["confirm_password"] = $_POST['confirm_password'];
        $user["email"] = $_POST['email'];
        $user["phone"] = $_POST['phone'];
        $email = $user["email"];
        if (!isset($_POST['accept_terms'])) {
            echo '<script>alert("Вы обязаны принять соглашение!")</script>';
        } else {
            $res = $conn->query("SELECT count(*) FROM users where email like '$email'")->fetch();
            if ($_POST['accept_terms'] == '1') {
                if ($res['count(*)'] > 0) {
                    echo '<script>alert("Пользователь с такой почтой уже есть!")</script>';
                } else {
                    if ($user["password"] === $user["confirm_password"]) {
                        $hash_pass = password_hash($user["password"],  PASSWORD_DEFAULT);
                        $sql = ("INSERT INTO users (username, surname, hash_pass, email, phone, status_id) VALUES (:username, :surname, :hash_pass, :email, :phone, 1)");
                        $stmt = $conn->prepare($sql);
                        $stmt->bindValue('username', $user["username"], PDO::PARAM_STR);
                        $stmt->bindValue('surname', $user["surname"], PDO::PARAM_STR);
                        $stmt->bindValue('hash_pass', $hash_pass, PDO::PARAM_STR);
                        $stmt->bindValue('email', $user["email"], PDO::PARAM_STR);
                        $stmt->bindValue('phone', $user["phone"], PDO::PARAM_STR);
                        $stmt->execute();
                        $user = array_fill_keys(array_keys($user), '');
                        header("Location: login.php");
                    } else {
                        $user["confirm_password"] = '';
                        echo '<script>alert("Пароли различаются!")</script>';
                    }
                }
            }
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
    <title>Регистрация</title>
</head>

<body>
    <?php require "UI/navbar.php"; ?>

    <div class="container d-flex justify-content-center align-items-center mt-0 mb-0">
        <div class="card d-flex align-items-center shadow">
            <form action="register.php" method="POST" class="container d-flex flex-column gap-3">
                <div class="align-self-center">
                    <h1>Регистрация</h1>
                </div>
                <input class="form-control" name="username" type="text" value="<?php echo $user["username"] ?>" placeholder="Введите имя">
                <input class="form-control" name="surname" type="text" value="<?php echo $user["surname"] ?>" placeholder="Введите фамилию">
                <input class="form-control" name="email" type="email" value="<?php echo $user["email"] ?>" placeholder="Введите email">
                <input class="form-control" name="password" type="password" value="<?php echo $user["password"] ?>" placeholder="Введите пароль">
                <input class="form-control" name="confirm_password" type="password" value="<?php echo $user["confirm_password"] ?>" placeholder="Введите повторно пароль">
                <input class="form-control" name="phone" type="number" value="<?php echo $user["phone"] ?>" placeholder="Введите номер телефона">
                <div class="form-check align-self-end">
                    <input class="form-check-input" type="checkbox" value="1" name="accept_terms" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Я принимаю <a href="#" class="text-decoration-none">соглашение</a>.
                    </label>
                </div>
                <div class="align-self-center w-100">
                    <button type="submit" class="btn btn-outline-primary w-100">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </div>

    <?php require "UI/footer.php"; ?>
</body>

</html>