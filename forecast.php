<?php
require './api.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city'])) {
    $city = trim($_POST['city']);
    $ApiKey = "1b006de33827f13d38f9419bc24ef83e";

    // Make variables available to the global-scoped function
    $GLOBALS['ApiKey'] = $ApiKey;
    $GLOBALS['city'] = $city;

    $forecast = getForecastWeather(); // ✅ no arguments
    echo json_encode(['list' => array_slice($forecast, 0, 8)]); // only send 8 items
} else {
    echo json_encode(['error' => 'Invalid request']);
}
