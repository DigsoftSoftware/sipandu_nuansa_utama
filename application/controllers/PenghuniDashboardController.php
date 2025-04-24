<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenghuniDashboardController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PenghuniModel');
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->library(['form_validation', 'session', 'upload']);
        $this->load->helper('url');
    }

    public function index() {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni_menunggu'] = $this->PenghuniModel->getByStatus('Menunggu');
        $data['penghuni_diproses'] = $this->PenghuniModel->getByStatus('Diproses'); 
        $data['penghuni_terverifikasi'] = $this->PenghuniModel->getByStatus(['Diterima', 'Ditolak']);
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('admin/penghuni_views', $data);
    }
    
    public function detail($id)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getById($id);

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('admin/penghuni_view', $data);
    }

    public function verifikasi($id, $status) {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/penghuni');
        }

        $update = ['status_verifikasi' => $status];
        if ($status === 'Ditolak') {
            $update['alasan'] = $this->input->post('alasan');
        } else {
            $update['alasan'] = null; 
        }

        $this->PenghuniModel->update($id, $update);
        $this->session->set_flashdata('success', 'Verifikasi berhasil!');
        redirect('dashboard/penghuni');
    }

    public function index_pj() {
        $this->check_role(['Penanggung Jawab']);
        $pj_id = $this->session->userdata('pj_id');

        $data['penghuni'] = $this->PenghuniModel->getByPJ($pj_id);
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('pj/penghuni_views', $data);
    }

    public function create() {
        $this->check_role(['Penanggung Jawab']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Tambah Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('pj/penghuni_create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/create');
            return;
        }
    
        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Penanggung Jawab tidak ditemukan. Silakan login ulang.');
            redirect('dashboard/penghuni/create');
            return;
        }

        // Handle file uploads
        $foto_profil = $this->_upload_file('foto');
        $foto_ktp = $this->_upload_file('ktp');

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jk'),
            'provinsi_asal' => $this->input->post('provinsi_asal'),
            'kabupaten_asal' => $this->input->post('kabupaten_asal'),
            'kecamatan_asal' => $this->input->post('kecamatan_asal'),
            'kelurahan_asal' => $this->input->post('kelurahan_asal'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $pj_id,
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses'
        ];
    
        if ($this->PenghuniModel->insert($data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/viewpj');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni.');
            redirect('dashboard/penghuni/create');
        }
    }
    
    public function create_admin() {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $data['title'] = "Tambah Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('admin/penghuni_create', $data);
    }

    public function store_admin() {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
        $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required');
    
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
            'jenis_kelamin' => $this->input->post('jk'),
            'provinsi_asal' => $this->input->post('provinsi_asal'),
            'kabupaten_asal' => $this->input->post('kabupaten_asal'),
            'kecamatan_asal' => $this->input->post('kecamatan_asal'),
            'kelurahan_asal' => $this->input->post('kelurahan_asal'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diterima' 
        ];
    
        if ($this->PenghuniModel->insert($data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/view');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni.');
            redirect('dashboard/penghuni/create_admin');
        }
    }

    private function _upload_file($field) {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload($field)) {
            return $this->upload->data('file_name');
        }
        return null;
    }

    private function hitungLamaTinggal($masuk, $keluar) {
        if ($masuk && $keluar && strtotime($masuk) && strtotime($keluar)) {
            $start = date_create($masuk);
            $end = date_create($keluar);
            $diff = date_diff($start, $end);
            return $diff->days . ' hari';
        }
        return 'Tidak valid';
    }

    public function delete($id) {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        
        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        // Delete related files if they exist
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

        if ($this->PenghuniModel->delete($id)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data penghuni.');
        }
        redirect('dashboard/penghuni/view');
    }
}
