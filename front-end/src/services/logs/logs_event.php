<?php
session_start();

// check authentication
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// get event from POST
$event = $_POST['event'] ?? null;

// validate event
$validEvents = ['on', 'off'];
if (!in_array($event, $validEvents)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid event"]);
    exit;
}

$logFile = __DIR__ . "/logs.json";

// read existing logs or initialize empty array
$logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

// add new log entry
$logs[] = [
    "user" => $_SESSION['name'],
    "event" => $event,
    "time" => date("Y-m-d H:i:s")
];

// keep only last 40 entries
$logs = array_slice($logs, -40);

// save back to file
file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));

echo json_encode(["success" => true]);
?>
