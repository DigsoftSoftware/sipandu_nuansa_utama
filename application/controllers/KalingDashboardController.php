<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KalingDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Kepala Lingkungan']);
    }
    public function index() {
        $data['title'] = "Dashboard Kaling | SIPANDU Nuansa Utama";
        $this->load->view('kaling/dashboard_kaling', $data);
    }
}
