<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar idioma
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/assets/languages/loadLanguage.php';
// Controlador de usuario
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/UserController.php';
$userController = new UserController();

// Manejar registro
$registerError = '';
$registerSuccess = false;
$prevData = ['nickname'=>'','first_name'=>'','last_name'=>'','email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $data = [
        'nickname'   => trim($_POST['nickname'] ?? ''),
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name'  => trim($_POST['last_name'] ?? ''),
        'email'      => trim($_POST['email'] ?? ''),
        'password'   => $_POST['password'] ?? ''
    ];

    $prevData = $data;
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($data['password'] !== $confirmPassword) {
        $registerError = $langTexts['password_mismatch'];
    } else {
        $result = $userController->register($data);
        if ($result['success']) {
            $registerSuccess = true;
            $prevData = ['nickname'=>'','first_name'=>'','last_name'=>'','email'=>''];
        } else {
            $registerError = $result['error'] ?? $langTexts['register_error'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draftosaurus - <?= $langTexts['register_title'] ?></title>

    <!-- Bootstrap y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="/public/css/pages/register.css">
    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/layouts/navbar.css">
    <link rel="stylesheet" href="/public/css/layouts/footer.css">
</head>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">

        <!-- Navbar -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/navbar.php'; ?>

        <!-- Contenido principal -->
        <main class="container flex-grow-1 mt-5 pt-5">
            <div class="hero-section mx-auto">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <h2 class="text-center hero-title mb-4"><?= $langTexts['register_heading'] ?></h2>

                        <?php if($registerError): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
                        <?php elseif($registerSuccess): ?>
                        <div class="alert alert-success"><?= $langTexts['register_success'] ?></div>
                        <?php endif; ?>

                        <form method="POST" id="registerForm" novalidate>
                            <input type="hidden" name="register" value="1">

                            <!-- Nickname -->
                            <div class="mb-3">
                                <label for="nickname" class="form-label"><?= $langTexts['nickname'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nickname" name="nickname"
                                        value="<?= htmlspecialchars($prevData['nickname']) ?>" required>
                                    <span class="input-group-text" id="nicknameStatus"></span>
                                </div>
                            </div>

                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="first_name" class="form-label"><?= $langTexts['first_name'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= htmlspecialchars($prevData['first_name']) ?>" required>
                                </div>
                            </div>

                            <!-- Apellido -->
                            <div class="mb-3">
                                <label for="last_name" class="form-label"><?= $langTexts['last_name'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= htmlspecialchars($prevData['last_name']) ?>" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= $langTexts['email'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($prevData['email']) ?>" required>
                                </div>
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= $langTexts['password'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="<?= $langTexts['password'] ?>" required>
                                    <span class="input-group-text toggle-password" data-target="#password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="mb-3">
                                <label for="confirmPassword"
                                    class="form-label"><?= $langTexts['confirm_password'] ?></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="confirmPassword" required>
                                    <span class="input-group-text toggle-password" data-target="#confirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Aceptar términos -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="acceptTerms">
                                <label class="form-check-label" for="acceptTerms">
                                    <?= $langTexts['accept_terms'] ?>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-custom w-100" id="registerBtn"
                                disabled><?= $langTexts['register_button'] ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'; ?>
    </div>

    <!-- Modal Términos y Condiciones -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $langTexts['terms_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
                    <h2 class="title"><?= $langTexts['terms_intro_title'] ?></h2>
                    <p><?= $langTexts['terms_intro_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_age_title'] ?></h2>
                    <p><?= $langTexts['terms_age_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_account_title'] ?></h2>
                    <p><?= $langTexts['terms_account_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_usage_title'] ?></h2>
                    <p><?= $langTexts['terms_usage_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_content_title'] ?></h2>
                    <p><?= $langTexts['terms_content_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_responsibility_title'] ?></h2>
                    <p><?= $langTexts['terms_responsibility_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_modifications_title'] ?></h2>
                    <p><?= $langTexts['terms_modifications_text'] ?></p>

                    <h2 class="title"><?= $langTexts['terms_contact_title'] ?></h2>
                    <p><?= $langTexts['terms_contact_text'] ?></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?= $langTexts['close'] ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/register.js"></script>
</body>

</html>