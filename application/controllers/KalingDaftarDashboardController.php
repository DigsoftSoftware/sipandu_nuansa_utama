<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KalingDaftarDashboardController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('KalingModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
    }

    public function view() {
        $data['title'] = "Data Kepala Lingkungan | SIPANDU Nuansa Utama";
        $data['kaling'] = $this->KalingModel->getAll();
        $this->load->view('admin/kaling_list', $data);
    }

    public function create() {
        $data['wilayah'] = $this->WilayahModel->get_all(); 
        $data['title'] = "Buat Akun Kepala Lingkungan | SIPANDU Nuansa Utama";
        $this->load->view('admin/kaling_create', $data);  
    }
    

    public function store() {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/kaling/create');
        }

        $userData = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Kepala Lingkungan',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->insert('users', $userData);
        $user_id = $this->db->insert_id();
    
        $kalingData = [
            'user_id' => $user_id,
            'nama' => $this->input->post('nama'),
            'wilayah_id' => $this->input->post('wilayah_id'), 
        ];
        
        $this->KalingModel->insert($kalingData);
    
        $this->session->set_flashdata('success', 'Data Kaling berhasil ditambahkan!');
        redirect('dashboard/kaling/view');
    }
    

    public function edit($id) {
        $data['kaling'] = $this->KalingModel->getUserId($id);
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Edit Akun Kepala Lingkungan | SIPANDU Nuansa Utama";
        $this->load->view('admin/kaling_edit', $data);
    }

    public function update($id) {
        $nama = $this->input->post('nama');
        $wilayah_id = $this->input->post('wilayah_id');
        $password = $this->input->post('password');
    
        $kaling = $this->KalingModel->getById($id);
    
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $this->db->where('id', $kaling->user_id)->update('users', ['password' => $hashed]);
        }
    
        $data = [
            'nama' => $nama,
            'wilayah_id' => $wilayah_id,
        ];
        $this->KalingModel->update($id, $data);
    
        $this->session->set_flashdata('success', 'Data Kaling berhasil diupdate!');
        redirect('dashboard/kaling/view');
    }
    
    

    public function delete($id) {
        $this->KalingModel->delete($id);
        $this->session->set_flashdata('success', 'Data Kaling berhasil dihapus!');
        redirect('dashboard/kaling/view');
    }
}
