<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {
    public function index() {
        $data['title'] = "Dashboard - SIPADU Nuansa Utama";
        $this->load->view('home/dashboard', $data);
    }
}
