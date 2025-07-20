<?php

header('Content-Type: application/json');

$ApiKey = "1b006de33827f13d38f9419bc24ef83e";

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['city']) || empty($_POST['city'])) {
    echo json_encode(['error' => 'Invalid access or missing city parameter']);
    exit;
}

$city = trim($_POST['city']);

// Fetch both current and forecast weather
$currentWeather = getWeather($city, $ApiKey);
$forecastWeather = getForecastWeather($city, $ApiKey);

// Response structure
$response = [
    'current' => $currentWeather,
    'forecast' => $forecastWeather
];

echo json_encode($response);
exit;

/**
 * Fetch current weather
 */
function getWeather($city, $apiKey)
{
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}";

    $response = curlGet($url);
    if (isset($response['cod']) && $response['cod'] == 200) {
        return [
            'temperature' => round($response['main']['temp'] - 273.15),
            'description' => $response['weather'][0]['description'],
            'icon' => "https://openweathermap.org/img/wn/{$response['weather'][0]['icon']}@2x.png",
            'humidity' => $response['main']['humidity'],
            'wind_speed' => $response['wind']['speed'],
            'city' => $response['name']
        ];
    } else {
        return ['error' => $response['message'] ?? 'City not found.'];
    }
}

/**
 * Fetch forecast weather (next 5 days every 3 hours)
 */
function getForecastWeather()
{
    global $ApiKey;
    global $city;
    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$ApiKey";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $forecastUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);

    if ($response === false) {
        return ['error' => 'Error fetching data: ' . curl_error($curl)];
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (!isset($data['list'])) {
        return ['error' => 'Invalid data received from API'];
    }

    $forecastData = [];

    foreach ($data['list'] as $forecast) {
        $timestamp = $forecast['dt'];
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        $hour = $dateTime->format('H');

        $temp = round($forecast['main']['temp'] - 273.15);
        $icon = $forecast['weather'][0]['icon'];

        $forecastData[] = [
            'hour' => "{$hour}:00",
            'temp' => $temp,
            'icon' => "https://openweathermap.org/img/wn/{$icon}.png"
        ];
    }

    return $forecastData;
}


/**
 * Simple cURL GET request helper
 */
function curlGet($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($curl);
    if ($output === false) {
        return ['error' => 'cURL Error: ' . curl_error($curl)];
    }
    curl_close($curl);
    return json_decode($output, true);
}
