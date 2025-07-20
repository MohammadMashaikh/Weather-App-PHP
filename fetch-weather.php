<?php
header('Content-Type: application/json');

$ApiKey = "1b006de33827f13d38f9419bc24ef83e";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['city'])) {
    $city = urlencode($_POST['city']);
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$ApiKey}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['error' => curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    echo $response;
} else {
    echo json_encode(['error' => 'City not specified']);
}
