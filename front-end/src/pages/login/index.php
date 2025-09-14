<?php
session_start();


if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header('Location: ../alarm/index.php');
    exit;
}

//.env
require_once __DIR__ . '../../../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

// var error
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';


    //get api login
    $url = rtrim($_ENV['API_ENDPOINT'], '/') . '/Login';

    $data = [
        "gmail" => $user,
        "senha" => $pass
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\nAccept: text/plain\r\n",
            "method"  => "POST",
            "content" => json_encode($data),
            "ignore_errors" => true
        ]
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        $erro = "Erro interno do servidor. Tente novamente mais tarde.";
    } else {
        $json = json_decode($response, true);

        if (isset($json['status'])) {


            if ($json['status'] === true && !empty($json['jwtToken'])) {
                $jwt = $json['jwtToken'];

                // payload do JWT
                $parts = explode('.', $jwt);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

                    if (!empty($payload['alarmAuth']) && $payload['alarmAuth'] === 'True') {
                        $_SESSION['logged']   = true;
                        $_SESSION['jwtToken'] = $jwt;

                        // get user name
                        $userId = $payload['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/sid'] ?? null;
                        if ($userId) {
                            $urlUser = rtrim($_ENV['API_ENDPOINT'], '/') . '/Usuario/' . $userId;

                            $opts = [
                                "http" => [
                                    "header" => "Authorization: Bearer " . $jwt . "\r\nAccept: application/json\r\n",
                                    "method" => "GET",
                                    "ignore_errors" => true
                                ]
                            ];
                            $ctx  = stream_context_create($opts);
                            $resp = @file_get_contents($urlUser, false, $ctx);

                            if ($resp !== false) {
                                $userJson = json_decode($resp, true);
                                if (!empty($userJson['nome'])) {
                                    $_SESSION['name'] = $userJson['nome'];
                                }
                            }
                        }

                        header('Location: ../alarm/index.php');
                        exit;
                    } else {
                        $erro = "Você não tem permissão para acessar este sistema";
                    }
                } else {
                    $erro = "Token inválido";
                }
            } else {
                $erro = "Usuário ou senha incorretos";
            }
        }

        // internal server error
        else {
            $erro = "Erro interno do servidor. Tente novamente mais tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Sistema de Alarme</title>
    <link rel="stylesheet" href="./login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="login-container">
        <img src="../../../assets/images/security-management.png" alt="security-logo">
        <h1>IAS</h1>
        <?php if ($erro) echo "<p class='error'>$erro</p>"; ?>
        <form method="post">
            <input type="text" name="user" placeholder="Usuário" required>
            <input type="password" name="pass" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>