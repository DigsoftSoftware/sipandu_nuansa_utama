<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenanggungJawabDashboardController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Penanggung Jawab']);
    }
    public function index() {
        $data['title'] = "Dashboard Penanggung Jawab - SIPANDU Nuansa Utama";
        $this->load->view('pj/dashboard_pj', $data);
    }
}
