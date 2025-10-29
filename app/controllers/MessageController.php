<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validaciones
    if ($name === '' || $email === '' || $message === '') {
        $_SESSION['message_status'] = 'error';
        $_SESSION['message_text'] = 'Todos los campos son obligatorios.';
        header("Location: /draftosaurus/public/index.php?page=about");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message_status'] = 'error';
        $_SESSION['message_text'] = 'Email no válido.';
        header("Location: /draftosaurus/public/index.php?page=about");
        exit;
    }

    $to = "softwarexstudio@gmail.com";
    $subject = "Nuevo mensaje desde About";
    $body = "De: $name\nCorreo: $email\nMensaje:\n$message";
    $headers = "From: $email\r\nReply-To: $email";

    if (mail($to, $subject, $body, $headers)) {
        $_SESSION['message_status'] = 'success';
        $_SESSION['message_text'] = 'Mensaje enviado correctamente.';
    } else {
        $_SESSION['message_status'] = 'error';
        $_SESSION['message_text'] = 'Hubo un error al enviar el mensaje.';
    }

    header("Location: /draftosaurus/public/index.php?page=about");
    exit;
}