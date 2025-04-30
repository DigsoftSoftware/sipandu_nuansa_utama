<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenghuniModel extends CI_Model {

    private $table = 'penghuni';

    public function __construct() {
        parent::__construct();
        $this->load->helper('string');
    }

    private function generate_uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getAll() {
        return $this->db->get('penghuni')->result();
    }

    public function getByStatus($status) {
        $this->db->select('penghuni.*, penanggung_jawab.nama as pj_nama')
                 ->from($this->table)
                 ->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id');

        if (is_array($status)) {
            $this->db->where_in('status_verifikasi', $status);
        } else {
            $this->db->where('status_verifikasi', $status);
        }

        return $this->db->get()->result();
    }

    public function getByPJ($pj_id) {
        return $this->db->get_where($this->table, ['penanggung_jawab_id' => $pj_id])->result();
    }

    public function getById($id)
    {
        return $this->db->select('penghuni.*, kaling.nama as kaling_nama, penanggung_jawab.nama as pj_nama, wilayah.wilayah')
            ->from($this->table)
            ->join('kaling', 'kaling.id = penghuni.kaling_id')
            ->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id')
            ->join('wilayah', 'wilayah.id = penghuni.wilayah_id')
            ->where('penghuni.id', $id)
            ->get()
            ->row();
    }

    public function getByUuid($uuid) {
        return $this->db->get_where('penghuni', ['uuid' => $uuid])->row();
    }

    public function insert($data) {
        $data['uuid'] = $this->generate_uuid();
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function getAllWithRelations() {
        return $this->db->select('p.*, pj.nama as nama_pj, k.nama as nama_kaling, w.wilayah as nama_wilayah')
                        ->from('penghuni p')
                        ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
                        ->join('kaling k', 'k.id = p.kaling_id')
                        ->join('wilayah w', 'w.id = p.wilayah_id')
                        ->get()
                        ->result();
    }

    public function getJumlahPendatangPerPJ() {
        $result = $this->db->select('pj.id, pj.nama as nama_pj, COUNT(p.id) as jumlah_pendatang, GROUP_CONCAT(p.nama_lengkap SEPARATOR ", ") as nama_pendatang')
                        ->from('penghuni p')
                        ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
                        ->where('p.status_verifikasi', 'Diterima')
                        ->group_by('pj.id, pj.nama')
                        ->get()
                        ->result();
        return $result;
    }

    public function getJumlahPendatangPerTujuan() {
        return $this->db->select('tujuan, COUNT(id) as jumlah')
                        ->from($this->table)
                        ->where('status_verifikasi', 'Diterima')
                        ->group_by('tujuan')
                        ->get()
                        ->result();
    }

    public function getJumlahPendatangPerTujuanByWilayah($wilayah_id) {
        return $this->db->select('tujuan, COUNT(id) as jumlah')
                        ->from($this->table)
                        ->where('status_verifikasi', 'Diterima')
                        ->where('wilayah_id', $wilayah_id)
                        ->group_by('tujuan')
                        ->get()
                        ->result();
    }

    public function getPenghuniBaruCoordinates() {
        return $this->db->select('nama_lengkap as nama, latitude as lat, longitude as lng')
                        ->from($this->table)
                        ->where('status_verifikasi', 'Diterima')
                        ->where('latitude IS NOT NULL')
                        ->where('longitude IS NOT NULL')
                        ->get()
                        ->result();
    }

    public function getPendatangByPJAndKaling() {
        return $this->db->select('
            p.nama_lengkap,
            p.tujuan,
            p.tanggal_masuk,
            p.tanggal_keluar,
            pj.nama as nama_pj,
            pj.id as pj_id,
            k.nama as nama_kaling,
            k.id as kaling_id')
            ->from('penghuni p')
            ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
            ->join('kaling k', 'k.id = p.kaling_id')
            ->where('p.status_verifikasi', 'Diterima')
            ->order_by('pj.nama', 'ASC')
            ->order_by('k.nama', 'ASC')
            ->order_by('p.nama_lengkap', 'ASC')
            ->get()
            ->result();
    }
}
