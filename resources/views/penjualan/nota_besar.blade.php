<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Besar No.{{ tambah_nol_didepan($transaksi->id_penjualan, 7) }}</title>

    <style>
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }
        table td {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table.data {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ asset($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br>
                {{ $setting->alamat }}
                <br>
                <br>
            </td>
            <td>Tanggal</td>
            <td>: {{ $transaksi->created_at->format('d-m-Y H:i:s') }}</td>
        </tr>
        <tr>
            <td>Member</td>
            <td>: {{ $transaksi->member->nama_member ?? 'Tanpa Member' }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->penjualan_detail as $key => $item)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $item->produk->kode_produk }}</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td class="text-right">Rp. {{ format_uang($item->harga_jual) }}</td>
                <td class="text-right">{{ $item->jumlah }}</td>
                <td class="text-right">{{ $item->diskon }} %</td>
                <td class="text-right">Rp. {{ format_uang($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><b>Total Harga: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($transaksi->total_harga) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Diskon: </b></td>
                <td class="text-right"><b>{{ $transaksi->diskon }} %</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Bayar: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($transaksi->bayar) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Diterima: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($transaksi->diterima) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Kembalian: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($transaksi->diterima - $transaksi->bayar) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td><b>Terima Kasih Telah Berbelanja Dengan Kami!</b></td>
            <td class="text-center">
                Kasir
                <br>
                <br>
                {{ $kasir->name }}
            </td>
        </tr>
    </table>
</body>

</html>
