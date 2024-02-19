<?php
use config\Config;
use services\SessionService;
use services\UsersService;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/config/Config.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$error = '';
$success = '';
$usersService = new UsersService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar la entrada
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Verificar que la entrada no esté vacía después de la limpieza
    if (!$username || !$password || !$nombre || !$apellidos || !$email) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            // Crear el nuevo usuario
            $newUser = $usersService->createUser($username, $password, $nombre, $apellidos, $email);

            echo "<script type='text/javascript'>
                alert('Usuario creado correctamente. Inicie sesion');
                window.location.href = 'login.php';
                </script>";
        } catch (Exception $e) {
            $error = 'Error en el sistema. Por favor, intente más tarde.';
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
<div class="container" style="width: 50%; margin-left: auto; margin-right: auto;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            Mis funkos CRUD 2º DAW
        </a>
    </nav>
    <h1>Registro</h1>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input class="form-control" id="username" name="username" required type="text">
            <label for="password">Password:</label>
            <input class="form-control" id="password" name="password" required type="password">
            <label for="nombre">Nombre:</label>
            <input class="form-control" id="nombre" name="nombre" required type="text">
            <label for="apellidos">Apellidos:</label>
            <input class="form-control" id="apellidos" name="apellidos" required type="text">
            <label for="email">Email:</label>
            <input class="form-control" id="email" name="email" required type="email">
        </div>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Registrar</button>
    </form>
</div>

<?php
require_once 'footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>
