document.addEventListener('DOMContentLoaded', () => {

    // 1. HERO SLIDER AUTOMATION
    const slides = document.querySelectorAll('.hero-slider .slide');
    let currentSlide = 0;

    if (slides.length > 0) {
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000); // Berganti setiap 5 detik
    }

    // 2. STATS ANIMATED COUNTER ON SCROLL
    const statNumbers = document.querySelectorAll('.stat-number');
    let animated = false;

    const animateCounters = () => {
        statNumbers.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const speed = 200; // Semakin kecil semakin cepat
            const inc = target / speed;

            let count = 0;
            const updateCount = () => {
                count += inc;
                if (count < target) {
                    counter.innerText = Math.ceil(count);
                    setTimeout(updateCount, 15);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    };

    // Trigger animasi angka saat elemen terlihat di viewport
    window.addEventListener('scroll', () => {
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            const sectionPos = statsSection.getBoundingClientRect().top;
            const screenPos = window.innerHeight / 1.2;

            if (sectionPos < screenPos && !animated) {
                animated = true;
                animateCounters();
            }
        }
    });

    // 3. DIGITAL CLOCK & CALENDAR WIDGET
    const updateDateTime = () => {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        
        const dayNameEl = document.getElementById('calendarDayName');
        const dateEl = document.getElementById('calendarDate');
        const monthYearEl = document.getElementById('calendarMonthYear');
        const clockEl = document.getElementById('clockTime');
        
        if (dayNameEl) dayNameEl.textContent = days[now.getDay()];
        if (dateEl) dateEl.textContent = now.getDate();
        if (monthYearEl) monthYearEl.textContent = `${months[now.getMonth()]} ${now.getFullYear()}`;
        
        if (clockEl) {
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            clockEl.textContent = `${hh}:${mm}:${ss}`;
        }
    };
    
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // 4. WEATHER WIDGET (OPEN-METEO API FOR BANDA ACEH)
    const fetchWeather = async () => {
        const weatherCard = document.getElementById('weatherCard');
        if (!weatherCard) return;
        
        try {
            const response = await fetch('https://api.open-meteo.com/v1/forecast?latitude=5.5483&longitude=95.3238&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia/Jakarta');
            if (!response.ok) throw new Error('API failed');
            const data = await response.json();
            
            const temp = Math.round(data.current.temperature_2m);
            const weatherCode = data.current.weather_code;
            const humidity = data.current.relative_humidity_2m;
            const windSpeed = data.current.wind_speed_10m;
            
            // Map weather code (WMO code) to Indonesian text & Lucide icons
            let weatherDesc = 'Cerah Berawan';
            let weatherIcon = 'cloud-sun';
            
            if (weatherCode === 0) {
                weatherDesc = 'Cerah';
                weatherIcon = 'sun';
            } else if ([1, 2, 3].includes(weatherCode)) {
                weatherDesc = 'Cerah Berawan';
                weatherIcon = 'cloud-sun';
            } else if ([45, 48].includes(weatherCode)) {
                weatherDesc = 'Kabut';
                weatherIcon = 'cloud-fog';
            } else if ([51, 53, 55, 56, 57].includes(weatherCode)) {
                weatherDesc = 'Gerimis';
                weatherIcon = 'cloud-drizzle';
            } else if ([61, 63, 65, 66, 67].includes(weatherCode)) {
                weatherDesc = 'Hujan';
                weatherIcon = 'cloud-rain';
            } else if ([71, 73, 75, 77, 85, 86].includes(weatherCode)) {
                weatherDesc = 'Salju';
                weatherIcon = 'snowflake';
            } else if ([80, 81, 82].includes(weatherCode)) {
                weatherDesc = 'Hujan Lebat';
                weatherIcon = 'cloud-showers-heavy';
            } else if ([95, 96, 99].includes(weatherCode)) {
                weatherDesc = 'Hujan Badai';
                weatherIcon = 'cloud-lightning';
            }
            
            weatherCard.innerHTML = `
                <div class="weather-info animate-fade-in">
                    <div class="weather-loc">
                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> Banda Aceh, Aceh
                    </div>
                    <div class="weather-temp-icon">
                        <i data-lucide="${weatherIcon}" class="weather-icon-large"></i>
                        <span class="weather-temp">${temp}°C</span>
                    </div>
                    <div class="weather-desc">${weatherDesc}</div>
                    <div class="weather-details">
                        <div class="weather-detail-item" title="Kelembapan">
                            <i data-lucide="droplets"></i>
                            <span>${humidity}%</span>
                        </div>
                        <div class="weather-detail-item" title="Kecepatan Angin">
                            <i data-lucide="wind"></i>
                            <span>${windSpeed} km/h</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Re-trigger Lucide icons creation for the newly inserted elements
            if (window.lucide) {
                window.lucide.createIcons();
            }
        } catch (error) {
            console.error('Error fetching weather:', error);
            // Fallback UI
            const hours = new Date().getHours();
            const isNight = hours < 6 || hours > 18;
            const fallbackTemp = isNight ? 26 : 31;
            const fallbackDesc = 'Cerah Berawan';
            const fallbackIcon = isNight ? 'cloud-moon' : 'cloud-sun';
            
            weatherCard.innerHTML = `
                <div class="weather-info">
                    <div class="weather-loc">
                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> Banda Aceh, Aceh
                    </div>
                    <div class="weather-temp-icon">
                        <i data-lucide="${fallbackIcon}" class="weather-icon-large"></i>
                        <span class="weather-temp">${fallbackTemp}°C</span>
                    </div>
                    <div class="weather-desc">${fallbackDesc}</div>
                    <div class="weather-details">
                        <div class="weather-detail-item" title="Kelembapan">
                            <i data-lucide="droplets"></i>
                            <span>78%</span>
                        </div>
                        <div class="weather-detail-item" title="Kecepatan Angin">
                            <i data-lucide="wind"></i>
                            <span>8 km/h</span>
                        </div>
                    </div>
                </div>
            `;
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    };
    
    fetchWeather();
});
