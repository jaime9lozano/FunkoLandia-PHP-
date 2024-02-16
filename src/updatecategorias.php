<?php

use config\Config;
use models\Categoria;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';

// Solo se puede modificar si en la sesión el usuario es admin
$session = SessionService::getInstance();
if (!$session->isAdmin()) {
// No enviar ninguna salida antes de este bloque de código
echo "<script type='text/javascript'>
    alert('No tienes permisos para modificar una categoria');
    window.location.href = 'categorias.php';
</script>";
exit;
}


$config = Config::getInstance();
$categoriasService = new CategoriasService($config->db);;

$errores = [];
$categoria = null;

// Obtenemos el ID del producto a editar
$productoId = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$productoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_REGEXP, [
    'options' => [
        'regexp' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i'
    ]
]);

// Si no se proporciona un ID, redirigimos al index
if (!$productoId) {
echo "<script type='text/javascript'>
    alert('No se proporcionó un ID de categoria');
    window.location.href = 'categorias.php';
</script>";
header('Location: categorias.php');
exit;
}

// Intentamos obtener el producto por su ID
try {
$categoria = $categoriasService->findById($productoId);
} catch (Exception $e) {
$error = 'Error en el sistema. Por favor intente más tarde.';
}

// Si no encontramos el producto, también redirigimos al index
if (!$categoria) {
    header('Location: index.php');
    exit;
}
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Aquí iría el código para procesar los datos del formulario y actualizar el producto
// Debes asegurarte de validar los datos de la misma manera que en el archivo create.php
// ...

// filtramos los datos
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$productoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_REGEXP, [
    'options' => [
        'regexp' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i'
    ]
]);

// Validamos los datos
if (empty($nombre)) {
$errores['nombre'] = 'El nombre es obligatorio.';
}

// Si no hay errores, actualizamos el producto
if (count($errores) === 0) {
// Actualizamos el producto
// Creamos el producto
$categoria = new Categoria();
$categoria->nombre = $nombre;
$categoria->id = $productoId;


try {
$categoriasService->update($categoria);
echo "<script type='text/javascript'>
    alert('Categoria actualizado correctamente');
    window.location.href = 'categorias.php';
</script>";
} catch (Exception $e) {
$error = 'Error en el sistema. Por favor intente más tarde.';
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>
    <h1>Actualizar Categoria</h1>

    <form action="updatecategorias.php" method="post">

        <input type="hidden" name="id" value="<?php echo $productoId; ?>">

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input class="form-control" id="nombre" name="nombre" type="text" required
                   value="<?php echo htmlspecialchars($categoria->nombre); ?>">
            <?php if (isset($errores['nombre'])): ?>
                <small class="text-danger"><?php echo $errores['nombre']; ?></small>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a class="btn btn-secondary mx-2" href="categorias.php">Volver</a>
    </form>
</div>

<?php require_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>
