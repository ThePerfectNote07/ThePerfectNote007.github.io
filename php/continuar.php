<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>The Perfect Note</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="../css/sty.css" rel="stylesheet" />
    <link href="../css/carrito.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top"><img src="../assets/img/navbar-logo.svg" alt="..." /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="carrito.php">Producto</a></li>
                    <li class="nav-item"><a class="nav-link" href="carrito.php">Venta</a></li>
                    <li class="nav-item"><a class="nav-link" href="../html/repa.html">Reparacion</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Producción</a></li>
                    <li class="nav-item"><a class="nav-link" href="../php/iniciar.php">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <br><br><br>
    <section class="page-section" id="contact">
        <div class="container my-5">
            <h2 class="mb-4">Checkout</h2>
            <div class="row">
                <!-- Carrito de compras (izquierda) -->
                <div class="col-md-6">
                    <h4>Resumen de Compra</h4>
                    <?php
                    if (empty($_SESSION['cart'])) {
                        echo "<p>El carrito está vacío</p>";
                    } else {
                        echo "<table class='table table-striped'>";
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
                                  </tr>";
                        }
                        echo "<tr>
                                <td colspan='3' style='text-align: right;'><strong>Total:</strong></td>
                                <td>\${$total}</td>
                              </tr>";
                        echo "</table>";
                    }
                    ?>
                    <button onclick="window.print()" class="btn btn-success mt-3">Imprimir ticket</button>
                </div>

                <!-- Métodos de pago (derecha) -->
                <div class="col-md-6">
                    <h4>Métodos de pago</h4>
                    <div class="d-flex flex-column">
                        <button class="btn btn-primary d-flex align-items-center my-2" onclick="payWithPayPal()">
                            <img src="../assets/img/paypal-logo.png" alt="PayPal" style="width: 50px; margin-right: 10px;"> PayPal
                        </button>
                        <button class="btn btn-secondary d-flex align-items-center my-2" onclick="showCardForm()">
                            <img src="../assets/img/card-logo.png" alt="Tarjeta" style="width: 50px; margin-right: 10px;"> Tarjeta de Crédito/Débito
                        </button>
                    </div>

                    <!-- Formulario de tarjeta de crédito -->
                    <div id="cardForm" class="mt-4" style="display: none;">
                        <h5>Detalles de la Tarjeta</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Número de Tarjeta</label>
                                <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required>
                            </div>
                            <div class="mb-3">
                                <label for="cardHolder" class="form-label">Titular de la Tarjeta</label>
                                <input type="text" class="form-control" id="cardHolder" name="cardHolder" placeholder="Nombre Completo" required>
                            </div>
                            <div class="mb-3">
                                <label for="expiryDate" class="form-label">Fecha de Expiración</label>
                                <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/AA" required>
                            </div>
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario de inicio de sesión (mantiene su posición abajo) -->
            <div class="text-center mt-5">
                <h2 class="section-heading text-uppercase">Inicia sesión</h2>
                <h3 class="section-subheading text-muted">Tienes que iniciar sesión para continuar con tu compra</h3>

                <form id="contactForm" method="POST" action="continuar.php">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Tu Nombre *" required />
                                <div class="invalid-feedback">El nombre es obligatorio.</div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="correo" name="correo" type="email" placeholder="Tu Correo *" required />
                                <div class="invalid-feedback">El correo es obligatorio.</div>
                                <div class="invalid-feedback">El correo no es válido.</div>
                            </div>
                            <div class="form-group mb-md-0">
                                <input class="form-control" id="password" name="password" type="password" placeholder="Tu Contraseña *" required />
                                <div class="invalid-feedback">La contraseña es obligatoria.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" id="confirmar" name="confirmar" type="password" placeholder="Confirma tu Contraseña *" required />
                                <div class="invalid-feedback">Por favor, confirma tu contraseña.</div>
                                <div id="error-msg" style="color: red; margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Iniciar sesión</button>
                    </div>
                </form>
            </div>

        </div>
    </section>

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

    <!-- Scripts -->
    <script src="../js/scripts.js"></script>
    <script>
        // Mostrar formulario de tarjeta de crédito
        function showCardForm() {
            document.getElementById("cardForm").style.display = "block";
        }

        // Función de pago con PayPal
        function payWithPayPal() {
            alert("Redirigiendo a PayPal...");
        }
    </script>
</body>
</html>
