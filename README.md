# Weather App PHP

This is a simple weather application built with PHP, HTML, CSS (Tailwind), and JavaScript. It fetches current weather and 5-day forecast data from the OpenWeatherMap API and displays it on the front end.

---

## Features

- Search for current weather by city name.
- Display temperature, humidity, wind speed, visibility, feels-like temperature, sunrise, and sunset times.
- Show 5-day hourly weather forecast with temperature and weather icons.
- Interactive chart displaying temperature changes over time.
- Responsive design using Tailwind CSS.
- Data fetched asynchronously using JavaScript fetch API and PHP backend.

---

## Technologies Used

- **PHP**: Server-side scripting and API proxy.
- **JavaScript**: Fetch API to request weather data and Chart.js for visualization.
- **Chart.js**: For rendering the temperature line chart.
- **Tailwind CSS**: For styling and responsive layout.
- **OpenWeatherMap API**: For weather data (requires API key).

---

## Setup and Usage

1. Clone the repository:
   ```
   git clone https://github.com/MohammadMashaikh/Weather-App-PHP.git
   ```

2. Open the project directory in your local server environment (e.g., XAMPP, WAMP).

3. Obtain an API key from [OpenWeatherMap](https://openweathermap.org/api).

4. Update the API key in your PHP files (`fetch-weather.php`, `forecast.php`, `api.php`) where the `$ApiKey` variable is defined.

5. Access `index.php` via your local server URL.

6. Enter a city name and hit "Search" to view the current weather and forecast.

---

## File Structure

- `index.php` — Main front-end HTML page with embedded JavaScript.
- `fetch-weather.php` — PHP script to fetch current weather from OpenWeatherMap.
- `forecast.php` — PHP script to fetch weather forecast data.
- `api.php` — Helper PHP functions for API requests (if used).
- Other assets (optional CSS, etc.).

---

## Notes

- Temperatures are converted from Kelvin to Celsius.
- Forecast data shows temperature every 3 hours (limited by OpenWeatherMap API).
- Chart and hourly forecast update dynamically after a search.
- Make sure your server supports PHP and cURL.

---

## License

This project is open source and available under the MIT License.

---

## Author

Mohammad Mashaikh  
GitHub: [https://github.com/MohammadMashaikh](https://github.com/MohammadMashaikh)

