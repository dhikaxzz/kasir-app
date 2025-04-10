<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <style>
        @page { size: auto; margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 270px;
            margin: 0 auto;
            padding: 10px;
            color: #333;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }

        .brand {
            font-size: 16px;
            font-weight: bold;
            color: #00B894;
        }

        .kode-transaksi {
            font-size: 11px;
            color: #888;
        }

        .detail-item {
            margin-bottom: 6px;
        }

        .footer-total {
            margin-top: 10px;
        }

        .footer-total p {
            display: flex;
            justify-content: space-between;
        }

        .thanks {
            margin-top: 10px;
            font-size: 12px;
            color: #00B894;
        }

        .highlight {
            color: #00B894;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="text-center">
        <div class="brand">FastKas Dhika</div>
        <div class="kode-transaksi">#{{ $transaksi->kode_transaksi }}</div>
    </div>

    <div class="line"></div>

    <p><span class="bold">Tanggal</span>: {{ $transaksi->tanggal }}</p>
    <p><span class="bold">Pelanggan</span>: {{ $transaksi->pelanggan->nama ?? '-' }}</p>
    <p><span class="bold">Metode</span>: {{ ucfirst($transaksi->metode_pembayaran) }}</p>

    <div class="line"></div>

    @foreach($transaksi->detailTransaksi as $detail)
        <div class="detail-item">
            <div class="text-left bold">{{ $detail->barang->nama_barang }}</div>
            <div class="text-left">
                {{ $detail->jumlah }} x Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
            </div>
            <div class="text-right highlight">
                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="footer-total">
        <p><span class="bold">Total</span> <span class="bold">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></p>
        <p><span>Total Bayar</span> <span>Rp{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></p>
        <p><span>Kembalian</span> <span>Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span></p>
    </div>

    <div class="line"></div>

    <p class="text-center thanks">Terima Kasih telah berbelanja di FastKas!</p>

    <div class="text-center" style="margin-top: 10px;">
        <img src="data:image/svg+xml;base64,{{ $qreCode }}" width="100" alt="QR Code">
    </div>
        
</body>
</html>
