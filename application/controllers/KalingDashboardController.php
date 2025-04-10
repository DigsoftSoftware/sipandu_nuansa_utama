<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KalingDashboardController extends CI_Controller {
    public function index() {
        $data['title'] = "Dashboard Kaling - SIPANDU Nuansa Utama";
        $this->load->view('kaling/dashboard_kaling', $data);
    }
}
