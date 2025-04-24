<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pernyataan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin: 20px 0;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        table.biodata {
            margin: 20px 0;
        }
        table.biodata td {
            padding: 5px 20px 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT PERNYATAAN</h2>
        <p>Nomor: <?php echo $nomor_surat; ?></p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        
        <table class="biodata">
            <tr>
                <td width="150">Nama Lengkap</td>
                <td width="20">:</td>
                <td><?php echo $penghuni->nama; ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?php echo $penghuni->nik; ?></td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>:</td>
                <td><?php echo $penghuni->tempat_lahir.', '.$penghuni->tanggal_lahir; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?php echo $penghuni->alamat; ?></td>
            </tr>
            <tr>
                <td>Wilayah</td>
                <td>:</td>
                <td><?php echo $wilayah->wilayah; ?></td>
            </tr>
        </table>

        <p>Dengan ini menyatakan dengan sebenarnya bahwa saya:</p>
        <ol>
            <li>Akan mematuhi segala peraturan yang berlaku di wilayah <?php echo $wilayah->wilayah; ?></li>
            <li>Akan menjaga ketertiban dan keamanan lingkungan</li>
            <li>Bersedia melaporkan setiap perubahan data diri atau kepindahan tempat tinggal</li>
            <li>Akan berpartisipasi aktif dalam kegiatan kemasyarakatan</li>
        </ol>

        <p>Demikian surat pernyataan ini saya buat dengan sebenarnya dan penuh tanggung jawab.</p>
    </div>

    <div class="signature">
        <p><?php echo $tanggal; ?></p>
        <p>Yang membuat pernyataan,</p>
        <br><br><br>
        <p><?php echo $penghuni->nama; ?></p>
    </div>
</body>
</html>