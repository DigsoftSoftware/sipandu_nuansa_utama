<?php $this->load->view('partials/header'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4>SURAT PERNYATAAN</h4>
                <p>Nomor: <?php echo date('dmY').'/SP/'.rand(100,999); ?></p>
            </div>

            <div class="mt-4">
                <p>Yang bertanda tangan di bawah ini:</p>
                
                <table class="ml-4 mb-4">
                    <tr>
                        <td width="150">Nama Lengkap</td>
                        <td width="20">:</td>
                        <td><?php echo $nama; ?></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td><?php echo $nik; ?></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tgl Lahir</td>
                        <td>:</td>
                        <td><?php echo $tempat_lahir.', '.date('d-m-Y', strtotime($tanggal_lahir)); ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?php echo $alamat; ?></td>
                    </tr>
                </table>

                <p>Dengan ini menyatakan bahwa:</p>
                <p class="ml-4"><?php echo nl2br($isi_pernyataan); ?></p>
                
                <p>Demikian surat pernyataan ini saya buat dengan sebenarnya dan penuh tanggung jawab.</p>

                <div class="row mt-5">
                    <div class="col-6"></div>
                    <div class="col-6 text-center">
                        <p><?php echo date('d F Y'); ?></p>
                        <p>Yang membuat pernyataan,</p>
                        <br><br><br>
                        <p><?php echo $nama; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button onclick="window.print()" class="btn btn-primary">Cetak Surat</button>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>