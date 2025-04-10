<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5fdf6;
        color: #2c3e50;
        padding: 2rem;
    }

    h2 {
        color: #1e824c;
        font-size: 2rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #1e824c;
        padding-bottom: 0.5rem;
    }

    h4 {
        margin-top: 2rem;
        color: #27ae60;
        font-size: 1.3rem;
        border-left: 5px solid #2ecc71;
        padding-left: 10px;
    }

    .section {
        background-color: #ffffff;
        border: 1px solid #dff0d8;
        border-left: 5px solid #2ecc71;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 6px rgba(0, 128, 0, 0.1);
    }

    ul {
        list-style-type: none;
        padding-left: 0;
        margin-top: 1rem;
    }

    ul li {
        padding: 0.5rem 0;
        border-bottom: 1px dashed #c8e6c9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: #fff;
        border-radius: 6px;
        overflow: hidden;
    }

    table thead {
        background-color: #2ecc71;
        color: white;
    }

    table th, table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0f2e9;
        text-align: left;
    }

    table tbody tr:hover {
        background-color: #f1fdf3;
    }

    hr {
        border: none;
        border-top: 2px dashed #2ecc71;
        margin: 3rem 0;
    }

    .emoji-title {
        margin-right: 5px;
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
