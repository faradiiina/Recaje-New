// Mencegah papan ketikan (keyboard) muncul saat pengguna menyentuh area yang bukan input/textarea
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('touchstart', function(e) {
        if (e.target.tagName !== 'INPUT' && 
            e.target.tagName !== 'TEXTAREA' && 
            !e.target.isContentEditable) {
            e.preventDefault();
        }
    }, { passive: false });

    // Menambahkan atribut readonly ke elemen date input untuk mencegah keyboard muncul
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.setAttribute('readonly', 'readonly');
        // Tambahkan event listener untuk menghapus readonly saat diklik
        input.addEventListener('mousedown', function() {
            this.removeAttribute('readonly');
        });
        input.addEventListener('blur', function() {
            this.setAttribute('readonly', 'readonly');
        });
    });
}); 