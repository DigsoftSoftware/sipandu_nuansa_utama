<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->load->model('WilayahModel');
        $data['title'] = 'Laporan | SIPANDU NUANSA UTAMA';
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('document/laporan_views', $data);
    }

    public function report_pj() {
        $data['penghuni_data'] = $this->PenghuniModel->getPendatangByPJAndKaling();
        $data['tanggal'] = date('d F Y');
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        
        $dompdf = new Dompdf($options);
        $html = $this->load->view('document/cetak_laporan_detail_pj', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('LAP_DETAIL_PJ.pdf', ['Attachment' => 0]);
    }

    public function report_pendatang($wilayah_id = null) {
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');

        $data['pendatang_per_tujuan'] = $this->PenghuniModel->getJumlahPendatangPerTujuanByWilayah($wilayah_id);
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