<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
    }
    public function index() {
        $data['title'] = "Dashboard | SIPANDU Nuansa Utama";
        $this->load->view('admin/dashboard_admin', $data);
    }
}
