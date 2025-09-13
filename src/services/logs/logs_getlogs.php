<?php
    session_start();

    if(!isset($_SESSION['logged']) || $_SESSION['logged'] !== true)
    {
        http_response_code(401);
        echo json_encode(["error" => "Não autorizado"]);
        exit;
    }


    $logFile = __DIR__ . "/logs.json";

    $logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

    $lastLogs = array_Slice($logs, -40);

    echo json_encode($lastLogs);

?>