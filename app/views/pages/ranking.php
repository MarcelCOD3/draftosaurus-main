<?php
// No llamamos session_start() porque ya se hizo en index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar textos del idioma
require_once $_SERVER['DOCUMENT_ROOT'] . 'app/assets/languages/loadLanguage.php';
// Capturar variables del modal login si existen
$loginError = $_SESSION['loginError'] ?? '';
$showLoginModal = $_SESSION['showLoginModal'] ?? false;
unset($_SESSION['loginError'], $_SESSION['showLoginModal']);

// Controladores
require_once __DIR__ . '/../../controllers/RankingController.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$rankingController = new RankingController();
$userController = new UserController();

// Obtener jugadores del ranking
$jugadores = $rankingController->getPlayers();
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draftosaurus - <?= $langTexts['rankingTitle'] ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/layouts/navbar.css">
    <link rel="stylesheet" href="/public/css/pages/ranking.css">
    <link rel="stylesheet" href="/public/css/layouts/footer.css">
</head>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">

        <?php include __DIR__ . '/../layouts/navbar.php'; ?>

        <main class="container flex-grow-1 py-5">
            <h1 class="text-center mb-4"><?= $langTexts['rankingHeader'] ?></h1>

            <div class="table-responsive">
                <table class="table table-hover table-striped ranking-table text-center w-100">
                    <thead>
                        <tr>
                            <th><?= $langTexts['rankingPosition'] ?></th>
                            <th><?= $langTexts['rankingUser'] ?></th>
                            <th><?= $langTexts['rankingGames'] ?></th>
                            <th><?= $langTexts['rankingWins'] ?></th>
                            <th><?= $langTexts['rankingPoints'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jugadores as $jugador): ?>
                        <tr>
                            <th>
                                <?php
                                if ($jugador['pos'] == 1) echo '<i class="bi bi-trophy-fill text-warning"></i> 1';
                                elseif ($jugador['pos'] == 2) echo '<i class="bi bi-trophy-fill text-secondary"></i> 2';
                                elseif ($jugador['pos'] == 3) echo '<i class="bi bi-trophy-fill" style="color: peru;"></i> 3';
                                else echo $jugador['pos'];
                                ?>
                            </th>
                            <td>
                                <a class="ranking-link"
                                    href="/draftosaurus/public/index.php?page=userProfile&nickname=<?= urlencode($jugador['nickname']) ?>">
                                    <?= htmlspecialchars($jugador['nickname']) ?>
                                </a>
                            </td>
                            <td><?= $jugador['partidas'] ?? 0 ?></td>
                            <td><?= $jugador['victorias'] ?? 0 ?></td>
                            <td><?= $jugador['puntaje'] ?? 0 ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <?php include __DIR__ . '/../layouts/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>