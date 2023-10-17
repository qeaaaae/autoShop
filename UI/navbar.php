<head>
  <link rel="icon" href="../favicon.ico" type="image/x-icon">
</head>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">АвтоМагазин</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Главная</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Каталог</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php">Корзина</a>
        </li>
      </ul>
      <div class="d-flex gap-3">
        <?php
        session_start();
        if (!isset($_SESSION['user'])) {
          echo '
						<a href="register.php" class="btn btn-outline-success">Регистрация</a>
						<a href="login.php" class="btn btn-outline-primary">Вход</a>
					';
        } else {
          echo '<a href="profile.php" class="btn btn-outline-success">' . $_SESSION['user']['username'] . '</a>';
          if ($_SESSION['user']['status_id'] == 3) {
            echo '<a href="admin_panel.php" class="btn btn-outline-primary">Панель</a>';
          }
          echo '<a href="logout.php" class="btn btn-outline-danger">Выйти</a>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>