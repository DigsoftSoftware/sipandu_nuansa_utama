<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenanggungJawabDashboardController extends CI_Controller {
    public function index() {
        $data['title'] = "Dashboard Penanggung Jawab - SIPANDU Nuansa Utama";
        $this->load->view('pj/dashboard_pj', $data);
    }
}
