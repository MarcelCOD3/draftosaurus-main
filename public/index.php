<?php
// Inicia sesión solo si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no se pasa 'page', redirige al index.html por defecto
if (!isset($_GET['page'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/app/views/pages/index.html';
    exit();
}

// Manejo de idioma
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'es';
}

// Carga de idioma
require_once __DIR__ . '/../app/assets/languages/loadLanguage.php';

// Carga controlador de usuario
require_once __DIR__ . '/../app/controllers/UserController.php';
$userController = new UserController();

// Determinar la pagina
$page = $_GET['page'] ?? 'main';
$page = basename($page);

// Manejo de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $redirectQuery = $_POST['current_query'] ?? 'page=' . $page;

    $result = $userController->login($email, $password);

    if ($result['success']) {
        header("Location: /public/index.php?" . $redirectQuery);
        exit();
    } else {
        if (!empty($result['banned_until'])) {
            $bannedUntil = date('d/m/Y H:i', strtotime($result['banned_until']));
            $_SESSION['loginError'] = "Esta cuenta está baneada hasta el $bannedUntil.";
        } else {
            $_SESSION['loginError'] = "Correo o contraseña incorrectos";
        }
        $_SESSION['showLoginModal'] = true;
        header("Location: /public/index.php?" . $redirectQuery);
        exit();
    }
}

// Manejo de logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $userController->logout();
    exit();
}

// Rutas de vistas
$viewsPath = __DIR__ . '/../app/views/pages/';
$errorsPath = __DIR__ . '/../app/views/errors/';
$pageFile = $viewsPath . $page . '.php';
$error404 = $errorsPath . '404.php';

if (file_exists($pageFile)) {
    include $pageFile;
} else {
    http_response_code(404);
    include $error404;
}
exit();
?>