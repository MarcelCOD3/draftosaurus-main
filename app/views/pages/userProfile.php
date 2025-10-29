<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar sistema de idiomas
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/assets/languages/loadLanguage.php';

// Controlador de usuario
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/UserController.php';
$userController = new UserController();

// Verificamos si se pasa un nickname por GET
$nickname = $_GET['nickname'] ?? null;
if (!$nickname) {
    header("Location: /public/index.php");
    exit();
}

// Obtener perfil completo con stats usando UserController
$profile = $userController->getUserProfile($nickname);
if (!$profile) {
    http_response_code(404);
    include $_SERVER['DOCUMENT_ROOT'] . '/app/views/errors/404.php';
    exit();
}

$userData = $profile['user'];
$stats = $profile['stats'];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($userData['nickname']) ?> - <?= htmlspecialchars($langTexts['userProfile_title']) ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/layouts/navbar.css">
    <link rel="stylesheet" href="/public/css/layouts/footer.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/navbar.php'; ?>

    <div class="row justify-content-center py-5">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_first_name'] ?>:</div>
                        <div class="col-7"><?= htmlspecialchars($userData['first_name']) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_last_name'] ?>:</div>
                        <div class="col-7"><?= htmlspecialchars($userData['last_name']) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_nickname'] ?>:</div>
                        <div class="col-7"><?= htmlspecialchars($userData['nickname']) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_games_played'] ?>:</div>
                        <div class="col-7"><?= $stats['partidas'] ?? 0 ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_wins'] ?>:</div>
                        <div class="col-7"><?= $stats['victorias'] ?? 0 ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 fw-bold text-end"><?= $langTexts['userProfile_total_score'] ?>:</div>
                        <div class="col-7"><?= $stats['puntaje_total'] ?? 0 ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>