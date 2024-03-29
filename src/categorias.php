<?php

use config\Config;
use services\CategoriasService;
use services\SessionService;

// Para cargar las clases automáticamente
require_once 'vendor/autoload.php';

// Para las sesiones y configuración
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';
$session = $sessionService = SessionService::getInstance();
// Solo se puede entrar si en la sesión el usuario es User
if (!$session->isLoggedIn()) {
    // No enviar ninguna salida antes de este bloque de código
    echo "<script type='text/javascript'>
            alert('No tienes permisos para entrar');
            window.location.href = 'index.php';
          </script>";
    exit;
}
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

    <?php
    $searchOption = '';
    if ($session->isAdmin()): ?>
        <form action="categorias.php" method="get" class="mb-3">
            <select name="searchOption">
                <option value="normales" <?php echo ($searchOption === 'normales') ? 'selected' : ''; ?>>Activas</option>
                <option value="eliminadas" <?php echo ($searchOption === 'eliminadas') ? 'selected' : ''; ?>>Eliminadas</option>
            </select>
            <input type="hidden" name="searchIsDeleted" value="true">
            <button class="btn btn-dark" type="submit">Buscar</button>
        </form>
    <?php endif; ?>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $searchOption = isset($_GET['searchOption']) ? $_GET['searchOption'] : 'normales';
        $searchTerm = $_GET['search'] ?? null;
        $searchIsDeleted = isset($_GET['searchIsDeleted']) && $_GET['searchIsDeleted'] === 'true';
        $categoriasService = new CategoriasService($config->db);
        if ($searchOption === 'normales') {
            // Realizar la búsqueda normal
            $categorias = $categoriasService->findAllWithName($searchTerm);
        } else{
            $categorias = $categoriasService->findAllWithDeleted($searchTerm);
        }
        ?>
        <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?php echo htmlspecialchars(substr($categoria->id, 0, 8)); ?></td>
                <td><?php echo htmlspecialchars($categoria->nombre); ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="detailscategoria.php?id=<?php echo $categoria->id; ?>">Detalles</a>
                <?php
                if ($session->isAdmin()): ?>
                        <a class="btn btn-secondary btn-sm"
                           href="updatecategorias.php?id=<?php echo $categoria->id; ?>">Editar</a>
                        <a class="btn btn-danger btn-sm"
                           href="deletecategoria.php?id=<?php echo $categoria->id; ?>"
                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoria?');">
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
        <a class="btn btn-success" href="createcategoria.php">Nueva Categoria</a>
    <?php endif; ?>
    <a class="btn btn-success" href="index.php">Volver a Funkos</a>
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

