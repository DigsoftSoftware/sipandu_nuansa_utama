<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboardController extends CI_Controller {
    public function index() {
        $data['title'] = "Dashboard - SIPANDU Nuansa Utama";
        $this->load->view('admin/dashboard_admin', $data);
    }
}
