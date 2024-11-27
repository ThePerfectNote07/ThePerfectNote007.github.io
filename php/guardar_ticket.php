<?php
session_start();
include "conex.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $conex->real_escape_string($_POST['usuario']);
    $correo = $conex->real_escape_string($_POST['correo']);
    $total = $conex->real_escape_string($_POST['total']);
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    // Validar que los arreglos de productos, cantidades y precios coincidan
    if (count($productos) === count($cantidades) && count($cantidades) === count($precios)) {
        // Iterar sobre los productos para guardarlos uno por uno
        for ($i = 0; $i < count($productos); $i++) {
            $producto = $conex->real_escape_string($productos[$i]);
            $cantidad = $conex->real_escape_string($cantidades[$i]);
            $precio_unitario = $conex->real_escape_string($precios[$i]);

            $sql = "INSERT INTO ticket (usuario, correo, producto, cantidad, precio_unitario, total) 
                    VALUES ('$usuario', '$correo', '$producto', '$cantidad', '$precio_unitario', '$total')";

            if (!$conex->query($sql)) {
                echo "<div class='alert alert-danger'>Error al guardar el producto $producto: " . $conex->error . "</div>";
            }
        }

        // Mostrar mensaje de éxito
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Compra realizada con éxito',
                text: 'Te llegará un correo electrónico con más información.',
                confirmButtonText: 'Aceptar',
            }).then(() => {
                window.location.href = 'carrito.php';
            });
        </script>";

        // Vaciar el carrito después de guardar
        unset($_SESSION['cart']);
    } else {
        echo "<div class='alert alert-danger'>Error: Los datos del carrito no coinciden.</div>";
    }
}
?>
