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
            echo "Link tidak valid atau sudah kadaluarsa.";
            return;
        }
    
        $data['token'] = $token;
        $data['title'] = "Daftar Penanggung Jawab | SIPANDU NUANSA UTAMA";

        $this->load->model('WilayahModel');
        $data['wilayah'] = $this->WilayahModel->get_all();
    
        $this->load->view('partials/header', $data);
        $this->load->view('auth/register_views_pj');
        $this->load->view('partials/footer');
    }
    

    public function submit_pj() {
        $token = $this->input->post('token');
        $valid_token = $this->TokenModel->validate_token($token);
    
        if (!$valid_token) {
            $this->session->set_flashdata('error', 'Token sudah tidak valid!');
            redirect('auth');
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
    
        $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silakan login.');
        redirect('auth');
    }    
}
