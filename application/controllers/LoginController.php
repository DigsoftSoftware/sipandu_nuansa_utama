<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {
	public function login()
	{
        $data['title'] = "Login - SIPADU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('auth/login_views');
        $this->load->view('partials/footer');
	}
	public function daftarKaling()
	{
        $data['title'] = "Daftar Kaling - SIPADU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('auth/register_views_kaling');
        $this->load->view('partials/footer');
	}
	public function daftarPJ()
	{
        $data['title'] = "Daftar Penanggung Jawab - SIPADU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('auth/register_views_pj');
        $this->load->view('partials/footer');
	}
}
