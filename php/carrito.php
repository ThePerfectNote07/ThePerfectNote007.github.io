<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>The Perfect Note</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="../css/sty.css" rel="stylesheet" />
    <link href="../css/carrito.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><img src="../assets/img/navbar-logo.svg" alt="..." /></a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="carrito.php">Tienda</a></li>
                    <li class="nav-item"><a class="nav-link" href="../html/repa.html">Reparacion</a></li>
                    <li class="nav-item"><a class="nav-link" href="../html/prod.html">Produccion</a></li>
                    <li class="nav-item"><a class="nav-link" href="iniciar.php">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <br><br><br><br><br><br><br><br><br>

    <?php
    include("conex.php");
    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    // Implementación de la barra de búsqueda
    $where = "";
    if (!empty($_GET['search'])) {
        $search = $conex->real_escape_string($_GET['search']);
        $where = "WHERE nombre LIKE '%$search%'";
    }

    function addToCart($id, $quantity) {
        global $conex;
        $stmt = $conex->prepare("SELECT id, nombre, precio, imagen FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $id) {
                    $item['quantity'] += $quantity; 
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $product['quantity'] = $quantity;
                $_SESSION['cart'][] = $product;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['quantity'])) {
        addToCart((int)$_POST['id'], (int)$_POST['quantity']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    }

    $result = $conex->query("SELECT * FROM productos $where");
    ?>

    <!-- Barra de búsqueda -->
    <div class="container mt-4">
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Buscar artículo..." class="form-control" style="width: 300px; display: inline-block; margin-right: 10px;">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>
<br>
    <?php
    echo "<table>";
    echo "<h2>¡Nuestros productos más vendidos!</h2>";
    echo "<tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        $modalId = "productModal" . $row['id']; // ID único para cada modal
        echo "<tr>
                <td>
                    <div style='text-align: center;'>
                        <img src='{$row['imagen']}' alt='Imagen de {$row['nombre']}' class='product-image' style='max-width: 150px; cursor: pointer;' data-bs-toggle='modal' data-bs-target='#{$modalId}'>
                        <div style='margin-top: 5px;'>{$row['nombre']}</div>
                    </div>
                </td>
                <td>\${$row['precio']}</td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='number' name='quantity' value='1' min='1'>
                        <button type='submit'>Agregar al carrito</button>
                    </form>
                </td>
              </tr>";

        echo "<div class='portfolio-modal modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='container'>
                            <div class='row justify-content-center'>
                                <div class='col-lg-8'>
                                    <div class='modal-body'>
                                        <h2 class='text-uppercase'>{$row['nombre']}</h2>
                                        <p class='item-intro text-muted'>Detalles del producto</p>
                                        <img class='img-fluid d-block mx-auto' src='{$row['imagen']}' alt='Imagen de {$row['nombre']}' />
                                        <p>{$row['descripcion']}</p>
                                        <ul class='list-inline'>
                                            <li>
                                                <strong>Precio:</strong> \${$row['precio']}
                                            </li>
                                        </ul>
                                        <button class='btn btn-primary btn-xl text-uppercase' data-bs-dismiss='modal' type='button'>
                                            <i class='fas fa-xmark me-1'></i>
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>";
    }
    echo "</table>";
?>

<br>

<?php
    echo "<h2>Carrito de compras</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>El carrito está vacío</p>";
} else {
    echo "<table>";
    echo "<tr>
            <th>Producto</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
          </tr>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['precio'] * $item['quantity'];
        $total += $subtotal;

        echo "<tr>
                <td>
                    <div style='text-align: center;'>
                        <img src='{$item['imagen']}' alt='Imagen de {$item['nombre']}' class='product-image' style='max-width: 150px;'>
                        <div style='margin-top: 5px;'>{$item['nombre']}</div>
                    </div>
                </td>
                <td>\${$item['precio']}</td>
                <td>{$item['quantity']}</td>
                <td>\${$subtotal}</td>
                <td>
                    <form method='POST' style='display: inline;'>
                        <input type='hidden' name='id' value='{$item['id']}'>
                    </form>
                </td>
              </tr>";
    }
    echo "<tr>
            <td colspan='3' style='text-align: right;'><strong>Total:</strong></td>
            <td colspan='2'>\${$total}</td>
          </tr>";
    echo "</table>";
    echo "<form method='POST' style='display: inline;'>
            <button type='submit' name='clear_cart' class='clear-cart'>Limpiar carrito</button>
          </form>";
    echo "<button onclick=\"location.href='continuar.php'\" style='display: inline; margin-left: 10px;'>¡Continuar compra!</button>";
}
    ?>
 <p><p>  
¡Atención! 🚨 Para completar tu compra de manera correcta, asegúrate de tener una cuenta. ¡Es fácil y rápido!
<p><p>

<footer class="footer py-3 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-4 text-center text-lg-start mb-2 mb-lg-0">
                &copy; GodSpeed 2024. All Rights Reserved.
            </div>

            <div class="col-12 col-lg-4 d-flex justify-content-center flex-wrap mb-2 mb-lg-0">
                <a href="index.php" class="text-white text-decoration-none mx-2">Inicio</a>
                <a href="/about" class="text-white text-decoration-none mx-2">Sobre nosotros</a>
                <a href="../docu/politica.pdf" class="text-white text-decoration-none mx-2" target="_blank">Política de privacidad</a>
            </div>

            <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-end">
                <a href="https://facebook.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        width: 100%;
    }

    .footer a {
        white-space: nowrap;
        margin: 0 8px;
    }

    .footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .footer .row {
        display: flex;
        flex-wrap: wrap;
    }
</style>

</body>
</html>
