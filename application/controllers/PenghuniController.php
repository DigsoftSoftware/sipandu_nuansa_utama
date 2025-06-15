<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenghuniController extends MY_Controller 
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel'); 
        $this->load->model('KalingModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // Fungsi untuk generate UUID
    private function generate_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    // Fungsi untuk mengupload file
    private function _upload_file($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === 4) {
            return null;
        }

        $config['upload_path'] = FCPATH . 'uploads/penghuni/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = '4098';
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            return null;
        }

        $upload_data = $this->upload->data();
        return $upload_data['file_name'];
    }   

    // Fungsi untuk tampilan halaman di session Admin dan Kepala Lingkungan
    public function index()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni_menunggu'] = $this->PenghuniModel->getByStatus('Menunggu');
        $data['penghuni_diproses'] = $this->PenghuniModel->getByStatus('Diproses');
        $data['penghuni_terverifikasi'] = $this->PenghuniModel->getByStatusVerifikasiSorted(['Diterima', 'Ditolak']);
        $data['penanggung_jawab'] = $this->PenanggungJawabModel->getAll();
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_list_admin', $data);
    }

    // Fungsi untuk tampilan halaman detail di session Admin dan Kepala Lingkungan
    public function detail_admin($uuid)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['title'] = "Detail Data Penghuni | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('penghuni/penghuni_details_admin', $data);
    }

    // Fungsi untuk tampilan halaman detail di session Penanggung Jawab
    public function detail_pj($uuid)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['title'] = "Detail Data Penghuni | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('penghuni/penghuni_details_pj', $data);
    }

    // Fungsi untuk verifikasi data pendatang
    public function verifikasi($id, $status)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);

        if (!$this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid request method'
                ]));
            return;
        }

        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]));
            return;
        }

        $data = [
            'status_verifikasi' => $status
        ];

        if ($status === 'Ditolak') {
            $alasan = $this->input->post('alasan');
            if (empty($alasan)) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Alasan penolakan harus diisi'
                    ]));
                return;
            }
            $data['alasan'] = $alasan;
            $data['status_penghuni'] = 'Tidak Aktif';
            $data['tanggal_keluar'] = date('Y-m-d');
        } elseif ($status === 'Diterima') {
            $data['status_penghuni'] = 'Aktif';
            $data['alasan'] = null;
            $data['tanggal_keluar'] = null;
        }

        if ($this->PenghuniModel->update($id, $data)) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Status verifikasi berhasil diubah',
                    'data' => [
                        'status_verifikasi' => $status,
                        'status_penghuni' => $data['status_penghuni']
                    ]
                ]));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengubah status verifikasi'
                ]));
        }
    }

    // Fungsi untuk mengubah status pendatang menjadi nonaktif
    public function nonaktifkan_status($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan', 'Penanggung Jawab']);
        
        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/penghuni/view');
            return;
        }

        if ($penghuni->status_verifikasi !== 'Diterima') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Hanya data terverifikasi yang dapat diubah statusnya'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Hanya data terverifikasi yang dapat diubah statusnya');
            redirect('dashboard/penghuni/view');
            return;
        }

        $data = [
            'status_penghuni' => 'Tidak Aktif',
            'tanggal_keluar' => date('Y-m-d') 
        ];

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Status penghuni berhasil dinonaktifkan'
                    ]));
                return;
            }
            $this->session->set_flashdata('success', 'Status penghuni berhasil dinonaktifkan');
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal mengubah status penghuni'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Gagal mengubah status penghuni');
        }
        redirect('dashboard/penghuni/view');
    }

    // Fungsi untuk mengubah status penghuni menjadi aktif kembali
    public function aktifkan_status($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan', 'Penanggung Jawab']);

        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/penghuni/view');
            return;
        }

        if ($penghuni->status_verifikasi !== 'Diterima') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Hanya data terverifikasi yang dapat diubah statusnya'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Hanya data terverifikasi yang dapat diubah statusnya');
            redirect('dashboard/penghuni/view');
            return;
        }

        $data = [
            'status_penghuni' => 'Aktif',
            'tanggal_keluar' => null
        ];

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Status penghuni berhasil diaktifkan kembali'
                    ]));
                return;
            }
            $this->session->set_flashdata('success', 'Status penghuni berhasil diaktifkan kembali');
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal mengaktifkan kembali status penghuni'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Gagal mengaktifkan kembali status penghuni');
        }

        redirect('dashboard/penghuni/view');
    }

    // Fungsi untuk tampilan halaman penghuni di session Penanggung Jawab
    public function index_pj()
    {
        $this->check_role(['Penanggung Jawab']);
        $pj_id = $this->session->userdata('pj_id');
        $this->load->model('PenanggungJawabModel');
        if (!$this->PenanggungJawabModel->is_profile_complete($pj_id)) {
            redirect('dashboard/pj/validation');
            return;
        }
        $data['penghuni'] = $this->PenghuniModel->getByPJ($pj_id);
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_list_pj', $data);
    }

    // Fungsi untuk tambah data pendatang di session Admin dan Kepala Lingkungan
    public function create_admin()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $data['title'] = "Tambah Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_create_admin', $data);
    }

    public function store_admin()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->form_validation->set_rules('nik', 'NIK', 'required|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('no_hp', 'No Handphone');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'in_list[A,B,AB,O]');
        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/create_admin');
            return;
        }

        $foto_profil = $this->_upload_file('foto');
        $foto_ktp = $this->_upload_file('ktp');

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'no_hp' => $this->input->post('no_hp'),
            'jenis_kelamin' => $this->input->post('jk'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif'
        ];

        if ($this->PenghuniModel->insert($data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/view');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni.');
            redirect('dashboard/penghuni/create_admin');
        }
    }

    // Fungsi untuk tambah data pendatang di session Penanggung Jawab
    public function create_pj()
    {
        $this->check_role(['Penanggung Jawab']);
        $data['error'] = validation_errors();
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Tambah Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_create_pj', $data);
    }

    public function store_pj()
    {
        $this->check_role(['Penanggung Jawab']);
        $this->form_validation->set_rules('nik', 'NIK', 'required|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('no_hp', 'No Handphone');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'in_list[A,B,AB,O]');
        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'required|numeric');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Penanggung Jawab tidak ditemukan. Silakan login ulang.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $foto_profil = $this->_upload_file('foto');
        $foto_ktp = $this->_upload_file('ktp');

        $data = [
            'uuid' => $this->generate_uuid(),
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jk'),
            'no_hp' => $this->input->post('no_hp'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $pj_id,
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif'
        ];

        if ($this->PenghuniModel->insert($data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/viewpj');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni.');
            redirect('dashboard/penghuni/viewpj/create_admin');
        }
    }

    // Fungsi untuk mengedit data pendatang di session Admin dan Kepala Lingkungan
    public function edit_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $data['title'] = "Edit Data Penghuni | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        $this->load->view('penghuni/penghuni_edit_admin', $data);
    }

    public function update_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $penghuni = $this->PenghuniModel->getByUuid($uuid);

        if (!$penghuni) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        $this->form_validation->set_rules('nik', 'NIK', 'trim|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('no_hp', 'No Handphone');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'trim|in_list[A,B,AB,O]');
        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'trim');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'trim');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'trim');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'trim');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'trim');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'trim');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'trim');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'trim');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'trim|numeric');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'trim|numeric');
        $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'trim|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/edit_admin/' . $uuid);
            return;
        }

        $foto_profil = $this->_upload_file('foto');
        $foto_ktp = $this->_upload_file('ktp');

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jk'),
            'no_hp' => $this->input->post('no_hp'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses'
        ];

        if ($foto_profil) {
            $data['foto_profil'] = $foto_profil;
            if ($penghuni->foto_profil) {
                $old_file = FCPATH . 'uploads/penghuni/' . $penghuni->foto_profil;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }

        if ($foto_ktp) {
            $data['foto_ktp'] = $foto_ktp;
            if ($penghuni->foto_ktp) {
                $old_file = FCPATH . 'uploads/penghuni/' . $penghuni->foto_ktp;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil diperbarui.');
            redirect('dashboard/penghuni/view');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data penghuni.');
            redirect('dashboard/penghuni/edit_admin/' . $uuid);
        }
    }

    // Fungsi untuk mengedit data pendatang di session Penanggung Jawab
    public function edit_pj($uuid)
    {
        $this->check_role(['Penanggung Jawab']);
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Edit Data Penghuni | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        if ($data['penghuni']->status_verifikasi !== 'Ditolak') {
            $this->session->set_flashdata('error', 'Hanya data dengan status "Ditolak" yang dapat diedit.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $this->load->view('penghuni/penghuni_edit_pj', $data);
    }

    public function update_pj($uuid)
    {
        $this->check_role(['Penanggung Jawab']);
        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        $pj_id = $this->session->userdata('pj_id');

        if (!$penghuni || $penghuni->status_verifikasi !== 'Ditolak' || $penghuni->penanggung_jawab_id != $pj_id) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan atau tidak dapat diedit.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $this->form_validation->set_rules('nik', 'NIK', 'trim|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'trim|in_list[A,B,AB,O]');
        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('no_hp', 'No Handphone');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'trim');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'trim');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'trim');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'trim');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'trim');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'trim');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'trim');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'trim');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'trim|numeric');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'trim|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/edit_pj/' . $uuid);
            return;
        }

        $foto_profil = $this->_upload_file('foto');
        $foto_ktp = $this->_upload_file('ktp');

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jk'),
            'no_hp' => $this->input->post('no_hp'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses'
        ];

        if ($foto_profil) {
            $data['foto_profil'] = $foto_profil;
            if ($penghuni->foto_profil) {
                $old_file = FCPATH . 'uploads/penghuni/' . $penghuni->foto_profil;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }

        if ($foto_ktp) {
            $data['foto_ktp'] = $foto_ktp;
            if ($penghuni->foto_ktp) {
                $old_file = FCPATH . 'uploads/penghuni/' . $penghuni->foto_ktp;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil diperbarui.');
            redirect('dashboard/penghuni/viewpj');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data penghuni.');
            redirect('dashboard/penghuni/edit_pj/' . $uuid);
        }
    }

    // Fungsi untuk mendapatkan lokasi PJ untuk ditampikan di Form Pendatang
    // Fungsi ini akan mengembalikan data lokasi dalam format JSON
     public function getPJLocation()
    {
        $pj_id = $this->input->get('pj_id');
        if (!$pj_id) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'ID Penanggung Jawab tidak ditemukan'
                ]));
            return;
        }

        $pj = $this->PenanggungJawabModel->getById($pj_id);
        if (!$pj) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Data Penanggung Jawab tidak ditemukan'
                ]));
            return;
        }

       $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => [
                    'latitude' => $pj->latitude,
                    'longitude' => $pj->longitude,
                    'alamat' => $pj->alamat_detail
                ]
            ]));

    }


    // Fungsi untuk menghapus data pendatang di session Admin dan Kepala Lingkungan
    public function delete($id)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);

        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data penghuni tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        if ($penghuni->foto_profil) {
            $file_path = './uploads/' . $penghuni->foto_profil;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        if ($penghuni->foto_ktp) {
            $file_path = './uploads/' . $penghuni->foto_ktp;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $deleted = $this->PenghuniModel->delete($id);

        if ($this->input->is_ajax_request()) {
            if ($deleted) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Data penghuni berhasil dihapus'
                    ]));
            } else {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal menghapus data penghuni'
                    ]));
            }
            return;
        }

        if ($deleted) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data penghuni.');
        }

        redirect('dashboard/penghuni/view');
    }
   
    // Fungsi untuk filter data terverifikasi berdasarkan Penanggung Jawab
    // Fungsi ini akan mengembalikan data dalam format JSON
    public function filterTerverifikasiByPJ()
    {
        $this->output->set_content_type('application/json');
        
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]));
            return;
        }

        $pj_id = $this->input->get('pj_id');
        $status = $this->input->get('status');
        
        log_message('debug', 'Filter Terverifikasi By PJ ID: ' . $pj_id); 
        log_message('debug', 'Filter Status: ' . $status);

        $this->db->select('p.*, pj.nama_pj, p.uuid');
        $this->db->from('penghuni p');
        $this->db->join('penanggung_jawab pj', 'p.penanggung_jawab_id = pj.id');
        $this->db->where('p.status_verifikasi', 'Diterima');
        
        if ($pj_id) {
            $this->db->where('p.penanggung_jawab_id', $pj_id);
        }
        
        if ($status) {
            $this->db->where('p.status_penghuni', $status);
        }
        
        $this->db->order_by('FIELD(p.status_penghuni, "Aktif", "Tidak Aktif")', '');
        
        $data = $this->db->get()->result();

        log_message('debug', 'Data returned: ' . json_encode($data));
        $this->output->set_output(json_encode($data));
    }
}
