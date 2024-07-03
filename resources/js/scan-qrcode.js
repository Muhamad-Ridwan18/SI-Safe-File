import Html5Qrcode from 'html5-qrcode';

document.addEventListener('DOMContentLoaded', function() {
    const qrCodeScanner = new Html5Qrcode('qr-code-reader');

    // Fungsi untuk melakukan scan QR code dari kamera
    document.getElementById('start-scanner').addEventListener('click', async function() {
        try {
            await qrCodeScanner.start();
            qrCodeScanner.scan(result => {
                document.getElementById('access-code').value = result;
                qrCodeScanner.stop();
            });
        } catch (err) {
            console.error('Error during QR code scan:', err);
            alert('Error during QR code scan: ' + err);
        }
    });

    // Event listener untuk input file QR code
    document.getElementById('qr_code').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Menampilkan nama file yang dipilih (opsional)
            console.log('Selected file:', file.name);

            // Baca isi QR code dari file yang dipilih
            const reader = new FileReader();
            reader.onload = function(e) {
                // Setelah file QR code berhasil dibaca
                const qrContent = e.target.result; // Ini bisa berupa base64 atau blob URL tergantung dari cara pembacaan QR code
                
                // Menampilkan preview gambar
                const previewImg = document.getElementById('qr-code-preview');
                previewImg.style.display = 'block';
                previewImg.src = qrContent;

                // Memasukkan nilai QR code ke dalam input access_code
                document.getElementById('access-code').value = qrContent;

                // Memberhentikan pemindaian QR code (jika sedang berjalan)
                qrCodeScanner.stop();
            };
            reader.readAsDataURL(file);
        }
    });

    // Fungsi untuk menghentikan pemindaian QR code
    document.getElementById('stop-scanner').addEventListener('click', function() {
        qrCodeScanner.stop();
    });
});
