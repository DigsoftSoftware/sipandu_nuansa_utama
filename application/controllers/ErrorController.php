<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ErrorController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Fungsi untuk menampilkan halaman error 404
    // Halaman ini akan ditampilkan jika URL yang diminta tidak ditemukan
    public function error_404()
    {
        $this->output->set_status_header(404);
        $data['title'] = "404 | Halaman Tidak Ditemukan";
        $this->load->view('errors/custom/error_404', $data);
    }

    // Fungsi untuk menampilkan halaman error 403
    // Halaman ini akan ditampilkan jika akses ditolak
    // Misalnya, jika pengguna tidak memiliki izin untuk mengakses halaman tertentu
    public function error_403()
    {
        $this->output->set_status_header(403);
        $data['title'] = "403 | Akses Ditolak";
        $this->load->view('errors/custom/error_403', $data);
    }
}
