<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenanggungJawabDashboardController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        
        $this->load->model(['PenanggungJawabModel', 'UserModel', 'WilayahModel']);
        
        $this->load->library(['form_validation', 'session']);
        
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $data['title'] = "Dashboard Penanggung Jawab - SIPANDU Nuansa Utama";
        $this->load->view('pj/dashboard_pj', $data);
    }
    
    public function view() {
        $data['title'] = "Data Penanggung Jawab";
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $this->load->view('pj/pj_list', $data);
    }

    public function create() {
        $data['title'] = "Tambah Penanggung Jawab";
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('pj/pj_create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/pj/create');
        }

        // Create user account first
        $user_data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role' => 'Penanggung Jawab'
        ];

        $user_id = $this->UserModel->create($user_data);

        if ($user_id) {
            // Create penanggung jawab data
            $pj_data = [
                'user_id' => $user_id,
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'no_hp' => $this->input->post('no_hp'),
                'alamat' => $this->input->post('alamat'),
                'wilayah_id' => $this->input->post('wilayah_id')
            ];

            $this->PenanggungJawabModel->create($pj_data);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan.');
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
        $data['title'] = "Edit Penanggung Jawab";
        $this->load->view('pj/pj_edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

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
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp')
        ]);

        $this->session->set_flashdata('success', 'Data berhasil diupdate.');
        redirect('dashboard/pj/view');
    }

    public function delete($id) {
        $this->PenanggungJawabModel->delete($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('dashboard/pj/view');
    }
}
