<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->library('session');
    }

    public function index() {
        $data['title'] = 'Laporan | SIPANDU NUANSA UTAMA';
        $this->load->view('document/laporan_views', $data);
    }

    public function report_pj() {
        $data['pendatang_per_pj'] = $this->PenghuniModel->getJumlahPendatangPerPJ();
        $data['total_pendatang'] = array_sum(array_column($data['pendatang_per_pj'], 'jumlah_pendatang'));
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        
        $dompdf = new Dompdf($options);
        $html = $this->load->view('document/cetak_penghuni_pj', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('LAP_PERPJ.pdf', ['Attachment' => 0]);
    }

    public function report_pendatang() {
  
        $wilayah_id = $this->session->userdata('wilayah_id');
        
    
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');
        

        $wilayah = $this->WilayahModel->get_By_Id($wilayah_id);
        if (!$wilayah) {
            $this->session->set_flashdata('error', 'Data wilayah tidak ditemukan');
            redirect('dashboard/laporan');
            return;
        }

   
        $kaling = $this->KalingModel->getByWilayahId($wilayah_id);
        if (!$kaling) {
            $this->session->set_flashdata('error', 'Data Kepala Lingkungan belum ditambahkan untuk wilayah '.$wilayah->wilayah.'. Silahkan hubungi admin.');
            redirect('dashboard/laporan');
            return;
        }

        $data['pendatang_per_tujuan'] = $this->PenghuniModel->getJumlahPendatangPerTujuan();
        $data['total_pendatang'] = array_sum(array_column($data['pendatang_per_tujuan'], 'jumlah'));
        $data['tanggal'] = date('d F Y');
        $data['wilayah'] = $wilayah;
        $data['kaling'] = $kaling;
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        
        $dompdf = new Dompdf($options);
        $html = $this->load->view('document/cetak_tujuan', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('LAP_TUJUAN.pdf', ['Attachment' => 0]);
    }
}