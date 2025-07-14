// Inisialisasi Leaflet Map untuk halaman tambah kafe

document.addEventListener('DOMContentLoaded', function() {
    // Pastikan elemen peta ada
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Elemen peta tidak ditemukan!');
        return;
    }
    
    console.log('Menginisialisasi peta Leaflet...');
    
    // Koordinat default (Alun-Alun Jember)
    const defaultLat = -8.1722;
    const defaultLng = 113.6982;
    
    try {
        // Inisialisasi peta
        const mymap = L.map('map').setView([defaultLat, defaultLng], 13);
        
        // Tambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);
        
        // Inisialisasi marker
        let marker;
        
        // Fungsi untuk menambahkan marker
        function addMarker(lat, lng) {
            // Hapus marker sebelumnya jika ada
            if (marker) {
                mymap.removeLayer(marker);
            }
            
            // Tambahkan marker baru
            marker = L.marker([lat, lng]).addTo(mymap);
            
            // Update formulir
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('koordinat').value = lat + ', ' + lng;
        }
        
        // Event listener untuk klik pada peta
        mymap.on('click', function(e) {
            console.log('Peta diklik pada:', e.latlng);
            addMarker(e.latlng.lat, e.latlng.lng);
        });
        
        // Event listener untuk input koordinat manual
        const koordinatInput = document.getElementById('koordinat');
        if (koordinatInput) {
            koordinatInput.addEventListener('change', function() {
                const koordinat = this.value.split(',');
                if (koordinat.length === 2) {
                    const lat = parseFloat(koordinat[0].trim());
                    const lng = parseFloat(koordinat[1].trim());
                    if (!isNaN(lat) && !isNaN(lng)) {
                        console.log('Mengubah view peta ke koordinat:', lat, lng);
                        mymap.setView([lat, lng], 13);
                        addMarker(lat, lng);
                    }
                }
            });
        }
        
        // Perbaiki ukuran peta
        setTimeout(function() {
            mymap.invalidateSize();
            console.log('Ukuran peta diperbaiki');
        }, 100);
        
        console.log('Peta berhasil diinisialisasi');
    } catch (error) {
        console.error('Terjadi kesalahan saat menginisialisasi peta:', error);
        mapElement.innerHTML = '<div style="text-align: center; padding: 20px; color: red;">Terjadi kesalahan saat memuat peta: ' + error.message + '</div>';
    }
}); 