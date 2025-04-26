<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Jumlah Pendatang per Penanggung Jawab</title>
    <style>
        @page {
            size: legal;
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
        }

        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 100px;
            text-align: center;
        }

        .logo-cell img {
            width: 150px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .header-text {
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
            text-align: left;
            font-size: 11pt;
        }

        .data th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11pt;
        }

        .total-row {
            font-weight: bold;
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
                    <th width="40%">Nama Penanggung Jawab</th>
                    <th width="25%">Jumlah Pendatang</th>
                    <th width="30%">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($pendatang_per_pj as $row): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row->nama_pj; ?></td>
                    <td><?php echo $row->jumlah_pendatang; ?> orang</td>
                    <td><?php echo number_format(($row->jumlah_pendatang / $total_pendatang * 100), 2); ?>%</td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td><?php echo $total_pendatang; ?> orang</td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>

     
    </div>
</body>
</html>