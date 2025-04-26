<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Izin Tinggal</title>
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

        .title {
            text-align: center;
            margin: 20px 0 10px;
        }

        .title h2 {
            text-decoration: underline;
            font-size: 14pt;
            margin-bottom: 5px;
        }

        .title p {
            margin: 0;
            font-size: 12pt;
        }

        .content {
            text-align: justify;
            margin-top: 10px;
        }

        table.biodata {
            margin: 15px 0;
            width: 100%;
        }

        table.biodata td {
            padding: 4px 20px 4px 0;
            vertical-align: top;
        }

        .durasi {
            margin: 15px 0;
            font-weight: bold;
        }

        ol {
            margin: 15px 0;
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 10px;
            text-align: justify;
        }

        .signature {
            margin-top: 50px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            vertical-align: top;
        }

        .signature-right {
            text-align: right;
            margin-right: 50px;
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

    <div class="title">
        <h2>SURAT IZIN TINGGAL</h2>
        <p>Nomor: <?php echo $nomor_surat; ?></p>
    </div>

    <div class="content">
    <p>Yang bertanda tangan di bawah ini:</p>
        <table class="biodata">
            <tr>
                <td width="180px">Nama Lengkap</td>
                <td width="10">:</td>
                <td><?php echo $penghuni->nama_lengkap; ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?php echo $penghuni->nik; ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td><?php echo $penghuni->tempat_lahir . ', ' . date('d/m/Y', strtotime($penghuni->tanggal_lahir)); ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?php echo $penghuni->jenis_kelamin; ?></td>
            </tr>
            <tr>
                <td>Alamat Asal</td>
                <td>:</td>
                <td><?php echo $penghuni->alamat_asal; ?></td>
            </tr>
            <tr>
                <td>Alamat Sekarang</td>
                <td>:</td>
                <td><?php echo $penghuni->alamat_sekarang; ?></td>
            </tr>
            <tr>
                <td>Wilayah</td>
                <td>:</td>
                <td><?php echo $wilayah->wilayah; ?></td>
            </tr>
            <tr>
                <td>Tujuan</td>
                <td>:</td>
                <td><?php echo $penghuni->tujuan; ?></td>
            </tr>
        </table>

        <div class="durasi">
            Lama Tinggal:
            <?php
            $start = new DateTime($penghuni->tanggal_masuk);
            $end = new DateTime($penghuni->tanggal_keluar);
            $interval = $start->diff($end);
            echo ($interval->y ? $interval->y . ' Tahun ' : '') .
                ($interval->m ? $interval->m . ' Bulan ' : '') .
                ($interval->d ? $interval->d . ' Hari' : '');
            ?><br>
            (<?php echo date('d/m/Y', strtotime($penghuni->tanggal_masuk)) . ' s.d. ' . date('d/m/Y', strtotime($penghuni->tanggal_keluar)); ?>)
        </div>
        
        <p>
            Telah terdaftar sebagai penghuni di wilayah <?php echo $wilayah->wilayah; ?> dan diberikan izin tinggal sesuai dengan ketentuan yang berlaku. Surat izin tinggal ini berlaku selama yang bersangkutan masih berdomisili di alamat tersebut dan mematuhi segala peraturan lingkungan.
        </p>

    </div>

    <div class="signature">
        <div class="signature-right">
            <p>Jimbaran, <?php echo $tanggal; ?></p>
            <p>Kepala Lingkungan <?php echo $wilayah->wilayah; ?></p>
            <br><br><br>
            <p><u><?php echo $kaling->nama; ?></u></p>
            <p><i>MATERAI 10.000</i></p>
        </div>
    </div>
</body>

</html>