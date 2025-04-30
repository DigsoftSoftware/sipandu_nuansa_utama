<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenghuniController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['PenghuniModel', 'WilayahModel', 'KalingModel', 'PenanggungJawabModel']);
        $this->load->library(['form_validation', 'session', 'upload']);
        $this->load->helper(['url', 'form']);
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);
    }

    public function index() {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni_menunggu'] = $this->PenghuniModel->getByStatus('Menunggu');
        $data['penghuni_diproses'] = $this->PenghuniModel->getByStatus('Diproses'); 
        $data['penghuni_terverifikasi'] = $this->PenghuniModel->getByStatus(['Diterima', 'Ditolak']);
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('admin/penghuni_views', $data);
    }
    
    public function detail_admin($id)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByid($id);

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('admin/penghuni_details_admin', $data);
    }
    public function detail_pj($id)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByid($id);

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('pj/penghuni_details_pj', $data);
    }

    public function verifikasi($id, $status) {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        
        if (!$this->input->is_ajax_request()) {
            show_404();
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

        $update = ['status_verifikasi' => $status];
        if ($status === 'Ditolak') {
            $update['alasan'] = $this->input->post('alasan');
        } else {
            $update['alasan'] = null;
        }

        if ($this->PenghuniModel->update($id, $update)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                            'status' => 'success',
                            'message' => 'Status verifikasi berhasil diubah!'
                         ]));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                            'status' => 'error',
                            'message' => 'Gagal mengubah status verifikasi'
                         ]));
        }
    }

    public function index_pj() {
        $this->check_role(['Penanggung Jawab']);
        $pj_id = $this->session->userdata('pj_id');

        $data['penghuni'] = $this->PenghuniModel->getByPJ($pj_id);
        $data['title'] = "Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('pj/penghuni_views', $data);
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
        $this->form_validation->set_rules('nik', 'NIK', 'required|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');    
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
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'required|numeric');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required|numeric');
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
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses' 
        ];
    
        if ($this->PenghuniModel->insert($data)) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/view');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni.');
            redirect('dashboard/penghuni/create_admin');
        }
    }

    public function create_pj() {
        $this->check_role(['Penanggung Jawab']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Tambah Data Penghuni | SIPANDU Nuansa Utama";
        $this->load->view('pj/penghuni_create', $data);
    }

    public function store_pj() {
        $this->check_role(['Penanggung Jawab']);
        $this->form_validation->set_rules('nik', 'NIK', 'required|exact_length[16]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');    
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
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required');
        $this->form_validation->set_rules('kaling_id', 'Kepala Lingkungan', 'required|numeric');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/viewpj/create_admin');
            return;
        }

        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Penanggung Jawab tidak ditemukan. Silakan login ulang.');
            redirect('dashboard/penghuni/viewpj/create_admin');
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
            redirect('dashboard/penghuni/viewpj/create_admin');
        }
    }

    private function _upload_file($field) {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === 4) {
            return null;
        }

        $config['upload_path'] = FCPATH . 'uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = '2048'; 
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

    public function delete($id) {
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
    
    public function print($id) {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni'] = $this->PenghuniModel->getById($id);
        
        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('document/surat_keterangan_tinggal', $data);
    }
}
