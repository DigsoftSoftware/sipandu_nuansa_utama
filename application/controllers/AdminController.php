<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('AdminModel');
        $this->load->model('UserModel');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function view() {
        $data['title'] = "Data Admin | SIPANDU Nuansa Utama";
        $data['admins'] = $this->AdminModel->getAll();
        $this->load->view('admin/admin_list', $data);
    }

    public function create() {
        $data['title'] = "Buat Akun Admin | SIPANDU Nuansa Utama";
        $this->load->view('admin/admin_create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/admin/create');
        }

        $user = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Admin',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $user_id = $this->AdminModel->insertUser($user);
        $this->AdminModel->insertAdmin([
            'user_id' => $user_id,
            'nama' => $this->input->post('nama')
        ]);

        $this->session->set_flashdata('success', 'Admin berhasil ditambahkan!');
        redirect('dashboard/admin/view');

    }

    public function edit($id) {
        $admin = $this->AdminModel->getById($id);

        if (!$admin) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('admin');
            return;
        }

        $data['admin'] = $admin;
        $data['title'] = "Edit Akun Admin | SIPANDU Nuansa Utama";;
        $this->load->view('admin/admin_edit', $data);
    }

    public function update($id) {
        $nama = $this->input->post('nama'); 
        $username = $this->input->post('username');
        $password = $this->input->post('password');
    
        $admin = $this->AdminModel->getById($id);
        if (!$admin) {
            $this->session->set_flashdata('error', 'Data admin tidak ditemukan.');
            redirect('dashboard/admin/view');
            return;
        }

        $this->AdminModel->update($id, ['nama' => $nama]);
        $userData = ['username' => $username];
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->UserModel->update($admin->user_id, $userData);
    
        $this->session->set_flashdata('success', 'Data admin berhasil diupdate.');
        redirect('dashboard/admin/view');
    }
    

    public function delete($id) {
        $this->AdminModel->delete($id);
        $this->session->set_flashdata('success', 'Admin berhasil dihapus!');
        redirect('dashboard/admin/view');

    }
}
