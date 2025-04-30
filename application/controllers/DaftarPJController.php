<?php
class DaftarPJController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('TokenModel');
    }

    public function generate_link_view() {
        $data['title']= "Short Link Daftar Penanggung Jawab | SIPANDU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('partials/footer');
        $this->load->view('auth/generate_link');
    }

    public function link_pj() {
        $token = $this->TokenModel->generate_token();
        $link = base_url("daftar/pj/$token");
        echo json_encode(['link' => $link]);
    }

    public function pj($token) {
        $valid_token = $this->TokenModel->validate_token($token);
        
        if (!$valid_token) {
        $data['title'] = "Token Kadaluwarsa | SIPANDU NUANSA UTAMA";
        $this->load->view('auth/token_views', $data);
        return;
    }
    
        $data['token'] = $token;
        $data['title'] = "Daftar Penanggung Jawab | SIPANDU NUANSA UTAMA";

        $this->load->model('WilayahModel');
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('auth/register_views_pj', $data);
    }
    

    public function submit_pj() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
    
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[penanggung_jawab.email]', [
            'required'      => 'Email wajib diisi.',
            'valid_email'   => 'Format email tidak valid.',
            'is_unique'     => 'Email sudah digunakan, silakan gunakan email yang lain.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
    
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'errors' => array_map(function($e) {
                    return '❌ ' . $e;
                }, $this->form_validation->error_array())
            ]);
            return;
        }
    
        $token = $this->input->post('token');
        $valid_token = $this->TokenModel->validate_token($token);
        if (!$valid_token) {
            echo json_encode(['status' => 'error', 'errors' => ['Token sudah tidak valid atau kadaluarsa!']]);
            return;
        }
    
        $hashed_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $user_data = [
            'username' => $this->input->post('username'),
            'password' => $hashed_password,
            'role' => 'Penanggung Jawab',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();
    
        $pj_data = [
            'user_id' => $user_id,
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp'),
            'wilayah_id' => $this->input->post('wilayah_id')
        ];
        $this->db->insert('penanggung_jawab', $pj_data);
        $pj_id = $this->db->insert_id();
    
        $this->TokenModel->mark_token_as_used($token, $pj_id);
    
        echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil! Silakan login.']);
    }
    
}
