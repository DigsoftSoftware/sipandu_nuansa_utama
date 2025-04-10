<?php
class LoginController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('UserModel');
    }

    public function login() {
        $data['title'] = "Login - SIPANDU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('auth/login_views');
        $this->load->view('partials/footer');
    }
    public function do_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
    
        $user = $this->UserModel->get_by_username($username);
    
        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata('user_id', $user->id);
            $this->session->set_userdata('user_username', $user->username);
            $this->session->set_userdata('role', $user->role);

            $this->session->set_flashdata('success_login', 'Selamat datang kembali, ' . $user->username . '!');
    
            if ($user->role === 'Admin') {
                redirect('dashboard/admin');
            } elseif ($user->role === 'Kepala Lingkungan') {
                redirect('dashboard/kaling');
            } elseif ($user->role === 'Penanggung Jawab') {
                redirect('dashboard/pj');
            } else {
                $this->session->set_flashdata('error', 'Peran tidak dikenal.');
                redirect('/');
            }
        } else {
            $this->session->set_flashdata('error', 'Login gagal! Username atau password salah.');
            redirect('/');
        }
        
    } 
    public function logout() {
        $this->session->sess_destroy();
        $this->session->flashdata('success','Anda Berhasil Logout!');
        redirect('/');
    }
}
