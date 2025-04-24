<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuratModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function simpan_surat_izin_tinggal($data) {
        return $this->db->insert('surat_izin_tinggal', $data);
    }

    public function simpan_surat_pernyataan($data) {
        return $this->db->insert('surat_pernyataan', $data);
    }

    public function get_surat_izin_tinggal($id = null) {
        if ($id === null) {
            return $this->db->get('surat_izin_tinggal')->result();
        }
        return $this->db->get_where('surat_izin_tinggal', ['id' => $id])->row();
    }

    public function get_surat_pernyataan($id = null) {
        if ($id === null) {
            return $this->db->get('surat_pernyataan')->result();
        }
        return $this->db->get_where('surat_pernyataan', ['id' => $id])->row();
    }
}