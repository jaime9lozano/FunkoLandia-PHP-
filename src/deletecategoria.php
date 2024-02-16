<?php

use config\Config;
use models\Categoria;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/services/SessionService.php';

// Solo se puede borrar si en la sesión el usuario es admin
$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    // No enviar ninguna salida antes de este bloque de código
    echo "<script type='text/javascript'>
            alert('No tienes permisos para eliminar una categoria');
            window.location.href = 'categorias.php';
          </script>";
    exit;
}


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_REGEXP, [
    'options' => [
        'regexp' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i'
    ]
]);
$categoria = null;

if ($id === false) {
    header('Location: categorias.php');
    exit;
} else {
    // El valor de "id" es un número entero válido
    // Puedes utilizarlo en tu lógica de aplicación
    $config = Config::getInstance();
    $categoriasService = new CategoriasService($config->db);
    // Debemos borrar la imagen si existe antes de borrar el producto
    $categoria = $categoriasService->findById($id);
    if ($categoria) {
        $categoriasService->deleteById($id);
        echo "<script type='text/javascript'>
                alert('Categoria eliminado correctamente');
                window.location.href = 'categorias.php';
                </script>";
    }
}
