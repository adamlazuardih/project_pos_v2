<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil No.{{ tambah_nol_didepan($transaksi->id_penjualan, 7)}}</title>

    <?php
    $style = '<style>
            * {
                font-family: "consolas", sans-serif;
            }

            p {
                display: block;
                margin: 3;
                font-size: 10pt;
            }

            table td {
                font-size: 9pt;
            }

            .text-center {
                text-align: center;
            }

            .text-end {
                text-align: right;
            }

            @media print {
                @page{
                    margin: 0;
                    size: 75mm

        ';
    ?>

    <?php
    $style .= !empty($_COOKIE['innerHeight']) ? $_COOKIE['innerHeight'] . 'mm; }' : '}';
    ?>

    <?php
    $style .= '
                html, body{
                        width: 70mm;
                    }
                .btn-print{
                    display: none;
                }
            }
        </style>';
    ?>

    {!! $style !!}
</head>

<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ strtoupper($transaksi->created_at->format('d-m-Y H:i:s')) }}</p>
        <p style="float: right">{{ strtoupper($kasir->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both"></div>
    <p style="margin: 0;">Member: {{ $transaksi->member->nama_member ?? 'Tanpa Member' }}</p>
    <p style="margin: 0;">No: {{ tambah_nol_didepan($transaksi->id_penjualan, 7) }}</p>
    <p style="margin: 0;" class="text-center">===================================</p>

    <br>
    <table width="100%" style="border: 0;">
        @foreach ($transaksi->penjualan_detail as $item)
            <tr>
                <td colspan="3">{{ $item->produk->nama_produk }}</td>
            </tr>
            <tr>
                <td>{{ $item->jumlah }} x {{ format_uang($item->harga_jual) }}</td>
                <td></td>
                <td class="text-end">Rp. {{ format_uang($item->jumlah * $item->harga_jual) }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga: </td>
            <td class="text-end">Rp. {{ format_uang($transaksi->total_harga) }}</td>
        </tr>
        <tr>
            <td>Total Item: </td>
            <td class="text-end">{{ $transaksi->total_item }}</td>
        </tr>
        <tr>
            <td>Diskon: </td>
            <td class="text-end">{{ $transaksi->diskon }} %</td>
        </tr>
        <tr>
            <td>Total Bayar: </td>
            <td class="text-end">Rp. {{ format_uang($transaksi->bayar) }}</td>
        </tr>
        <tr>
            <td>Diterima: </td>
            <td class="text-end">Rp. {{ format_uang($transaksi->diterima) }}</td>
        </tr>
        <tr>
            <td>Kembalian: </td>
            <td class="text-end">Rp. {{ format_uang($transaksi->diterima - $transaksi->bayar) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">TERIMA KASIH</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
        document.cookie = "innerHeight=" + ((height + 50) * 0.264583);

    </script>
</body>

</html>
