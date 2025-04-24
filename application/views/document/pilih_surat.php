<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>  

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Pilih Jenis Surat</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Penghuni</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($penghuni as $p): ?>
                        <tr>
                            <td><?= $p->nama_lengkap ?></td>
                            <td><?= $p->nik ?></td>
                            <td><?= $p->alamat_sekarang ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pilih Surat
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="<?= base_url('dashboard/surat/generate_surat_izin_tinggal/'.$p->id) ?>">Surat Izin Tinggal</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('dashboard/surat/generate_surat_pernyataan/'.$p->id) ?>">Surat Pernyataan</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>