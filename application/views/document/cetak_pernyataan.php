<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pernyataan</title>
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
            margin-top: 8px;
        }

        table.biodata {
            margin: 12px 0;
            width: 100%;
        }

        table.biodata td {
            padding: 3px 15px 3px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .durasi {
            margin: 12px 0;
            font-weight: bold;
        }

        ol {
            margin: 12px 0;
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 8px;
            text-align: justify;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
            padding-right: 50px;
        }

        .signature p {
            margin: 3px 0;
        }

        .photo-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .photo-table td {
            width: 50%;
            padding: 10px;
            text-align: center;
            vertical-align: top;
        }

        .photo-table img {
            max-width: 300px;
            height: auto;
            border: 1px solid #000;
        }

        .photo-label {
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }

        .foto-container {
            margin-top: 30px;
            width: 100%;
        }

        .foto-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .foto-item {
            text-align: center;
            width: 45%;
        }

        .foto-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border: 1px solid #000;
        }

        .foto-label {
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
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
        <h2>SURAT PERNYATAAN</h2>
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

        <p>Dengan ini saya menyatakan hal-hal sebagai berikut:</p>
        <ol>
            <li>Bahwa saya akan mematuhi seluruh peraturan dan ketentuan yang berlaku di wilayah <?php echo $wilayah->wilayah; ?>.</li>
            <li>Bahwa saya akan turut menjaga ketertiban, keamanan, dan kenyamanan lingkungan sekitar.</li>
            <li>Bahwa saya bersedia melaporkan kepada pihak berwenang apabila terdapat perubahan data diri atau kepindahan tempat tinggal.</li>
            <li>Bahwa seluruh data yang saya sampaikan dalam surat ini adalah benar dan dapat dipertanggungjawabkan.</li>
        </ol>

        <p>Demikian surat pernyataan ini saya buat dengan sesungguhnya, dalam keadaan sadar, tanpa adanya tekanan atau paksaan dari pihak manapun, untuk digunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
            <p>Jimbaran, <?php echo $tanggal; ?></p>
            <p>Yang membuat pernyataan,</p>
            <br><br><br>
            <p><u><?php echo $penghuni->nama_lengkap; ?></u></p> 
        </div>

    <div class="page-break">
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
            <h2>LAMPIRAN DOKUMEN</h2>
            <p>Nomor: <?php echo $nomor_surat; ?></p>
        </div>

        <table class="photo-table">
            <tr>
                <td>
                    <img src="<?= base_url('uploads/' . $penghuni->foto_ktp) ?>" alt="Foto KTP">
                    <div class="photo-label">FOTO KTP</div>
                </td>
                <td>
                    <img src="<?= base_url('uploads/' . $penghuni->foto_profil) ?>" alt="Foto Penghuni">
                    <div class="photo-label">FOTO PENGHUNI</div>
                </td>
            </tr>
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