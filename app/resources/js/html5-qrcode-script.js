console.log("Scan QR Script Loaded");

document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        scannedResult: '',
        qrCodeReader: null,
        isModalOpen: false,

        initScanner() {
            this.qrCodeReader = new Html5Qrcode("qr-reader");

            this.qrCodeReader.start(
                { facingMode: "environment" }, // Kamera belakang
                { fps: 10, qrbox: 250 },
                async (decodedText) => {
                    this.scannedResult = decodedText;
                    console.log('Kode Barang Terbaca:', decodedText);

                    // Matikan scanner setelah scan berhasil
                    this.destroyScanner();

                    // Tutup modal dulu biar bersih
                    this.isModalOpen = false;

                    // Tunggu sedikit sebelum redirect biar smooth
                    setTimeout(() => {
                        window.location.href = `/admin/barangs?tableSearch=${decodedText}`;
                    }, 300);
                },
                (error) => {
                    console.error("QR Scan Error:", error);
                }
            );
        },

        destroyScanner() {
            if (this.qrCodeReader) {
                this.qrCodeReader.stop().then(() => {
                    console.log("QR Scanner Stopped");
                }).catch(err => console.error("Error stopping QR scanner:", err));
                this.qrCodeReader = null;
            }
        }
    }));
});

// Scanner otomatis mati ketika modal ditutup
document.addEventListener('close-modal', () => {
    const qrScanner = Alpine.$data(document.querySelector('[x-data="qrScanner"]'), 'qrScanner');
    if (qrScanner) {
        qrScanner.destroyScanner();
    }
});
