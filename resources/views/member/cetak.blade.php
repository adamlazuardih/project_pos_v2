<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kartu Member</title>

    <style>
        .box {
            position: relative;
        }

        .box img {
            width: 85.60mm;
        }

        .logo {
            position: absolute;
            top: 3pt;
            right: 0pt;
            font-size: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .logo p {
            text-align: right;
            margin-right: 16pt;
        }

        .logo img {
            position: absolute;
            margin-top: -5pt;
            width: 40px;
            height: 40px;
            right: 16pt;
        }

        .nama {
            position: absolute;
            top: 100pt;
            right: 16pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .telepon {
            position: absolute;
            margin-top: 120pt;
            right: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #111111 !important;
        }

        .barcode {
            position: absolute;
            top: 105pt;
            left: .860rem;
            border: 1px solid #fff;
            padding: .5px;
            background: #fff;
        }

        .barcode img {
            width: 40px;
            height: 40px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }
    </style>

</head>

<body>
    <section style="border: 1px solid #fff">
        <table width="100%">
            @foreach ($datamember as $key => $member)
                <tr>
                    @foreach ($member as $item)
                        <td class="text-center">
                            <div class="box">
                                <img src="{{ public_path('images/member.png') }}" alt="card">
                                <div class="logo">
                                    <p>{{ config('app.name') }}</p>
                                    <img src="{{ public_path('images/logo.png') }}" alt="logo">
                                </div>
                                <div class="nama">{{ $item->nama_member }}</div>
                                <div class="telepon">{{ $item->telepon }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$item->kode_member", 'QRCODE') }}"
                                        alt="qrcode">
                                </div>
                            </div>
                        </td>
                        @if (count($datamember) == 1)
                            <td class="text-center" style="width: 50%;"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>

</html>
