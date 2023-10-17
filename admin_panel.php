<?php
require "pdo_connection.php";
require "functions.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: errorPage.php?msg=Вы не авторизованы!");
}
if ($_SESSION["user"]["status_id"] != 3) {
    header("Location: errorPage.php?msg=Вы не администратор!");
} else {
    if (isset($_GET['id'])) {
        $pId = $_GET['id'];
        $conn->exec("DELETE FROM cards WHERE cards.id = $pId");
    } else if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['qty']) && isset($_POST['category']) && isset($_POST['desc']) && isset($_FILES['image'])) {
        if (upload_image('image')) {
            $sql = ("INSERT INTO cards (name, description, price, image, qty, category_id) VALUES (:name, :desc, :price, :image, :qty, :category)");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('name', $_POST["name"], PDO::PARAM_STR);
            $stmt->bindValue('price', $_POST["price"], PDO::PARAM_INT);
            $stmt->bindValue('qty', $_POST["qty"], PDO::PARAM_INT);
            $stmt->bindValue('category', $_POST["category"], PDO::PARAM_INT);
            $stmt->bindValue('desc', $_POST["desc"], PDO::PARAM_STR);
            $stmt->bindValue('image', $_FILES["image"]["name"], PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    $categories = $conn->query("SELECT * FROM category");

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $itemsPerPage = 5;
    $totalItems = $conn->query("SELECT COUNT(*) FROM cards")->fetch();
    $totalPages = ceil($totalItems["COUNT(*)"] / $itemsPerPage);
    $offset = ($page - 1) * $itemsPerPage;

    $sql = "SELECT cards.id, cards.name, cards.image, CONCAT(LEFT(cards.description, 31), '...') as description, cards.qty, cards.price, category.title as category 
        FROM cards
        INNER JOIN category on cards.category_id = category.id
        ORDER BY cards.id
        LIMIT :limit OFFSET :offset";

    $goods = $conn->prepare($sql);
    $goods->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
    $goods->bindParam(':offset', $offset, PDO::PARAM_INT);
    $goods->execute();

    //$goods = $conn->query("SELECT cards.id, cards.name, cards.image, CONCAT(LEFT(cards.description, 31), '...') as description, cards.qty, cards.price, category.title as category FROM cards INNER JOIN category on cards.category_id = category.id ORDER BY cards.id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="main.css" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
    <title>Панель администратора</title>
</head>

<body>
    <?php require "UI/navbar.php"; ?>
    <div class="container d-flex gap-5 flex-lg-row flex-column">
        <div>
            <h3>Товары</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Изображение</th>
                        <th scope="col">Название</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Количество</th>
                        <th scope="col">Категория</th>
                        <th scope="col">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($goods as $good) {
                        echo '
                                <tr>
                                    <th scope="row">' . $good['id'] . '</th>
                                    <td><img src="images/' . $good['image'] . '" class="image"></td>
                                    <td><a class="text-decoration-none" href="more.php?id=' . $good['id'] . '">' . $good['name'] . '</a></td>
                                    <td class="truncate">' . $good['description'] . '</td>
                                    <td>' . number_format($good['price']) . '</td>
                                    <td>' . $good['qty'] . '</td>
                                    <td>' . $good['category'] . '</td>
                                    <td class="d-flex gap-3">
                                        <a class="btn btn-outline-danger" href="admin_panel.php?id=' . $good['id'] . '">Удалить</a>
                                        <a class="btn btn-outline-primary" href="update_product.php?id=' . $good['id'] . '">Изменить</a>
                                    </td>
                                </tr>
                            ';
                    }
                    ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example" id="pagination">
                <ul class="pagination justify-content-center">
                    <?php
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Назад</a></li>';
                    }
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';

                        echo '<li class="page-item ' . $activeClass . '">';
                        echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }
                    if ($page < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Вперед</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
        <div>
            <form action="admin_panel.php" method="POST" enctype="multipart/form-data">
                <fieldset class="d-flex flex-column gap-3 w-100">
                    <h3>Добавить товар</h3>
                    <div class="w-100 d-flex gap-3 flex-wrap gap-3 justify-content-between">

                        <div class="w-100">
                            <label for="name" class="form-label">Название</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" name="name" required>
                        </div>

                        <div>
                            <label for="price" class="form-label">Цена</label>
                            <input type="number" class="form-control" id="formGroupExampleInput2" name="price" required>
                        </div>

                        <div>
                            <label for="qty" class="form-label">Количество</label>
                            <input type="number" class="form-control" id="formGroupExampleInput2" name="qty" required>
                        </div>

                        <div class="w-100">
                            <label for="category" class="form-label">Категория</label>
                            <select class="form-select" aria-label="Default select example" name="category" required>
                                <?php
                                foreach ($categories as $one) {
                                    echo '
                                            <option value="' . $one["id"] . '">' . $one["title"] . '</option>
                                        ';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="w-100">
                            <label for="desc" class="form-label">Описание</label>
                            <textarea type="text" class="form-control" id="formGroupExampleInput2" name="desc" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Сохранить</button>
                </fieldset>
            </form>
        </div>
    </div>
    <?php require "UI/footer.php"; ?>
</body>

</html>