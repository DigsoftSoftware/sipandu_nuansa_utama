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
        $this->db->where('id', $id);
        $this->db->delete('wilayah');
    }
}
