<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuratController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Dompdf_lib');
        $this->load->library('Pusher_lib');
        $this->load->model('SuratModel', 'surat');
        $this->load->model('PenghuniModel', 'penghuni');
        $this->load->model('WilayahModel', 'wilayah');
        $this->load->model('KalingModel', 'kaling');
        $this->load->model('PenanggungJawabModel', 'pj');
        $this->load->library('session');
    }

    // Fungsi untuk menampilkan halaman utama pilihan surat
    public function index()
    {
        $this->check_role(['Penanggung Jawab', 'Admin', 'Kepala Lingkungan']);
        $user_role = $this->session->userdata('role');

        if ($user_role === 'Penanggung Jawab') {
            $pj_id = $this->session->userdata('pj_id');
            if (!$this->pj->is_profile_complete($pj_id)) { 
                redirect('dashboard/pj/validation');
                return;
            }
        }

        $data['title'] = 'Data Surat | SIPANDU NUANSA UTAMA';
        $this->load->view('document/surat/pilihan_surat_views', $data);
    }

    // Fungsi untuk menampilkan halaman surat pengantar di pendatang
    public function surat_pendatang()
    {
        $data['title'] = 'Surat Pendatang | SIPANDU NUANSA UTAMA';
        $user_role = $this->session->userdata('role');
        
        if ($user_role === 'Penanggung Jawab') {
            $pj_id = $this->session->userdata('pj_id');
            if (!$this->pj->is_profile_complete($pj_id)) {
                redirect('dashboard/pj/validation');
                return;
            }
            $data['list_keperluan'] = $this->surat->getKeperluan($pj_id);
            $data['penghuni'] = $this->penghuni->getByPenanggungJawab($pj_id);
            $data['surat_menunggu'] = $this->surat->getSuratMenungguVerifikasiByPJ($pj_id);
            $data['surat_terverifikasi'] = $this->surat->getSuratTerverifikasiByPJ($pj_id);
            $this->load->view('document/surat/surat_pendatang_pj', $data);
        } else {
            $data['pj'] = $this->pj->getAllWithWilayah();
            $data['list_keperluan'] = $this->surat->getKeperluan();
            $data['penghuni'] = $this->penghuni->getAllTerverifikasi();
            $data['surat_menunggu'] = $this->surat->getSuratMenungguVerifikasi();
            $data['surat_terverifikasi'] = $this->surat->getSuratTerverifikasi();
            $this->load->view('document/surat/surat_pendatang_admin', $data);
        }
    }

    // Fungsi untuk menampilkan halaman surat anggota keluarga
    public function surat_anggota_keluarga()
    {
        $this->load->model('AnggotaKeluargaModel', 'anggota');
        $data['title'] = 'Surat Anggota Keluarga | SIPANDU NUANSA UTAMA';
        $user_role = $this->session->userdata('role');

        if ($user_role === 'Penanggung Jawab') {
            $pj_id = $this->session->userdata('pj_id');
            if (!$this->pj->is_profile_complete($pj_id)) {
                redirect('dashboard/pj/validation');
                return;
            }
            $data['anggota'] = $this->anggota->getByPenanggungJawab($pj_id);
            $data['surat_menunggu'] = $this->surat->getSuratMenungguVerifikasiAnggotaByPJ($pj_id);
            $data['surat_terverifikasi'] = $this->surat->getSuratTerverifikasiAnggotaByPJ($pj_id);
            $this->load->view('document/surat/surat_anggota_pj', $data);
        } else {
            $data['anggota'] = $this->anggota->getAllWithPJ();
            $data['pj'] = $this->pj->getAll();
            $data['surat_terverifikasi'] = $this->surat->getSuratTerverifikasiAnggota();
            $data['surat_menunggu'] = $this->surat->getSuratMenungguVerifikasiAnggota();
            $this->load->view('document/surat/surat_anggota_admin', $data);
        }
    }

    // Fungsi untuk mencetak surat pengantar anggota keluarga oleh admin pakai no surat di database
    public function cetak_pengantar_pendatang($penghuni_uuid)
    {
        $this->load->model('PenghuniModel', 'penghuni');
        $this->load->model('PenanggungJawabModel', 'pj');
        $this->load->model('WilayahModel', 'wilayah');
        $this->load->model('KalingModel', 'kaling');
        $this->load->model('SuratModel', 'surat');
        $this->load->library('Dompdf_lib');
        $this->load->library('session');

        $penghuni = $this->penghuni->getByUuid($penghuni_uuid);
        if (!$penghuni) {
            show_error('Data pendatang tidak ditemukan');
            return;
        }

        $pj = $this->pj->getById($penghuni->penanggung_jawab_id);
        if (!$pj) {
            show_error('Penanggung Jawab tidak ditemukan');
            return;
        }

       $surat = $this->surat->db
        ->where('penghuni_id', $penghuni->id)
        ->where('status_proses', 'Diterima')
        ->order_by('tanggal_verifikasi', 'DESC')
        ->get('surat')
        ->row();

        if (!$surat) {
            $angka_acak = random_int(100, 9999);
            $nomor_surat = $angka_acak . '/SPG/' . date('Y');
            
            $data_surat = [
                'penghuni_id' => $penghuni->id,
                'pj_id' => $penghuni->penanggung_jawab_id,
                'status_proses' => 'Diterima',
                'tanggal_pengajuan' => date('Y-m-d H:i:s'),
                'tanggal_verifikasi' => date('Y-m-d H:i:s'),
                'uuid' => $this->surat->generate_uuid(),
                'no_surat' => $nomor_surat
            ];
            
            $this->surat->insert($data_surat);
            
            $surat = $this->surat->db
                ->where('penghuni_id', $penghuni->id)
                ->where('status_proses', 'Diterima')
                ->order_by('tanggal_verifikasi', 'DESC')
                ->get('surat')
                ->row();
        }

        $wilayah = $this->wilayah->getById($penghuni->wilayah_id);
        $kaling = $this->kaling->getByWilayahId($penghuni->wilayah_id);

        $data = [
            'nomor_surat' => $surat->no_surat,  
            'penghuni' => (object)[
                'nama_lengkap' => $penghuni->nama_lengkap,
                'tempat_lahir' => $penghuni->tempat_lahir,
                'tanggal_lahir' => $penghuni->tanggal_lahir,
                'jenis_kelamin' => $penghuni->jenis_kelamin,
                'alamat_asal' => $penghuni->alamat_asal,
                'kecamatan_asal' => $penghuni->kecamatan_asal ?? '',
                'kabupaten_asal' => $penghuni->kabupaten_asal ?? '',
                'provinsi_asal' => $penghuni->provinsi_asal ?? '',
                'alamat_sekarang' => $penghuni->alamat_sekarang,
            ],
            'wilayah' => (object)[ 'wilayah' => $wilayah ? $wilayah->wilayah : '-' ],
            'kaling' => (object)[ 'nama' => $kaling ? $kaling->nama : '-' ],
            'tanggal' => date('d F Y', strtotime($surat->tanggal_verifikasi))
        ];

        $html = $this->load->view('document/surat/cetak_pengantar_pendatang', $data, true);
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->setPaper('legal', 'portrait');
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream('SPG_NUANSA_UTAMA_' . $penghuni->nama_lengkap . '.pdf', false);
    }


    public function cetak_pengantar_anggota($anggota_uuid)
    {
        $this->load->model('AnggotaKeluargaModel', 'anggota');
        $this->load->model('PenanggungJawabModel', 'pj');
        $this->load->model('WilayahModel', 'wilayah');
        $this->load->model('KalingModel', 'kaling');
        $this->load->library('Dompdf_lib');
        $this->load->library('session');

        $anggota = $this->anggota->getByUuid($anggota_uuid);
        if (!$anggota) {
            show_error('Anggota keluarga tidak ditemukan');
            return;
        }

        // Ambil data penanggung jawab
        $pj = $this->pj->getById($anggota->penanggung_jawab_id);
        if (!$pj) {
            show_error('Penanggung Jawab tidak ditemukan');
            return;
        }

        $surat = $this->surat->db
            ->where('anggota_keluarga_id', $anggota->id)
            ->where('status_proses', 'Diterima')
            ->order_by('tanggal_verifikasi', 'DESC')
            ->get('surat')
            ->row();

        if (!$surat) {
            $angka_acak = random_int(100, 9999);
            $nomor_surat = $angka_acak . '/SPG/' . date('Y');
            
            $data_surat = [
                'anggota_keluarga_id' => $anggota->id,  
                'pj_id' => $anggota->penanggung_jawab_id,
                'status_proses' => 'Diterima',
                'tanggal_pengajuan' => date('Y-m-d H:i:s'),
                'tanggal_verifikasi' => date('Y-m-d H:i:s'),
                'uuid' => $this->surat->generate_uuid(),
                'no_surat' => $nomor_surat
            ];
            
            $this->surat->insert($data_surat);
            
            $surat = $this->surat->db
                ->where('anggota_keluarga_id', $anggota->id)
                ->where('status_proses', 'Diterima')
                ->order_by('tanggal_verifikasi', 'DESC')
                ->get('surat')
                ->row();
        }


        $wilayah = $this->wilayah->getById($pj->wilayah_id);
        $kaling = $this->kaling->getByWilayahId($pj->wilayah_id);


        $data = [
            'nomor_surat' => $surat ->no_surat,
            'penghuni' => (object)[
                'nama_lengkap' => $anggota->nama,
                'tempat_lahir' => $anggota->tempat_lahir,
                'tanggal_lahir' => $anggota->tanggal_lahir,
                'jenis_kelamin' => $anggota->jenis_kelamin,
                'alamat_asal' => $pj->alamat_maps, 
                'kecamatan_asal' => '', 
                'kabupaten_asal' => '', 
                'provinsi_asal' => '', 
                'alamat_sekarang' => $pj->alamat_detail,
            ],
            'wilayah' => (object)[ 'wilayah' => $wilayah ? $wilayah->wilayah : '-' ],
            'kaling' => (object)[ 'nama' => $kaling ? $kaling->nama : '-' ],
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/cetak_pengantar_anggota', $data, true);
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->setPaper('legal', 'portrait');
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream('SPG_NUANSA_UTAMA_' . $anggota->nama . '.pdf', false);
    }


    public function cetak_pengantar_pj($pj_uuid)
    {
        if (empty($pj_uuid)) {
            show_error('UUID Penanggung Jawab kosong');
        }

        $this->load->model('PenanggungJawabModel', 'pj');
        $this->load->model('WilayahModel', 'wilayah');
        $this->load->model('KalingModel', 'kaling');
        $this->load->model('SuratModel', 'surat');
        $this->load->library('Dompdf_lib');

        $pj = $this->pj->getByUUID($pj_uuid);
        
        if (!$pj && is_numeric($pj_uuid)) {
            $pj = $this->pj->getById($pj_uuid);
        }

        if (!$pj) {
            show_error('Penanggung Jawab tidak ditemukan. Parameter yang dicari: ' . $pj_uuid);
        }

        $surat = $this->surat->db
            ->where('pj_id', $pj->id)
            ->where('status_proses', 'Diterima')
            ->order_by('tanggal_verifikasi', 'DESC')
            ->get('surat')
            ->row();

        if (!$surat) {
            $angka_acak = random_int(100, 9999);
            $nomor_surat = $angka_acak . '/SPG/' . date('Y');

            $data_surat = [
                'anggota_keluarga_id' => null,
                'pj_id' => $pj->id,
                'status_proses' => 'Diterima',
                'tanggal_pengajuan' => date('Y-m-d H:i:s'),
                'tanggal_verifikasi' => date('Y-m-d H:i:s'),
                'uuid' => $this->surat->generate_uuid(),
                'no_surat' => $nomor_surat
            ];

            $this->surat->insert($data_surat);

            $surat = $this->surat->db
                ->where('pj_id', $pj->id)
                ->where('status_proses', 'Diterima')
                ->order_by('tanggal_verifikasi', 'DESC')
                ->get('surat')
                ->row();
        }

        $wilayah = $this->wilayah->getById($pj->wilayah_id);
        $kaling = $this->kaling->getByWilayahId($pj->wilayah_id);

        $data = [
            'nomor_surat' => $surat->no_surat,
            'penghuni' => (object)[
                'nama_lengkap' => $pj->nama_pj,
                'tempat_lahir' => $pj->tempat_lahir,
                'tanggal_lahir' => $pj->tanggal_lahir,
                'jenis_kelamin' => $pj->jenis_kelamin,
                'alamat_asal' => $pj->alamat_maps,
                'kecamatan_asal' => '',
                'kabupaten_asal' => '',
                'provinsi_asal' => '',
                'alamat_sekarang' => $pj->alamat_detail,
            ],
            'wilayah' => (object)[ 'wilayah' => $wilayah ? $wilayah->wilayah : '-' ],
            'kaling' => (object)[ 'nama' => $kaling ? $kaling->nama : '-' ],
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/cetak_pengantar_pj', $data, true);
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->setPaper('legal', 'portrait');
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream('SPG_NUANSA_UTAMA_' . $pj->nama_pj . '.pdf', false);
    }




    // Fungsi GET penghuni untuk cetak surat 
    public function get_penghuni_by_pj($pj_id)
    {
        $penghuni = $this->penghuni->getByPenanggungJawabAndStatus($pj_id, 'Diterima');
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($penghuni ? $penghuni : []));
    }

    // Fungsi GET anggota keluarga untuk cetak surat 
    public function get_anggota_by_pj($pj_id)
    {
        $this->load->model('AnggotaKeluargaModel', 'anggota');
        $anggota = $this->anggota->getByPenanggungJawab($pj_id);
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($anggota ? $anggota : []));
    }

    // Fungsi untuk mengajukan surat pengantar pendatang
    public function ajukan_surat_pendatang()
    {
        $this->output->set_content_type('application/json');
        $penghuni_id = $this->input->post('penghuni');
        $pj_id = $this->session->userdata('pj_id');
        $status_proses = 'Diproses';

        // Validasi input dikirim ke json
        if (!$penghuni_id || !$pj_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data tidak lengkap']));
            return;
        }

        $this->load->model('PenghuniModel', 'penghuni');
        $penghuni = $this->penghuni->getByUuid($penghuni_id);
        if (!$penghuni) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data penghuni tidak ditemukan']));
            return;
        }

        $kaling_id = isset($penghuni->kaling_id) ? $penghuni->kaling_id : null;
        if (!$kaling_id && isset($penghuni->wilayah_id)) {
            $this->load->model('KalingModel', 'kaling');
            $kaling = $this->kaling->getByWilayahId($penghuni->wilayah_id);
            $kaling_id = $kaling ? $kaling->id : null;
        }
        if (!$kaling_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Field kaling_id wajib diisi. Data Kepala Lingkungan tidak ditemukan untuk wilayah ini.']));
            return;
        }

        // Cek duplikasi pengajuan
        $this->load->model('SuratModel', 'surat');
        $cek = $this->surat->db
            ->where('penghuni_id', $penghuni->id)
            ->where('pj_id', $pj_id)
            ->where('status_proses', 'Diproses')
            ->get('surat')
            ->row();
        if ($cek) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Masih ada pengajuan surat pendatang ini yang belum selesai diverifikasi.']));
            return;
        }

        $angka_acak = random_int(100, 9999);
        $data = [
            'penghuni_id' => $penghuni->id,
            'pj_id' => $pj_id,
            'status_proses' => $status_proses,
            'tanggal_pengajuan' => date('Y-m-d H:i:s'),
            'uuid' => $this->surat->generate_uuid(),
            'no_surat' => $angka_acak . '/SPG/' . date('Y')
        ];
        $this->surat->insert($data);
        $this->output->set_output(json_encode(['success' => true]));
    }

    // Fungsi untuk mengajukan surat pengantar pendatang oleh Penanggung Jawab
    public function ajukan_surat_pendatang_pj()
    {
        $this->output->set_content_type('application/json');
        $penghuni_id = $this->input->post('penghuni');
        $pj_id = $this->session->userdata('pj_id');
        $status_proses = 'Diproses';

        if (!$penghuni_id || !$pj_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data tidak lengkap']));
            return;
        }

        $this->load->model('PenghuniModel', 'penghuni');
        $penghuni = $this->penghuni->getByUuid($penghuni_id);
        if (!$penghuni) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data penghuni tidak ditemukan']));
            return;
        }

        $kaling_id = isset($penghuni->kaling_id) ? $penghuni->kaling_id : null;
        if (!$kaling_id && isset($penghuni->wilayah_id)) {
            $this->load->model('KalingModel', 'kaling');
            $kaling = $this->kaling->getByWilayahId($penghuni->wilayah_id);
            $kaling_id = $kaling ? $kaling->id : null;
        }
        if (!$kaling_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Field kaling_id wajib diisi. Data Kepala Lingkungan tidak ditemukan untuk wilayah ini.']));
            return;
        }

        // Cek duplikasi pengajuan
        $this->load->model('SuratModel', 'surat');
        $cek = $this->surat->db
            ->where('penghuni_id', $penghuni->id)
            ->where('pj_id', $pj_id)
            ->where('status_proses', 'Diproses')
            ->get('surat')
            ->row();
        if ($cek) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Masih ada pengajuan surat pendatang ini yang belum selesai diverifikasi.']));
            return;
        }

        $angka_acak = random_int(100, 9999);
        $data = [
            'penghuni_id' => $penghuni->id,
            'pj_id' => $pj_id,
            'status_proses' => $status_proses,
            'tanggal_pengajuan' => date('Y-m-d H:i:s'),
            'uuid' => $this->surat->generate_uuid(),
            'no_surat' => $angka_acak . '/SPG/' . date('Y')
        ];
        $this->surat->insert($data);
        
        // fungsi triggers
        $this->db->where_in('status_proses', ['Diproses']);
        $this->db->order_by('tanggal_pengajuan', 'DESC');
        $this->db->limit(5);
        $latest_surat = $this->db->get('surat')->result();

        $this->pusher_lib->trigger('notifikasi-surat', 'status-update', ['data' => $latest_surat]);

        $this->output->set_output(json_encode(['success' => true]));
    }

    // Fungsi untuk mengajukan surat pengantar anggota keluarga
    public function ajukan_surat_anggota()
    {
        $this->output->set_content_type('application/json');
        $anggota_id_or_uuid = $this->input->post('anggota_id');
        $pj_id = $this->session->userdata('pj_id');
        $status_proses = 'Diproses';

        if (!$anggota_id_or_uuid || !$pj_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data tidak lengkap']));
            return;
        }

        $this->load->model('AnggotaKeluargaModel', 'anggota');
        if (is_numeric($anggota_id_or_uuid)) {
            $anggota = $this->anggota->getById($anggota_id_or_uuid);
        } else {
            $anggota = $this->anggota->getByUuid($anggota_id_or_uuid);
        }
        if (!$anggota) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Data anggota tidak ditemukan']));
            return;
        }

        $this->load->model('PenanggungJawabModel', 'pj');
        $pj = $this->pj->getById($pj_id);
        if (!$pj) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Penanggung Jawab tidak ditemukan']));
            return;
        }

        $kaling_id = isset($pj->kaling_id) ? $pj->kaling_id : null;
        if (!$kaling_id && isset($pj->wilayah_id)) {
            $this->load->model('KalingModel', 'kaling');
            $kaling = $this->kaling->getByWilayahId($pj->wilayah_id);
            $kaling_id = $kaling ? $kaling->id : null;
        }
        if (!$kaling_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Field kaling_id wajib diisi. Data Kepala Lingkungan tidak ditemukan untuk wilayah ini.']));
            return;
        }

        $this->load->model('SuratModel', 'surat');
        $cek = $this->surat->db
            ->where('anggota_keluarga_id', $anggota->id)
            ->where('pj_id', $pj_id)
            ->where('status_proses', 'Diproses')
            ->get('surat')
            ->row();
        if ($cek) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Masih ada pengajuan surat anggota ini yang belum selesai diverifikasi.']));
            return;
        }

        $angka_acak = random_int(100, 9999);
        $data = [
            'penghuni_id' => null,
            'anggota_keluarga_id' => $anggota->id,
            'pj_id' => $pj_id,
            'status_proses' => $status_proses,
            'tanggal_pengajuan' => date('Y-m-d H:i:s'),
            'uuid' => $this->surat->generate_uuid(),
            'no_surat' => $angka_acak . '/SPG/' . date('Y')
        ];
        $this->surat->insert($data);
       
        // fungsi triggers
        $this->db->where_in('status_proses', ['Diproses']);
        $this->db->order_by('tanggal_pengajuan', 'DESC');
        $this->db->limit(5);
        $latest_surat = $this->db->get('surat')->result();

        $this->pusher_lib->trigger('notifikasi-surat', 'status-update', ['data' => $latest_surat]);

        $this->output->set_output(json_encode(['success' => true]));

    }

    // Fungsi untuk verifikasi surat
    
    public function verifikasi()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if (!$id || !$status) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'ID dan status wajib diisi']));
            return;
        }
        $data = [
            'status_proses' => $status,
            'tanggal_verifikasi' => date('Y-m-d H:i:s')
        ];
        $this->load->model('SuratModel', 'surat');
        $result = $this->surat->update($id, $data);

       if ($result) {
        if (in_array($status, ['Diterima', 'Ditolak'])) {
            $this->db->where_in('status_proses', ['Diproses', 'Diterima', 'Ditolak']);
            $this->db->order_by('tanggal_pengajuan', 'DESC');
            $this->db->limit(5);
            $latest_surat = $this->db->get('surat')->result();

            $this->load->library('Pusher_lib');
            $this->pusher_lib->trigger('notifikasi-surat', 'status-update', ['data' => $latest_surat]);
        }

            $this->output->set_output(json_encode(['success' => true]));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Gagal memverifikasi surat']));
        }
    }

    // Fungsi untuk mengupdate status surat
    public function update_status($id) {
        $this->load->model('SuratModel', 'surat');
        $status_baru = $this->input->post('status_proses');
        $data = [
            'status_proses' => $status_baru,
            'tanggal_verifikasi' => date('Y-m-d H:i:s')
        ];
       
        if ($status_baru == 'Diproses') {
            $data['notifikasi'] = 0;
        } elseif ($status_baru == 'Diterima' || $status_baru == 'Ditolak') {
            $data['notifikasi'] = 1;
        }

        $this->db->where('id', $id)->update('surat', $data);
        $this->output->set_output(json_encode(['success' => true]));
    }

}
