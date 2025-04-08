<div x-data="qrScanner" x-init="initScanner()" class="p-4">
    <div id="qr-reader" class="border rounded-lg w-full"></div>

    <p class="mt-2 text-lg font-semibold text-center text-gray-700">
        Kode Barang: <span x-text="scannedResult"></span>
    </p>
</div>