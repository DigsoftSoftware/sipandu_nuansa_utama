<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        
        $this->load->model(['PenanggungJawabModel', 'UserModel', 'WilayahModel']);
        
        $this->load->library(['form_validation', 'session']);
        
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $data['title'] = "Dashboard Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/dashboard_pj', $data);
    }
    
    public function view() {
        $data['title'] = "Data Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $this->load->view('pj/pj_list', $data);
    }

    public function create() {
        $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('pj/pj_create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[penanggung_jawab.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/pj/create');
        }
        $hashed_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $user_data = [
            'username' => $this->input->post('username'),
            'password' => $hashed_password,
            'role' => 'Penanggung Jawab',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();
    
        if ($user_id) {
            
            $pj_data = [
                'user_id' => $user_id,
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'wilayah_id' => $this->input->post('wilayah_id')
            ];

            $this->PenanggungJawabModel->create($pj_data);
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil ditambahkan.');
            redirect('dashboard/pj/view');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data.');
            redirect('dashboard/pj/create');
        }
    }

    public function edit($id) {
        $pj = $this->PenanggungJawabModel->getById($id);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
        }
        $data['pj'] = $pj;
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Edit Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[penanggung_jawab.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/pj/edit/' . $id);
        }

        $pj = $this->PenanggungJawabModel->getById($id);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
        }

        $this->PenanggungJawabModel->update($id, [
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp'),
            'wilayah_id' => $this->input->post('wilayah_id')
        ]);

        $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil diupdate.');
        redirect('dashboard/pj/view');
    }

    public function delete($id) {
        $this->PenanggungJawabModel->delete($id);
        $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil dihapus.');
        redirect('dashboard/pj/view');
    }
}
