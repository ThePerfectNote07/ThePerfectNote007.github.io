<!DOCTYPE html>
<html lang="en">
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
                <h2 class="section-heading text-uppercase">SE PARTE DE NUESTRA FAMILIA</h2>
                <h3 class="section-subheading text-muted">Regístrate en <b>The Perfect Note</b></h3>
            </div>
            <?php
            include("conex.php");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario = $conex->real_escape_string($_POST['usuario']);
                $correo = $conex->real_escape_string($_POST['correo']);
                $password = $conex->real_escape_string($_POST['password']);
                $confirmar = $conex->real_escape_string($_POST['confirmar']);

                $sql = "INSERT INTO usuarios (usuario, correo, password, confirmar) VALUES ('$usuario', '$correo', '$password', '$confirmar')";

                if ($conex->query($sql) === TRUE) {
                    echo "Registro exitoso";
                } else {
                    echo "Error: " . $sql . "<br>" . $conex->error;
                }

                $conex->close();
            }
            ?>
            <form id="contactForm" method="POST" action="iniciar.php">
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
                    <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Registrar</button>
                </div>
                <br>
                 <div class="text-center">
                    <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="button" onclick="location.href='admin.php'">
                        Iniciar sesión como <b> admin </b>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("contactForm");
            const password = document.getElementById("password");
            const confirmar = document.getElementById("confirmar");
            const errorMsg = document.getElementById("error-msg");

            form.addEventListener("submit", function (event) {
                if (password.value !== confirmar.value) {
                    event.preventDefault(); 
                    errorMsg.textContent = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
                } else {
                    errorMsg.textContent = ""; 
                    event.preventDefault();
                    Swal.fire({
                        title: "¡Registro Exitoso!",
                        text: "Ahora eres parte de nuestra familia.",
                        imageUrl: "https://i.pinimg.com/originals/6d/12/42/6d1242777c5370ba8d538315bee7c7d2.jpg",
                        imageWidth: 500,
                        imageHeight: 300,
                        imageAlt: "Imagen personalizada"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); 
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
