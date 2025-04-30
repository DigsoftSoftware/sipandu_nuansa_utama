<?php
class WilayahModel extends CI_Model {
    protected $table = 'wilayah';
    protected $primaryKey = 'id';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        if (!$id || !is_numeric($id)) {
            log_message('error', 'WilayahModel::get_by_id called with invalid ID: ' . $id);
            return null;
        }
        
        $result = $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
        if (!$result) {
            log_message('error', 'Wilayah not found with ID: ' . $id);
        }
        return $result;
    }

    public function insert($data) {
        $data = array(
            'wilayah' => $data['wilayah']
        );
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        
        $data = array(
            'wilayah' => $data['wilayah']
        );
        
        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        if (!$id || !is_numeric($id)) {
            return false;
        }

        $this->db->where('wilayah_id', $id);
        $kaling_count = $this->db->count_all_results('kaling');
        
        $this->db->where('wilayah_id', $id);
        $pj_count = $this->db->count_all_results('penanggung_jawab');
        
        $this->db->where('wilayah_id', $id);
        $penghuni_count = $this->db->count_all_results('penghuni');

        if ($kaling_count > 0 || $pj_count > 0 || $penghuni_count > 0) {
            return false;
        }

        $this->db->where($this->primaryKey, $id);
        return $this->db->delete($this->table);
    }
}
