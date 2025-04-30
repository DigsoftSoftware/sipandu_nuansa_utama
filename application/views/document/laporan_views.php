<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Laporan</h5>
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
            <div class="row">
                <div class="col-md-12">
                    <form id="laporanForm">
                        <div class="form-group mb-4">
                            <label for="jenisLaporan" class="form-label">Pilih Jenis Laporan</label>
                            <select class="form-select" id="jenisLaporan" name="jenisLaporan">
                                <option value="">-- Pilih Jenis Laporan --</option>
                                <option value="reportpj">Laporan Jumlah Semua Pendatang</option>
                                <option value="reportpj">Laporan Pendatang Per PJ</option>
                                <option value="reportpendatang">Laporan Tujuan Pendatang</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="btnCetakLaporan">
                                <i class="ti ti-printer me-1"></i>
                                Cetak Laporan
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
    const jenisLaporanSelect = document.getElementById('jenisLaporan');
    const btnCetakLaporan = document.getElementById('btnCetakLaporan');
    
    function checkForm() {
        btnCetakLaporan.disabled = !jenisLaporanSelect.value;
    }

    jenisLaporanSelect.addEventListener('change', checkForm);
    checkForm();
    
    btnCetakLaporan.addEventListener('click', function() {
        const jenisLaporan = jenisLaporanSelect.value;
        
        if (!jenisLaporan) return;

        let title = jenisLaporan === 'reportpj' ? 'Laporan Pendatang Per PJ' : 'Laporan Tujuan Pendatang';
        let url = '<?= base_url('dashboard/report/') ?>' + jenisLaporan;
        
        Swal.fire({
            title: 'Cetak ' + title,
            text: 'Apakah anda yakin Mencetak ' + title + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Cetak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(url, '_blank');
            }
        });
    });
});</script>