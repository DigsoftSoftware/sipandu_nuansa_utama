<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model(['AdminModel', 'PenghuniModel']);
    }
    public function index() {
        $data['title'] = "Dashboard | SIPANDU Nuansa Utama";
        $data['total_admin'] = $this->AdminModel->countAdmins();
        $data['total_kaling'] = $this->AdminModel->countKaling();
        $data['total_wilayah'] = $this->AdminModel->countWilayah();
        $data['total_users'] = $this->AdminModel->countUsers();
        $data['penghuni_baru'] = $this->PenghuniModel->getPenghuniBaruCoordinates();

        $this->load->view('admin/dashboard_admin', $data);
    }
}
