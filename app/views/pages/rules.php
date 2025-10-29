<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loginError = $_SESSION['loginError'] ?? '';
$showLoginModal = $_SESSION['showLoginModal'] ?? false;
unset($_SESSION['loginError'], $_SESSION['showLoginModal']);
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/assets/languages/loadLanguage.php';
?>

<!doctype html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Draftosaurus Rules</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/layouts/navbar.css">
    <link rel="stylesheet" href="/public/css/layouts/footer.css">
    <link rel="stylesheet" href="/public/css/views/rules.css">
</head>

<body>

    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="home-container">

        <div class="container my-4">
            <h1 class="title text-center"><?= $langTexts['rulesHeader'] ?></h1>

            <div class="accordion accordion-flush index my-4" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <?= $langTexts['rulesIndex'] ?>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul>
                                <li><a href="#preparation"><?= $langTexts['rule1'] ?></a></li>
                                <li><a href="#develop"><?= $langTexts['rule2'] ?></a></li>
                                <li><a href="#places"><?= $langTexts['rule3'] ?></a></li>
                                <li><a href="#summer"><?= $langTexts['rule4'] ?></a></li>
                                <li><a href="#dice"><?= $langTexts['rule5'] ?></a></li>
                                <li><a href="#winter"><?= $langTexts['rule6'] ?></a></li>
                                <li><a href="#extra"><?= $langTexts['rule7'] ?></a></li>
                                <li><a href="#video"><?= $langTexts['rule8'] ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rules-galery text-center mb-5">
            <img src="/draftosaurus/public/img/reglamento.png" class="rules mb-3" alt="rules main" />
            <img src="/draftosaurus/public/img/rules1.png" class="rules mb-3" id="preparation" alt="rules page 1" />
            <img src="/draftosaurus/public/img/rules2.png" class="rules mb-3" id="develop" alt="rules page 2" />
            <img src="/draftosaurus/public/img/rules3.png" class="rules mb-3" id="places" alt="rules page 3" />
            <img src="/draftosaurus/public/img/rules4.png" class="rules mb-3" id="summer" alt="rules page 4" />
            <img src="/draftosaurus/public/img/rules5.png" class="rules mb-3" id="dice" alt="rules page 5" />
            <img src="/draftosaurus/public/img/rules6.png" class="rules mb-3" id="winter" alt="rules page 6" />
            <img src="/draftosaurus/public/img/rules7.png" class="rules mb-3" id="extra" alt="rules page 7" />
        </div>

        <div class="container mb-5" id="video">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/-ZyFqRNkiAU" title="Draftosaurus - Tutorial"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>