<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class SuratController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('PenghuniModel');
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');
    }

    public function index() {
        $data['title'] = 'Pilih Jenis Surat';
        $data['penghuni'] = $this->PenghuniModel->getAll();
        $this->load->view('document/pilih_surat', $data);
    }

    public function form_surat_izin_tinggal() {
        $data['title'] = 'Form Surat Izin Tinggal';
        $this->load->view('document/form_surat_izin_tinggal', $data);
    }

    public function form_surat_pernyataan() {
        $data['title'] = 'Form Surat Pernyataan';
        $this->load->view('document/form_surat_pernyataan', $data);
    }

    public function generate_surat_izin_tinggal($penghuni_id) {
        $penghuni = $this->PenghuniModel->getById($penghuni_id);
        if (!$penghuni) {
            show_404();
        }
        
        $wilayah = $this->WilayahModel->get_by_id($penghuni->wilayah_id);
        if (!$wilayah) {
            show_404();
        }
        
        $kaling = $this->KalingModel->getById($wilayah->kaling_id);
        if (!$kaling) {
            show_404();
        }
        
        $data = [
            'nomor_surat' => date('dmY').'/SIT/'.rand(100,999),
            'penghuni' => $penghuni,
            'wilayah' => $wilayah,
            'kaling' => $kaling,
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/surat_izin_tinggal_pdf', $data, true);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->render();
        
        $dompdf->stream("surat_izin_tinggal_".$penghuni->nama.".pdf", array("Attachment" => false));
    }

    public function generate_surat_pernyataan($penghuni_id) {
        $penghuni = $this->PenghuniModel->getById($penghuni_id);
        if (!$penghuni) {
            show_404();
        }
        
        $wilayah = $this->WilayahModel->get_by_id($penghuni->wilayah_id);
        if (!$wilayah) {
            show_404();
        }
        
        $data = [
            'nomor_surat' => date('dmY').'/SP/'.rand(100,999),
            'penghuni' => $penghuni,
            'wilayah' => $wilayah,
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/surat_pernyataan_pdf', $data, true);
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->render();
        
        $dompdf->stream("surat_pernyataan_".$penghuni->nama.".pdf", array("Attachment" => false));
    }
}