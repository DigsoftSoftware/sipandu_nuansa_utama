<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenanggungJawabDashboardController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('PenanggungJawabModel');
        $this->load->model('UserModel');
        $this->load->library(['session', 'form_validation']);
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
