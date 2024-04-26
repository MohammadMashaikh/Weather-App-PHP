<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div id="weather-container">
        <h2>Weather App</h2>
        <form method="POST" action="">
            <input type="text" id="city" name="city" placeholder="Enter city" required>
            <button type="submit">Search</button>
        </form>
        <?php
        require './api.php';
        $data = getWeather();
        if (isset($data['error'])) {
            echo $data['error'] . '<br>';
        } else {
        ?>
        <img id="weather-icon" style="display: block;"
            src="https://openweathermap.org/img/wn/<?= $data['weather'][0]['icon']; ?>@4x.png" alt="Weather Icon">

        <div id="temp-div"><?= round($data['main']['temp'] - 273.15); ?>°C</div>

        <div id="weather-info">
            <p><?= $data['name']; ?></p>
            <p><?= $data['weather'][0]['description']; ?></p>
        </div>

        <div id="hourly-forecast">
            <?php
        }
            ?>
            <?php
            $data_forecast = getForecastWeather();
            if (is_string($data_forecast)) {
                echo $data_forecast; // Display the hourly forecast HTML
            }
            ?>
        </div>

    </div>
</body>

</html>