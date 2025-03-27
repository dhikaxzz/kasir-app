<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        @page { size: auto; margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .w-full { width: 100%; }
        .border { border: 1px solid black; border-collapse: collapse; }
        .border th, .border td { border: 1px solid black; padding: 8px; }
        .mt-2 { margin-top: 10px; }
        .mt-4 { margin-top: 20px; }
        .text-left { text-align: left; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="text-center bold uppercase">
        <p>STRUK TRANSAKSI</p>
        <p>#{{ $transaksi->kode_transaksi }}</p>
    </div>

    <p><span class="bold">Tanggal:</span> {{ $transaksi->tanggal }}</p>
    <p><span class="bold">Metode Pembayaran:</span> {{ ucfirst($transaksi->metode_pembayaran) }}</p>

    <table class="w-full border mt-2">
        <thead>
            <tr class="bold text-center">
                <th>Barang</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $detail)
                <tr>
                    <td class="text-left">{{ $detail->barang->nama_barang }}</td>
                    <td class="text-right">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 text-left">
        <p class="bold">Total Harga: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
        <p class="bold">Total Bayar: Rp{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
        <p class="bold">Kembalian: Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>
    </div>

    <p class="text-center mt-4 bold">Terima Kasih!</p>
</body>
</html>
