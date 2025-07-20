<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <!-- <link rel="stylesheet" href="./style.css"> -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gradient-to-br from-blue-300 to-indigo-600 min-h-screen flex flex-col items-center justify-start py-12 px-4 text-white font-sans">

  <h1 class="text-4xl font-bold mb-8 drop-shadow-lg">Weather App</h1>

  <form id="weather-form" class="flex w-full max-w-md mb-8" novalidate>
    <input
      type="text"
      id="city"
      name="city"
      placeholder="Enter city"
      class="flex-grow rounded-l-md px-4 py-2 text-gray-900 focus:outline-none"
      required
    />
    <button
      type="submit"
      class="bg-purple-200 hover:bg-purple-400 rounded-r-md px-6 font-semibold text-gray-900 transition"
    >
      Search
    </button>
  </form>

  <div id="weather-info" class="bg-white bg-opacity-20 rounded-lg p-6 w-full max-w-md shadow-lg mb-8 hidden">
    <img id="weather-icon" class="w-24 h-24 mx-auto mb-4" alt="Weather Icon" />
    <div id="temp-div" class="text-5xl font-extrabold text-center mb-2"></div>
    <p id="city-name" class="text-center text-lg font-semibold"></p>
    <p id="description" class="text-center capitalize mb-4"></p>

    <div class="grid grid-cols-2 gap-4 text-sm">
      <p><strong>Humidity:</strong> <span id="humidity"></span>%</p>
      <p><strong>Wind Speed:</strong> <span id="wind-speed"></span> m/s</p>
      <p><strong>Visibility:</strong> <span id="visibility"></span> km</p>
      <p><strong>Feels Like:</strong> <span id="feels-like"></span>°C</p>
      <p><strong>Sunrise:</strong> <span id="sunrise"></span></p>
      <p><strong>Sunset:</strong> <span id="sunset"></span></p>
    </div>
  </div>

  <canvas id="forecast-chart" class=" mb-8 shadow-lg rounded-lg bg-white bg-opacity-20" width="800" height="250"></canvas>

  <div id="hourly-forecast" class="w-full max-w-5xl grid grid-cols-6 gap-4"></div>

  <!-- Your existing scripts below -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Your existing JS logic here
    // Just update DOM selectors to match new IDs/classes

    document.getElementById('weather-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const city = document.getElementById('city').value.trim();
      if (!city) return alert('Please enter a city');

      fetch('fetch-weather.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'city=' + encodeURIComponent(city)
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
          document.getElementById('weather-info').classList.add('hidden');
          return;
        }

        document.getElementById('weather-info').classList.remove('hidden');
        document.getElementById('weather-icon').src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@4x.png`;
        document.getElementById('temp-div').textContent = `${Math.round(data.main.temp - 273.15)}°C`;
        document.getElementById('city-name').textContent = data.name;
        document.getElementById('description').textContent = data.weather[0].description;
        document.getElementById('humidity').textContent = data.main.humidity;
        document.getElementById('wind-speed').textContent = data.wind.speed;
        document.getElementById('visibility').textContent = (data.visibility / 1000).toFixed(1);
        document.getElementById('feels-like').textContent = Math.round(data.main.feels_like - 273.15);
        document.getElementById('sunrise').textContent = new Date(data.sys.sunrise * 1000).toLocaleTimeString();
        document.getElementById('sunset').textContent = new Date(data.sys.sunset * 1000).toLocaleTimeString();

        loadForecast(city);
      });
    });

    
   function loadForecast(city) {
  fetch('forecast.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'city=' + encodeURIComponent(city)
  })
  .then(res => res.json())
  .then(data => {
    console.log('Received forecast:', data);

    if (!Array.isArray(data.forecast)) return;

    const hours = [];
    const temps = [];
    const hourlyForecastContainer = document.getElementById('hourly-forecast');
    hourlyForecastContainer.innerHTML = '';

    for (let i = 0; i < 24; i++) {
      const forecast = data.forecast[i];

      // Format hour string "23:00" to "11 PM"
      const [hourStr] = forecast.hour.split(':');
      let hourNum = parseInt(hourStr, 10);
      const ampm = hourNum >= 12 ? 'PM' : 'AM';
      hourNum = hourNum % 12 || 12;
      const formattedHour = `${hourNum} ${ampm}`;

      hours.push(formattedHour);
      temps.push(forecast.temp);

      const forecastBlock = document.createElement('div');
      forecastBlock.className = 'flex flex-col items-center bg-white bg-opacity-20 rounded p-2';
      forecastBlock.innerHTML = `
        <span class="font-semibold">${formattedHour}</span>
        <img src="${forecast.icon}" alt="Icon" class="w-12 h-12" />
        <span>${forecast.temp}°C</span>
      `;
      hourlyForecastContainer.appendChild(forecastBlock);
    }

    new Chart(document.getElementById('forecast-chart'), {
      type: 'line',
      data: {
        labels: hours,
        datasets: [{
          label: 'Hourly Temp (°C)',
          data: temps,
          fill: false,
          borderColor: 'yellow',
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            ticks: {
              autoSkip: false,
              maxRotation: 45,
              minRotation: 30
            }
          },
          y: { beginAtZero: false }
        }
      }
    });
  });
}


  </script>

</body>

</html>