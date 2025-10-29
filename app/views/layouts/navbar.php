<?php
// Inicia sesión solo si no hay ninguna activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nickname = $_SESSION['nickname'] ?? null;
$page = basename($_GET['page'] ?? 'main'); 

// Asegurarnos de que $langTexts exista
if (!isset($langTexts)) {
    $langTexts = [];
}
?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="/public/index.php?page=main">
            <i class="fas fa-dragon me-2"></i>Draftosaurus
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links del navbar -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'main' ? 'active' : '' ?>"
                        href="/public/index.php?page=main">
                        <?= $langTexts['home'] ?? 'Inicio' ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'rules' ? 'active' : '' ?>"
                        href="/public/index.php?page=rules">
                        <?= $langTexts['rules'] ?? 'Reglas' ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'ranking' ? 'active' : '' ?>"
                        href="/public/index.php?page=ranking">
                        <?= $langTexts['ranking'] ?? 'Ranking' ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'register' ? 'active' : '' ?>"
                        href="/public/index.php?page=register">
                        <?= $langTexts['register'] ?? 'Registro' ?>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= $page === 'miniGame' ? 'active' : '' ?>"
                        href="/public/index.php?page=miniGame">
                        <?= $langTexts['dino runner'] ?? 'Correcadinos' ?></a></li>

                <li class="nav-item">
                    <a class="nav-link <?= $page === 'about' ? 'active' : '' ?>"
                        href="/public/index.php?page=about">
                        <?= $langTexts['about'] ?? 'Sobre Nosotros' ?>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Login + Language -->
        <div class="ms-auto d-flex gap-2 align-items-center">

            <!-- Dropdown de idioma -->
            <div class="dropdown">
                <button class="btn btn-custom dropdown-toggle" type="button" id="languageDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    🌐
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                    <li>
                        <a class="dropdown-item" href="/public/index.php?page=<?= $page ?>&lang=en">
                            <img src="/public/img/flag-uk.png" alt="English" width="20" class="me-2">
                            English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/public/index.php?page=<?= $page ?>&lang=es">
                            <img src="/public/img/flag-spain.png" alt="Español" width="20" class="me-2">
                            Español
                        </a>
                    </li>
                </ul>
            </div>

            <?php if (!empty($nickname)): ?>
            <div class="dropdown">
                <a class="btn btn-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i><?= htmlspecialchars($nickname) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item"
                            href="/public/index.php?page=userProfile&nickname=<?= urlencode($nickname) ?>">
                            <i class="fas fa-id-badge me-2"></i><?= $langTexts['myProfile'] ?? 'Mi Perfil' ?>
                        </a>
                    </li>
                    <!-- Solo si es admin -->
                    <?php
                    //var_dump($_SESSION['is_admin']); 
?>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <li>
                        <a class="dropdown-item" href="/public/index.php?page=adminPanel">
                            <i class="fas fa-tools me-2"></i><?= $langTexts['adminPanel'] ?? 'Panel Admin' ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <form method="post" style="margin:0;">
                        <button type="submit" name="logout" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i><?= $langTexts['logout'] ?? 'Cerrar sesión' ?>
                        </button>
                    </form>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-sign-in-alt me-2"></i><?= $langTexts['login'] ?? 'Login' ?>
            </button>
            <?php endif; ?>
        </div>
    </div>

</nav>
<?php if (!empty($_SESSION['showLoginModal'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
});
</script>
<?php unset($_SESSION['showLoginModal']); ?>
<?php endif; ?>

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i
                        class="fas fa-user-circle me-2"></i><?= $langTexts['login'] ?? 'Iniciar Sesión' ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="login-alert">
                    <?php if (!empty($_SESSION['loginError'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['loginError']) ?>
                    </div>
                    <?php unset($_SESSION['loginError']); ?>
                    <?php endif; ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    <input type="hidden" name="current_query"
                        value="<?= htmlspecialchars($_SERVER['QUERY_STRING'] ?? 'page=main') ?>">

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email"
                                placeholder="<?= $langTexts['email'] ?? 'Correo electrónico' ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control toggle-password-input" name="password"
                                placeholder="<?= $langTexts['password'] ?? 'Contraseña' ?>" required>
                            <span class="input-group-text toggle-password" data-target=".toggle-password-input">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"><?= $langTexts['close'] ?? 'Cerrar' ?></button>
                        <button type="submit" class="btn btn-custom"><?= $langTexts['enter'] ?? 'Entrar' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS separado -->
<script src="/public/js/navbar.js"></script>