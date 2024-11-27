<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>The Perfect Note</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/sty.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body id="page-top">
    <!-- Navigation-->
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
                    <li class="nav-item"><a class="nav-link" href="carrito.php">Tienda</a></li>
                     <li class="nav-item"><a class="nav-link" href="../html/repa.html">Reparacion</a></li>
                    <li class="nav-item"><a class="nav-link" href="../html/prod.html">Producción</a></li>
                    <li class="nav-item"><a class="nav-link" href="../php/iniciar.php">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br><br><br>
    <section class="page-section" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Inicia sesión como admin</h2>
                <h3 class="section-subheading text-muted">Si no tienes autorización a <b>The Perfect Note</b>, te solicitamos que salgas de esta página</h3>
            </div>
            <?php
            include("conex.php");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $conex->real_escape_string($_POST['id']);
                $usuario = $conex->real_escape_string($_POST['usuario']);
                $password = $conex->real_escape_string($_POST['password']);
                $confirmar = $conex->real_escape_string($_POST['confirmar']);

                if ($password === $confirmar) {
                    $sql = "SELECT * FROM admin WHERE id = '$id' AND usuario = '$usuario' AND password = '$password'";
                    $result = $conex->query($sql);
                    
                    if ($result->num_rows > 0) {
                        header("Location: mods.php");
                        exit();
                    } else {
                        echo "<script>Swal.fire('Error', 'Datos incorrectos. Intenta de nuevo.', 'error');</script>";
                    }
                } else {
                    echo "<script>Swal.fire('Error', 'Las contraseñas no coinciden. Por favor, intenta de nuevo.', 'error');</script>";
                }
            }
            ?>
            <form id="contactForm" method="POST" action="">
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input class="form-control" id="id" name="id" type="text" placeholder="Tu ID *" required />
                            <div class="invalid-feedback">El ID es obligatorio.</div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Tu Usuario *" required />
                            <div class="invalid-feedback">El usuario es obligatorio.</div>
                            <div class="invalid-feedback">El usuario no es válido.</div>
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
                    <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">
                        Ingresar
                    </button>
                </div> 
            </form>
        </div>
    </section>
    <footer class="footer py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-lg-start">Copyright &copy; GodSpeed 2024</div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap and custom scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("contactForm");
        const password = document.getElementById("password");
        const confirmar = document.getElementById("confirmar");
        const errorMsg = document.getElementById("error-msg");
        const submitButton = document.getElementById("submitButton");

        function validateForm() {
            const id = document.getElementById("id").value;
            const usuario = document.getElementById("usuario").value;

            if (id && usuario && password.value && confirmar.value) {
                submitButton.disabled = false; 
                errorMsg.textContent = ""; 
            } else {
                submitButton.disabled = true; 
            }
        }

        form.addEventListener("input", validateForm);

        validateForm();
    });
</script>

</body>
</html>
