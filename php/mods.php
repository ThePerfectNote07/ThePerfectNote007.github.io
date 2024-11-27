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

    // Insertar un nuevo producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_product_name'], $_POST['new_product_price'])) {
        $new_product_name = $_POST['new_product_name'];
        $new_product_price = $_POST['new_product_price'];
        $new_product_image = '';

        if (isset($_FILES['new_product_image']) && $_FILES['new_product_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../up/uploads/";
            $target_file = $target_dir . basename($_FILES['new_product_image']['name']);
            if (move_uploaded_file($_FILES['new_product_image']['tmp_name'], $target_file)) {
                $new_product_image = $target_file;
            }
        }

        $stmt = $conex->prepare("INSERT INTO productos (nombre, precio, imagen) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $new_product_name, $new_product_price, $new_product_image);
        $stmt->execute();
        $stmt->close();
    }

    // Actualizar un producto existente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'], $_POST['new_name'], $_POST['new_price'])) {
        $update_id = $_POST['update_id'];
        $new_name = $_POST['new_name'];
        $new_price = $_POST['new_price'];
        $target_file = '';

        if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../up/uploads/";
            $target_file = $target_dir . basename($_FILES['new_image']['name']);
            move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file);
        }

        $stmt = $conex->prepare("UPDATE productos SET nombre = ?, precio = ?, imagen = ? WHERE id = ?");
        $stmt->bind_param("sssi", $new_name, $new_price, $target_file, $update_id);
        $stmt->execute();
        $stmt->close();
    }

    // Eliminar un producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        $stmt = $conex->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    }

    // Mostrar la lista de productos
    $result = $conex->query("SELECT * FROM productos");
    echo "<h2>Lista de productos</h2>";
    echo "<table>";
    echo "<tr><th>Producto</th><th>Precio</th><th>Imagen</th><th>Modificar</th><th>Eliminar</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>\${$row['precio']}</td>
                <td><img src='{$row['imagen']}' alt='Imagen de {$row['nombre']}' class='product-image'></td>
                <td>
                    <form method='POST' enctype='multipart/form-data' class='update-form'>
                        <input type='hidden' name='update_id' value='{$row['id']}'>
                        <input type='text' name='new_name' placeholder='Nuevo nombre'>
                        <input type='number' name='new_price' placeholder='Nuevo precio' step='0.01' min='0'>
                        <input type='file' name='new_image'>
                        <button type='button' class='update-button'>Actualizar</button>
                    </form>
                </td>
                <td>
                    <form method='POST' class='delete-form'>
                        <input type='hidden' name='delete_id' value='{$row['id']}'>
                        <button type='button' class='delete-button'>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
    ?>

    <!-- Nuevo formulario para agregar productos -->
    <h2>Agregar nuevo producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="new_product_name" placeholder="Nombre del producto" required>
        <input type="number" name="new_product_price" placeholder="Precio" step="0.01" min="0" required>
        <input type="file" name="new_product_image" required>
        <button type="submit">Añadir producto</button>
    </form>
<br><br>
    <div class="text-center">
        <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="button" onclick="location.href='../index.php'">
            Volver al inicio
        </button>
        <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="button" onclick="location.href='carrito.php'">
            Verificar cambios
        </button>
    </div> 

    <script>
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        document.querySelectorAll('.update-button').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.update-form');
                const newName = form.querySelector('input[name="new_name"]').value;
                const newPrice = form.querySelector('input[name="new_price"]').value;

                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: `Vas a actualizar el producto a: ${newName} por \$${newPrice}`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, actualizar",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La actualización no se realizó.",
                            icon: "error"
                        });
                    }
                });
            });
        });

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.delete-form');

                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminarlo",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "El producto está a salvo :)",
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
