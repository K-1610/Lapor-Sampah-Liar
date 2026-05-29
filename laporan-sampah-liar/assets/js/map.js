document.addEventListener("DOMContentLoaded", function () {

    // Cek apakah element map ada
    const mapElement = document.getElementById('map');

    if (!mapElement) return;

    // Default lokasi
    const defaultLat = -6.7345294376528315;
    const defaultLng = 108.53114633158512;

    // Input form
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const alamatInput = document.getElementById('alamat');

    // Inisialisasi map
    const map = L.map('map').setView(
        [defaultLat, defaultLng],
        13
    );

    // Modern map style
    L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
        {
            attribution: '&copy; OpenStreetMap & CartoDB'
        }
    ).addTo(map);

    // Marker draggable
    const marker = L.marker(
        [defaultLat, defaultLng],
        {
            draggable: true
        }
    ).addTo(map);

    // Update input lokasi
    function updateLocation(lat, lng) {

        latitudeInput.value = lat;
        longitudeInput.value = lng;

        getAddress(lat, lng);
    }

    // Reverse geocoding
    function getAddress(lat, lng) {

        fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
        )
        .then(response => response.json())
        .then(data => {

            alamatInput.value =
                data.display_name || '';

        })
        .catch(error => {

            console.error(
                'Gagal mengambil alamat:',
                error
            );

        });

    }

    // Set default awal
    updateLocation(defaultLat, defaultLng);

    // Saat marker digeser
    marker.on('dragend', function () {

        const position = marker.getLatLng();

        updateLocation(
            position.lat,
            position.lng
        );

    });

    // Tombol deteksi lokasi
    const detectButton =
        document.getElementById('detectLocation');

    if (detectButton) {

        detectButton.addEventListener(
            'click',
            function () {

                if (!navigator.geolocation) {

                    alert(
                        'Browser tidak mendukung GPS'
                    );

                    return;
                }

                navigator.geolocation.getCurrentPosition(

                    function (position) {

                        const lat =
                            position.coords.latitude;

                        const lng =
                            position.coords.longitude;

                        // Pindahkan map
                        map.setView([lat, lng], 16);

                        // Pindahkan marker
                        marker.setLatLng([lat, lng]);

                        // Update input
                        updateLocation(lat, lng);

                    },

                    function (error) {

                        console.log(error);

                        switch(error.code) {

                            case error.PERMISSION_DENIED:
                                alert('Izin lokasi ditolak user.');
                                break;

                            case error.POSITION_UNAVAILABLE:
                                alert('Lokasi tidak tersedia.');
                                break;

                            case error.TIMEOUT:
                                alert('GPS timeout.');
                                break;

                            default:
                                alert('Terjadi kesalahan GPS.');
                                break;
                        }

                    }

                );

            }
        );

    }

});