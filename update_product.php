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
    if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['qty']) && isset($_POST['category']) && isset($_POST['description']) && isset($_FILES['image'])) {
        $pId = $_POST['id'];
        if ($_FILES['image']['size'] !== 0) {
            upload_image('image');
            $sql = ("UPDATE cards SET cards.name = :name, cards.description = :description, cards.price = :price, cards.category_id = :category, cards.qty = :qty, cards.image = :image where cards.id like $pId");
        } else {
            $sql = ("UPDATE cards SET cards.name = :name, cards.description = :description, cards.price = :price, cards.category_id = :category, cards.qty = :qty where cards.id like $pId");
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('name', $_POST["name"], PDO::PARAM_STR);
        $stmt->bindValue('price', $_POST["price"], PDO::PARAM_INT);
        $stmt->bindValue('qty', $_POST["qty"], PDO::PARAM_INT);
        $stmt->bindValue('category', $_POST["category"], PDO::PARAM_INT);
        $stmt->bindValue('description', $_POST["description"], PDO::PARAM_STR);
        if ($_FILES['image']['size'] !== 0) {
            $stmt->bindValue('image', $_FILES["image"]["name"], PDO::PARAM_STR);
        }
        $stmt->execute();
        header("Location: update_product.php?id=$pId");
    } else if (isset($_GET["id"])) {
        $pId = $_GET["id"];
        $categories = $conn->query("SELECT * FROM category");
        $product = $conn->query("SELECT * FROM cards where id like $pId")->fetch();
    }
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
    <div class="container d-flex gap-3 justify-content-center align-items-center">
        <div class="w-75">
            <form action="update_product.php" method="POST" enctype="multipart/form-data">
                <fieldset class="d-flex flex-column gap-3 w-100">
                    <h3>Форма обновления товара</h3>
                    <div class="w-100 d-flex gap-3 flex-wrap gap-3 justify-content-between">
                        <input type="text" hidden class="form-control" id="formGroupExampleInput2" value="<?php echo $product['id'] ?>" name="id">
                        <div class="w-50">
                            <label for="formGroupExampleInput2" class="form-label">Название</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?php echo $product['name'] ?>" name="name">
                        </div>

                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Цена</label>
                            <input type="number" class="form-control" id="formGroupExampleInput2" min="0" value="<?php echo $product['price'] ?>" name="price">
                        </div>

                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Количество</label>
                            <input type="number" class="form-control" id="formGroupExampleInput2" min="0" value="<?php echo $product['qty'] ?>" name="qty">
                        </div>

                        <div class="w-100">
                            <label for="formGroupExampleInput2" class="form-label">Категория</label>
                            <select class="form-select" aria-label="Default select example" name="category">
                                <?php
                                foreach ($categories as $one) {
                                    echo '<option value="' . $one["id"] . '" ' . (($product['category_id'] == $one['id']) ? 'selected="selected"' : "") . '>' . $one["title"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="w-100">
                            <label for="formGroupExampleInput2" class="form-label">Описание</label>
                            <textarea type="text" class="form-control" id="formGroupExampleInput2" rows="4" name="description"><?php echo $product['description'] ?></textarea>
                        </div>

                        <div class="d-flex flex-column">
                            <div class="mb-3">
                                <img class="image__product" src="/images/<? echo $product['image'] ?>" alt="<? $product['image'] ?>">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Изображение</label>
                                <input class="form-control" type="file" name="image" id="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Обновить</button>
                </fieldset>
            </form>
        </div>
    </div>
    <?php require "UI/footer.php"; ?>
</body>

</html>