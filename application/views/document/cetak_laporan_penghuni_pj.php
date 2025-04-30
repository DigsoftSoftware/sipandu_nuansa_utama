<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Jumlah Pendatang per Penanggung Jawab</title>
    <style>

        @page {
            size: legal landscape;
            margin: 2.5cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .kop-table {
            width: 100%;
            border-bottom: 3px solid black;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-spacing: 0;
            table-layout: fixed;
        }

        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 20%;
            text-align: right;
            padding-right: 20px;
        }

        .logo-cell img {
            width: 180px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .header-text {
            width: 50%;
            text-align: center;
            padding: 8px 0;
        }

        .header-text h2 {
            font-size: 14pt;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header-text h3 {
            font-size: 13pt;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header-text p {
            margin: 1px 0;
            font-size: 10pt;
        }

        .content {
            margin-top: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data th, .data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11pt;
        }

        .data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .data td.center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11pt;
        }

        .total-row {
            font-weight: bold;
        }

        .data td ul {
            margin: 0;
            padding-left: 20px;
            list-style-type: decimal;
        }

        .data td li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <img src="<?= base_url('assets/images/logos/icon.png') ?>" alt="Logo">
            </td>
            <td class="header-text">
                <h2>IKATAN WARGA NUANSA UTAMA</h2>
                <h3>KECAMATAN KUTA SELATAN</h3>
                <h3>KELURAHAN JIMBARAN</h3>
                <p>Jl. Nuansa Utama, Jimbaran, Kec. Kuta Selatan, Kabupaten Badung, Bali 80361</p>
                <p>Telp. 0361-2334376 | Website: <a href="http://www.sipandu.nuansautama.my.id">www.sipandu.nuansautama.my.id</a></p>
            </td>
        </tr>
    </table>

    <div class="content">
        <div class="title">
            LAPORAN JUMLAH PENDATANG PER PENANGGUNG JAWAB<br>
            Periode: <?php echo date('d F Y'); ?>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Penanggung Jawab</th>
                    <th width="15%">Jumlah Pendatang</th>
                    <th width="50%">Nama Pendatang</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($pendatang_per_pj as $row): ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td class="center"><?php echo $row->nama_pj; ?></td>
                    <td class="center"><?php echo $row->jumlah_pendatang; ?> orang</td>
                    <td>
                        <ul>
                            <?php 
                            $nama_pendatang_array = explode(',', $row->nama_pendatang);
                            foreach($nama_pendatang_array as $nama) {
                                echo '<li>' . trim($nama) . '</li>';
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>