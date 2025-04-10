<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #ffffff;
        color: #000000;
        padding: 5mm;
        font-size: 3.5mm;
    }

    h2 {
        font-size: 5mm;
        margin-bottom: 3mm;
        color: #1b5e20;
        border-bottom: 0.5mm solid #81c784;
        padding-bottom: 1mm;
    }

    h4 {
        font-size: 4mm;
        margin-top: 4mm;
        margin-bottom: 2mm;
        color: #2e7d32;
        border-left: 1.5mm solid #81c784;
        padding-left: 2mm;
    }

    .section {
        padding: 3mm;
        border: 0.3mm solid #c8e6c9;
        border-left: 1.5mm solid #66bb6a;
        margin-bottom: 4mm;
        border-radius: 2mm;
    }

    ul {
        padding-left: 0;
        margin-top: 2mm;
        list-style-type: none;
    }

    ul li {
        padding: 1.5mm 0;
        border-bottom: 0.3mm dotted #a5d6a7;
        font-size: 3.5mm;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 3.5mm;
        margin-top: 2mm;
    }

    thead {
        background-color: #a5d6a7;
        color: #1b5e20;
    }

    th, td {
        border: 0.2mm solid #c8e6c9;
        padding: 1.5mm 2mm;
        text-align: left;
    }

    tbody tr:nth-child(even) {
        background-color: #f1f8e9;
    }

    hr {
        border: none;
        border-top: 0.3mm dashed #81c784;
        margin: 6mm 0;
    }

    .emoji-title {
        margin-right: 1mm;
    }
</style>


<h2>Laporan Transaksi</h2>

<div class="section">
    <h4><span class="emoji-title">📊</span>Statistik</h4>
    <ul>
        <li><strong>Total Transaksi:</strong> {{ number_format($overview['total_transaksi']) }}</li>
        <li><strong>Total Pendapatan:</strong> Rp {{ number_format($overview['total_pendapatan']) }}</li>
        <li><strong>Barang Tersedia:</strong> {{ $overview['barang_tersedia'] }}</li>
        <li><strong>Total Member:</strong> {{ $overview['total_member'] }}</li>
        <li><strong>Stok Hampir Habis:</strong> {{ $overview['stok_hampir_habis'] }}</li>
        <li><strong>Rata-rata Transaksi per Hari:</strong> {{ $overview['rata_rata_harian']() }}</li>
    </ul>
</div>

<div class="section">
    <h4><span class="emoji-title">🏆</span>Top 5 Barang Paling Banyak Dibeli</h4>
    <table>
        <thead>
            <tr><th>Nama Barang</th><th>Jumlah Dibeli</th></tr>
        </thead>
        <tbody>
            @foreach($topBarang as $barang)
                <tr>
                    <td>{{ $barang['nama'] }}</td>
                    <td>{{ $barang['jumlah'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <h4><span class="emoji-title">👥</span>Top 5 Member dengan Transaksi Terbanyak</h4>
    <table>
        <thead>
            <tr><th>Nama Member</th><th>Jumlah Transaksi</th></tr>
        </thead>
        <tbody>
            @foreach($topPelanggan as $member)
                <tr>
                    <td>{{ $member['nama'] }}</td>
                    <td>{{ $member['jumlah'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr>

<div class="section">
    <h4><span class="emoji-title">🧾</span>Detail Transaksi</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $trx->tanggal }}</td>
                    <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
