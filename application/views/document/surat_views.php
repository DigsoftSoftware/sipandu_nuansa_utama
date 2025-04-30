<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Surat - Surat</h5>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Silahkan Memilih Data Penghuni yang sudah terverifikasi <strong>Kepala Lingkungan</strong></li>
                            <li>Cetak Dalam Format Kertas <strong>F4 atau legal</strong></li>
                            <li>Scale Format Dokumen di <strong>100%</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="suratForm">
                        <div class="form-group mb-4">
                            <label for="penghuni" class="form-label">Pilih Data Penghuni</label>
                            <select class="form-select" id="penghuni" name="penghuni">
                                <option value="">-- Pilih Data Penghuni --</option>
                                <?php foreach($penghuni as $p): ?>
                                    <option value="<?= $p->uuid ?>" data-nama="<?= $p->nama_lengkap ?>">
                                        <?= $p->nama_lengkap ?> (NIK: <?= $p->nik ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jenisSurat" class="form-label">Pilih Jenis Surat</label>
                            <select class="form-select" id="jenisSurat" name="jenisSurat">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="izin_tinggal">Surat Izin Tinggal</option>
                                <option value="pernyataan">Surat Pernyataan</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="btnCetakSurat">
                                <i class="ti ti-printer me-1"></i>
                                Cetak Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const penghuniSelect = document.getElementById('penghuni');
    const jenisSuratSelect = document.getElementById('jenisSurat');
    const btnCetakSurat = document.getElementById('btnCetakSurat');
    
    function checkForm() {
        btnCetakSurat.disabled = !penghuniSelect.value || !jenisSuratSelect.value;
    }

    penghuniSelect.addEventListener('change', checkForm);
    jenisSuratSelect.addEventListener('change', checkForm);
    
    btnCetakSurat.addEventListener('click', function() {
        const uuid = penghuniSelect.value;
        const jenisSurat = jenisSuratSelect.value;
        const namaPenghuni = penghuniSelect.options[penghuniSelect.selectedIndex]?.dataset?.nama;
        
        if (!jenisSurat || !uuid) return;

        let title = jenisSurat === 'izin_tinggal' ? 'Surat Izin Tinggal' : 'Surat Pernyataan';
        let url = jenisSurat === 'izin_tinggal'
            ? '<?= base_url('dashboard/surat/SIT/') ?>' + uuid
            : '<?= base_url('dashboard/surat/SP/') ?>' + uuid;
        
        Swal.fire({
            title: 'Cetak ' + title,
            text: 'Apakah anda ingin Mencetak ' + title + ' untuk ' + namaPenghuni + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',  
            confirmButtonText: 'Ya, Cetak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(url, '_blank');
            }
        });
    });
});
</script>