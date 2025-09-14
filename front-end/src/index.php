<?php
    session_start();

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true || empty($_SESSION['jwtToken'])) {
        header('Location: ./pages/login/index.php');
        exit;
    }

    // Pega o JWT da sessão
    $jwt = $_SESSION['jwtToken'];
    $parts = explode('.', $jwt);

    if (count($parts) !== 3) {
        // Token inválido
        session_destroy();
        header('Location: ./pages/login/index.php');
        exit;
    }

    // Decodifica o payload
    $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

    // Verifica expiração
    if (!isset($payload['exp']) || time() > $payload['exp']) {
        session_destroy();
        header('Location: ./pages/login/index.php');
        exit;
    }

    // Verifica alarmAuth
    if (!isset($payload['alarmAuth']) || $payload['alarmAuth'] !== 'True') {
        session_destroy();
        header('Location: ./pages/login/index.php');
        exit;
    }

    // Aqui libera acesso ao alarme
    header('Location: ./pages/alarm/index.php');
    exit;
?>