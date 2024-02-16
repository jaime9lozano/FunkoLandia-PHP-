<?php

use config\Config;
use services\FunkoService;
use services\SessionService;

// Para cargar las clases automáticamente
require_once 'vendor/autoload.php';

// Para las sesiones y configuración
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/models/Funko.php';
$session = $sessionService = SessionService::getInstance();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Productos CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>

    <?php
    echo "<h1>{$session->getWelcomeMessage()}</h1>";
    $config = Config::getInstance();
    ?>

    <form action="index.php" class="mb-3" method="get">
        <div class="input-group">
            <input type="text" class="form-control" id="search" name="search" placeholder="Nombre">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Categoria</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Obtener el término de búsqueda si existe
        $searchTerm = $_GET['search'] ?? null;
        $funkosService = new FunkoService($config->db);
        $funkos = $funkosService->findAllWithCategoryName($searchTerm);
        ?>
        <?php foreach ($funkos as $funko): ?>
            <tr>
                <td><?php echo htmlspecialchars($funko->id); ?></td>
                <td><?php echo htmlspecialchars($funko->nombre); ?></td>
                <td><?php echo htmlspecialchars($funko->precio); ?></td>
                <td><?php echo htmlspecialchars($funko->cantidad); ?></td>
                <td><?php echo htmlspecialchars($funko->categoriaNombre); ?></td>
                <td>
                    <img alt="Imagen del funko" height="50"
                         src="<?php echo htmlspecialchars($funko->imagen); ?>" width="50">
                </td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="details.php?id=<?php echo $funko->id; ?>">Detalles</a>
                <?php
                if ($session->isAdmin()): ?>

                        <a class="btn btn-secondary btn-sm"
                           href="update.php?id=<?php echo $funko->id; ?>">Editar</a>
                        <a class="btn btn-info btn-sm"
                           href="update-image.php?id=<?php echo $funko->id; ?>">Imagen</a>
                        <a class="btn btn-danger btn-sm"
                           href="delete.php?id=<?php echo $funko->id; ?>"
                           onclick="return confirm('¿Estás seguro de que deseas eliminar este funko?');">
                            Eliminar
                        </a>
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    if ($session->isAdmin()): ?>
        <a class="btn btn-success" href="create.php">Nuevo Funko</a>
    <?php endif; ?>

    <p class="mt-4 text-center" style="font-size: smaller;">
        <?php
        if ($session->isLoggedIn()) {
            echo "<span>Nº de visitas: {$session->getVisitCount()}</span>";
            echo "<span>, desde el último login en: {$session->getLastLoginDate()}</span>";
        }
        ?>
    </p>


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
