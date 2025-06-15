<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('AdminModel');
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel');
    }
    
    // Fungsi untuk menampilkan halaman dashboard admin
    public function index()
    {
        $data['title'] = "Dashboard | SIPANDU Nuansa Utama";
        $data['total_admin'] = $this->AdminModel->countAdmins();
        $data['total_kaling'] = $this->AdminModel->countKaling();
        $data['total_warga'] = $this->AdminModel->countWarga();
        $data['total_users'] = $this->AdminModel->countUsers();
        $data['penghuni_baru'] = $this->PenghuniModel->getPenghuniBaruCoordinates();
        $data['data_kaling'] = $this->AdminModel->getAllKaling();
        $data['data_pj'] = $this->AdminModel->getAllPenanggungJawab();
        $data['data_warga'] = $this->AdminModel->getAllWarga();
        $data['data_pendatang'] = $this->AdminModel->getAllPendatang();
        $data['total_pj'] = $this->PenanggungJawabModel->countAll();
        $data['penghuni_verifikasi_bulanini'] = $this->PenghuniModel->get_verifikasi_bulanini();
        
        $this->load->view('dashboard/index_admin_views', $data);
    }
}
