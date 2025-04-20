<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenghuniController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PenghuniModel');
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');
        $this->load->model('PJModel');
        $this->load->library(['form_validation', 'session']);
    }

    public function index() {
        $this->check_role(['Admin', 'Kepala Lingkungan']);

        $data['penghuni_menunggu'] = $this->PenghuniModel->getByStatus('Menunggu');
        $data['penghuni_terverifikasi'] = $this->PenghuniModel->getByStatus(['Diterima', 'Ditolak']);
        $data['title'] = "Data Penghuni | SIPANDU";
        $this->load->view('penghuni/list_admin_kaling', $data);
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
            $update['alasan_penolakan'] = $this->input->post('alasan_penolakan');
        }

        $this->PenghuniModel->update($id, $update);
        $this->session->set_flashdata('success', 'Verifikasi berhasil!');
        redirect('dashboard/penghuni');
    }

    public function index_pj() {
        $this->check_role(['Penanggung Jawab']);
        $pj_id = $this->session->userdata('pj_id');

        $data['penghuni'] = $this->PenghuniModel->getByPJ($pj_id);
        $data['title'] = "Data Anda | SIPANDU";
        $this->load->view('penghuni/list_pj', $data);
    }

    public function tambah() {
        $this->check_role(['Penanggung Jawab']);
        $data['wilayah'] = $this->get_wilayah_data(); 
        $data['title'] = "Tambah Penghuni";
        $this->load->view('penghuni/form_tambah', $data);
    }

    private function get_wilayah_data() {
        $wilayahAPI = 'https://wilayah.id/api/provinces';
        return $this->getWilayahData($wilayahAPI);
    }

    private function getWilayahData($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            return [];
        }

        return json_decode($response, true);
    }

    public function simpan() {
        $this->check_role(['Penanggung Jawab']);

        $this->form_validation->set_rules('nik', 'NIK', 'required|is_unique[penghuni.nik]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/penghuni/tambah');
        }

        $foto = $this->_upload_file('foto');
        $ktp = $this->_upload_file('ktp');

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'no_hp' => $this->input->post('no_hp'),
            'ttl' => $this->input->post('ttl'),
            'provinsi_asal' => $this->input->post('provinsi_asal'),
            'kabupaten_asal' => $this->input->post('kabupaten_asal'),
            'kecamatan_asal' => $this->input->post('kecamatan_asal'),
            'kelurahan_asal' => $this->input->post('kelurahan_asal'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'lama_tinggal' => $this->input->post('lama_tinggal'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'foto' => $foto,
            'ktp' => $ktp,
            'wilayah_id' => $this->input->post('wilayah_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'penanggung_jawab_id' => $this->session->userdata('pj_id'),
            'status_verifikasi' => 'Menunggu'
        ];

        $this->PenghuniModel->insert($data);
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect('dashboard/penghuni/pj');
    }

    private function _upload_file($field) {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload($field)) {
            return $this->upload->data('file_name');
        }
        return NULL;
    }
}
