<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenghuniModel extends CI_Model {

    private $table = 'penghuni';

    public function getAll() {
        return $this->db->get('penghuni')->result();
    }

    public function getByStatus($status) {
        if (is_array($status)) {
            $this->db->where_in('status_verifikasi', $status);
        } else {
            $this->db->where('status_verifikasi', $status);
        }

        return $this->db->get($this->table)->result();
    }

    public function getByPJ($pj_id) {
        return $this->db->get_where($this->table, ['penanggung_jawab_id' => $pj_id])->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('penghuni', ['id' => $id])->row();
    }


    public function insert($data) {
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
}
