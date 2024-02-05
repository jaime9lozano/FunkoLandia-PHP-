<?php


use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/models/Funko.php';

$session = $sessionService = SessionService::getInstance();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    // El valor de "id" es un número entero válido
    // Puedes utilizarlo en tu lógica de aplicación
    $config = Config::getInstance();
    $funkosService = new FunkoService($config->db);
    $funko = $funkosService->findById($id);
    if ($funko === null) {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Funko</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>

    <h1>Detalles del Funko</h1>
    <dl class="row">
        <dt class="col-sm-2">ID:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars($funko->id); ?></dd>
        <dt class="col-sm-2">Nombre:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars($funko->nombre); ?></dd>
        <dt class="col-sm-2">Precio:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars($funko->precio); ?></dd>
        <dt class="col-sm-2">Imagen:</dt>
        <dd class="col-sm-10"><img alt="Producto Image" class="img-fluid"
                                   src="<?php echo htmlspecialchars($funko->imagen); ?>"></dd>
        <dt class="col-sm-2">Cantidad:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars($funko->cantidad); ?></dd>
        <dt class="col-sm-2">Categoría:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars($funko->categoriaNombre); ?></dd>
    </dl>
    <a class="btn btn-primary" href="index.php">Volver</a>
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
