<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WilayahDaftarDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
    }

    public function view() {
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Data Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('admin/wilayah_list', $data);
    }

    public function create() {
        $data['title'] = "Tambah Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('admin/wilayah_create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('wilayah', 'Nama Wilayah', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/wilayah/create');
        }

        $this->WilayahModel->insert([
            'wilayah' => $this->input->post('wilayah'),
        ]);

        $this->session->set_flashdata('success', 'Wilayah berhasil ditambahkan!');
        redirect('dashboard/wilayah/view');
    }

    public function edit($id) {
        $data['wilayah'] = $this->WilayahModel->get_by_id($id);
        $data['title'] = "Edit Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('admin/wilayah_edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('wilayah', 'Nama Wilayah', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/wilayah/edit/' . $id);
        }

        $this->WilayahModel->update($id, [
            'wilayah' => $this->input->post('wilayah'),
        ]);

        $this->session->set_flashdata('success', 'Wilayah berhasil diperbarui!');
        redirect('dashboard/wilayah/view');
    }

    public function delete($id) {
        $this->WilayahModel->delete($id);
        $this->session->set_flashdata('success', 'Wilayah berhasil dihapus!');
        redirect('dashboard/wilayah/view');
    }
}
