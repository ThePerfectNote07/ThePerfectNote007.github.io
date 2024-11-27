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
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/modssty.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    include("conex.php");
    session_start();

    if ($conex->connect_error) {
        die("Error de conexión: " . $conex->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $producto = $_POST['nombre']; // Campo "nombre" del formulario
        $marca = $_POST['marca'];
        $mensaje = $_POST['mensaje'];

        // Manejo de la imagen
        $imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../up/uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true); // Crear el directorio si no existe
            }
            $imagen = $target_dir . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
        }

        // Insertar datos en la base de datos
        $stmt = $conex->prepare("INSERT INTO repa (producto, marca, imagen, mensaje) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conex->error);
        }
        $stmt->bind_param("ssss", $producto, $marca, $imagen, $mensaje);

        if ($stmt->execute()) {
            echo "<script>Swal.fire('Éxito', 'Datos guardados correctamente.', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Error', 'No se pudo guardar en la base de datos.', 'error');</script>";
        }
        $stmt->close();
    }
    ?>

    <h2>Enviar Mensaje</h2>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="nombre">Producto:</label>
            <input type="text" name="nombre" id="nombre" required>
        </div>
        <div>
            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" required>
        </div>
        <div>
            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" id="imagen" required>
        </div>
        <div>
            <label for="mensaje">Mensaje:</label>
            <textarea name="mensaje" id="mensaje" required></textarea>
        </div>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
