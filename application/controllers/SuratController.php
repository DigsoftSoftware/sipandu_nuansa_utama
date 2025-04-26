<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class SuratController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan', 'Penanggung Jawab']);
        $this->load->model('PenghuniModel', 'penghuni');
        $this->load->model('WilayahModel', 'wilayah');
        $this->load->model('KalingModel', 'kaling');
        $this->load->library('session');
    }

    private function generateUniqueCode($data) {
        $string = $data->nama_lengkap . $data->nik . time();
        return substr(md5($string), 0, 16);
    }

    public function index() {
        $data['title'] = 'Surat - Surat | SIPANDU NUANSA UTAMA';
        $data['penghuni'] = array_filter($this->penghuni->getAll(), function($p) {
            return $p->status_verifikasi === 'Diterima';
        });
        $this->load->view('document/surat_views', $data);
    }

    public function generate_surat_izin_tinggal($uuid) {
        $penghuni = $this->penghuni->getByUuid($uuid);
        if (!$penghuni || $penghuni->status_verifikasi !== 'Diterima') {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan atau belum diverifikasi');
            redirect('dashboard/surat/view');
            return;
        }
        
        $wilayah = $this->wilayah->get_by_id($penghuni->wilayah_id);
        if (!$wilayah) {
            $this->session->set_flashdata('error', 'Data wilayah tidak ditemukan');
            redirect('dashboard/surat/view');
            return;
        }

        $kaling = $this->kaling->getByWilayahId($wilayah->id);
        if (!$kaling) {
            $this->session->set_flashdata('error', 'Data Kepala Lingkungan belum ditambahkan untuk wilayah '.$wilayah->wilayah.'. Silahkan hubungi admin untuk menambahkan data Kepala Lingkungan.');
            redirect('dashboard/surat/view');
            return;
        }
        
        $data = [
            'nomor_surat' =>'SIT/'.date('dmY'),
            'penghuni' => $penghuni,
            'wilayah' => $wilayah,
            'kaling' => $kaling,
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/cetak_ijin', $data, true);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("surat_izin_tinggal_".$penghuni->nama_lengkap.".pdf", array("Attachment" => false));
    }

    public function generate_surat_pernyataan($uuid) {
        $penghuni = $this->penghuni->getByUuid($uuid);
        if (!$penghuni || $penghuni->status_verifikasi !== 'Diterima') {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan atau belum diverifikasi');
            redirect('dashboard/surat/view');
            return;
        }
        
        $wilayah = $this->wilayah->get_by_id($penghuni->wilayah_id);
        if (!$wilayah) {
            $this->session->set_flashdata('error', 'Data wilayah tidak ditemukan');
            redirect('dashboard/surat/view');
            return;
        }

        $uniqueCode = $this->generateUniqueCode($penghuni);
        
        $data = [
            'nomor_surat' =>'SP/'.date('dmY'),
            'penghuni' => $penghuni,
            'wilayah' => $wilayah,
            'tanggal' => date('d F Y')
        ];

        $html = $this->load->view('document/cetak_pernyataan', $data, true);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("surat_pernyataan_".$penghuni->nama_lengkap.".pdf", array("Attachment" => false));
    }
}