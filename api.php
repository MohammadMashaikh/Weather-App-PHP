<?php
$ApiKey = "YOUR-API-KEY";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city'])) {
    $city = $_POST['city'];
} else {
    return ['error' => 'Invalid access or missing city parameter'];
}


function getWeather()
{
    global $ApiKey;
    global $city;


    $ApiLink = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$ApiKey";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ApiLink);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);

    if ($response === false) {
        return ['error' => 'Error fetching data: ' . curl_error($curl)];
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if ($data === null || isset($data['message'])) {
        return ['error' => 'Enter a City to show the Data'];
    }

    return $data;
}




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

    if ($data === null) {
        return ['error' => 'Does not matter if its small or capital letter'];
    } elseif (isset($data['message']) && $data['message'] === 0) {
        $forecastHtml = '';
        foreach ($data['list'] as $forecast) {
            $timestamp = $forecast['dt'];
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timestamp);
            $hour = $dateTime->format('H');

            $temp = round($forecast['main']['temp'] - 273.15);
            $icon = $forecast['weather'][0]['icon'];

            $forecastHtml .= "
            <div class='hourly-item'>
                <span>{$hour}:00</span>
                <img src='https://openweathermap.org/img/wn/{$icon}.png' alt='Weather Icon'>
                <span>{$temp}°C</span>
            </div>
        ";
        }

        return $forecastHtml;
    }
}
