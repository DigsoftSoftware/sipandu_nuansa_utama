<?php
class WilayahModel extends CI_Model {
    public function get_all() {
        return $this->db->get('wilayah')->result();
    }
    public function get_by_id($id) {
        return $this->db->get_where('wilayah', ['id' => $id])->row();
    }

    public function insert($data) {
        $this->db->insert('wilayah', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('wilayah', $data);
    }

    public function delete($id) {
        $this->db->where('wilayah_id', $id);
        $kaling_count = $this->db->count_all_results('kaling');
        
        $this->db->where('wilayah_id', $id);
        $pj_count = $this->db->count_all_results('penanggung_jawab');
        
        $this->db->where('wilayah_id', $id);
        $penghuni_count = $this->db->count_all_results('penghuni');

        if ($kaling_count > 0 || $pj_count > 0 || $penghuni_count > 0) {
            return false;
        }

        $this->db->where('id', $id);
        return $this->db->delete('wilayah');
    }
}
