<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Capturar variables del modal login si existen
$loginError = $_SESSION['loginError'] ?? '';
$showLoginModal = $_SESSION['showLoginModal'] ?? false;

// Limpiar variables temporales del modal
unset($_SESSION['loginError'], $_SESSION['showLoginModal']);

// Comprobar si el usuario está logueado
$isLoggedIn = !empty($_SESSION['nickname']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draftosaurus - Juego de Mesa Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/layouts/navbar.css">
    <link rel="stylesheet" href="/public/css/partials/carousel.css">
    <link rel="stylesheet" href="/public/css/layouts/footer.css">
</head>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">

        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
        <main class="hero-section flex-grow-1">
            <div class="container py-4">
                <div class="row align-items-center">

                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="hero-carousel">
                            <?php include __DIR__ . '/../partials/carousel.php'; ?>
                        </div>
                    </div>

                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="hero-title"><?= $langTexts['heroTitle'] ?? 'UN JUEGO PARA TODA LA FAMILIA' ?></h1>
                        <p class="hero-subtitle">
                            <?= $langTexts['heroSubtitle'] ?? 'Sumérgete en una aventura jurásica épica...' ?></p>



                        <button type="button" class="btn btn-custom mb-3" data-bs-toggle="modal"
                            data-bs-target="#trackingModal">
                            <i class="fas fa-map-marker-alt me-2"></i><?= $langTexts['tracking'] ?? 'Seguimiento' ?>
                        </button>
                        <?php if ($isLoggedIn): ?>

                        <a href="/public/index.php?page=game" class="btn btn-custom mb-3">
                            <i class="fas fa-play me-2"></i><?= $langTexts['startGame'] ?? 'Comenzar Partida' ?>
                        </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </main>


        <?php require_once __DIR__ . '/../layouts/footer.php'; ?>

    </div>

    <!-- Modal de Seguimiento -->
    <div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="trackingModalLabel">Configurar Seguimiento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form id="trackingForm" action="/public/index.php?page=tracking" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="numPlayers" class="form-label">Cantidad de jugadores</label>
                            <select class="form-select" id="numPlayers" name="numPlayers" required>
                                <option value="">Seleccione...</option>
                                <option value="2">2 jugadores</option>
                                <option value="3">3 jugadores</option>
                                <option value="4">4 jugadores</option>
                                <option value="5">5 jugadores</option>
                            </select>
                        </div>

                        <div id="playerInputs"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mostrar modal login si hay error -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($showLoginModal): ?>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();

        <?php if ($loginError): ?>
        const alertPlaceholder = document.getElementById('login-alert');
        if (alertPlaceholder) {
            alertPlaceholder.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($loginError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        }
        <?php endif; ?>
        <?php endif; ?>
    });
    </script>
    <script src="/public/js/trackingModal.js"></script>

</body>

</html>