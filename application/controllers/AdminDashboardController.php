<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin']);
    }
    public function index() {
        $data['title'] = "Dashboard Admin | SIPANDU Nuansa Utama";
        $this->load->view('admin/dashboard_admin', $data);
    }
}
