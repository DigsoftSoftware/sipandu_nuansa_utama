<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Pendatang Per Penanggung Jawab dan Kepala Lingkungan</title>
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

        .pj-header {
            background-color: #e6e6e6;
            font-weight: bold;
        }

        .kaling-header {
            background-color: #f2f2f2;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .footer-content {
            float: right;
            text-align: center;
            margin-right: 50px;
        }

        .tanggal {
            font-style: italic;
            text-align: center;
            margin-bottom: 10px;
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
            LAPORAN DETAIL PENDATANG<br>
            PER PENANGGUNG JAWAB DAN KEPALA LINGKUNGAN<br>
        </div>

        <div class="tanggal">Per tanggal: <?= $tanggal ?></div>

        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Pendatang</th>
                    <th width="30%">Tujuan</th>
                    <th width="15%">Tanggal Masuk</th>
                    <th width="15%">Tanggal Keluar</th>
                    <th width="15%">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $current_pj = null;
                $current_kaling = null;

                foreach($penghuni_data as $row): 
                    if ($current_pj !== $row->pj_id):
                        $current_pj = $row->pj_id;
                ?>
                <tr class="pj-header">
                    <td colspan="6">Penanggung Jawab: <?= $row->nama_pj ?></td>
                </tr>
                <?php 
                    endif;
                    if ($current_kaling !== $row->kaling_id):
                        $current_kaling = $row->kaling_id;
                ?>
                <tr class="kaling-header">
                    <td colspan="6">Kepala Lingkungan: <?= $row->nama_kaling ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="center"><?= $no++ ?></td>
                    <td><?= $row->nama_lengkap ?></td>
                    <td><?= $row->tujuan ?></td>
                    <td class="center"><?= date('d-m-Y', strtotime($row->tanggal_masuk)) ?></td>
                    <td class="center"><?= date('d-m-Y', strtotime($row->tanggal_keluar)) ?></td>
                    <td><?= $row->nama_pj ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="signature">
            <p>Jimbaran, <?php echo $tanggal; ?></p>
            <p>Yang membuat pernyataan,</p>
            <br><br><br>
            <p><u><?php echo $penghuni->nama_lengkap; ?></u></p> 
        </div>
    </div>
</body>
</html>