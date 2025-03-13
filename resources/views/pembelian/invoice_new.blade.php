<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Beli No.{{ tambah_nol_didepan($pembelian->id_pembelian, 7) }}</title>

    <style>
        .text-right {
            text-align: right;
        }

        .text-center {
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
            <td rowspan="6" width="60%">
                <img src="{{ asset($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br>
                {{ $setting->nama_perusahaan }}
                <br>
                {{ $setting->alamat }}
                <br>
                <b>Tenggat Bayar: {{ $pembelian->created_at->addDays(10)->setTime(23, 0, 0)->format('d-m-Y H:i:s') }}</b>
                <br>
            </td>
            <td>Tanggal</td>
            <td>: {{ $pembelian->created_at->format('d-m-Y H:i:s') }}</td>
        </tr>
        <tr>
            <td>Invoice No.</td>
            <td>: {{ tambah_nol_didepan($pembelian->id_pembelian, 7) }}</td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>: {{ $pembelian->supplier->nama_supplier }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $pembelian->supplier->alamat }}</td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td>: {{ $pembelian->supplier->telepon }}</td>
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
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->pembelian_detail as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->produk->kode_produk }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right">Rp. {{ format_uang($item->produk->harga_beli) }}</td>
                    <td class="text-right">{{ $item->jumlah }}</td>
                    <td class="text-right">Rp. {{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><b>Total Item: </b></td>
                <td class="text-right"><b>{{ $pembelian->total_item }}</b></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Subtotal: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($pembelian->total_harga) }}</b></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Diskon ({{ $pembelian->diskon }}%): </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($diskon) }}</b></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Total Bayar: </b></td>
                <td class="text-right"><b>Rp. {{ format_uang($pembelian->bayar) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td class="text-right">
                Dari:
                <br>
                <br>
                {{ $user->name }}
                <br>
                {{ $user->email }}
            </td>
        </tr>
    </table>
</body>

</html>
