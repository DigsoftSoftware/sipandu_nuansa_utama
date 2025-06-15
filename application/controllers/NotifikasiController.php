<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotifikasiController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('Pusher_lib'); 
    }

    public function kirim_notifikasi() {
        $this->db->select('uuid, no_surat, status_proses, penghuni_id, anggota_keluarga_id');
        $this->db->group_start();
        $this->db->where('status_proses', 'Diproses');
        $this->db->where('notifikasi', 0);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where_in('status_proses', ['Diterima', 'Ditolak']);
        $this->db->where('notifikasi', 1);
        $this->db->group_end();
        $data = $this->db->get('surat')->result();

        $this->Pusher_lib->trigger('notifikasi-surat', 'status-update', ['data' => $data]);
    }
}