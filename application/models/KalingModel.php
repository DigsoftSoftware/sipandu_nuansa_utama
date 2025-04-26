<?php
class KalingModel extends CI_Model {
    
    public function getAll() {
        $this->db->select('kaling.*, wilayah.wilayah as wilayah, users.username as username');
        $this->db->from('kaling');
        $this->db->join('wilayah', 'wilayah.id = kaling.wilayah_id', 'left');
        $this->db->join('users', 'users.id = kaling.user_id', 'left');
        return $this->db->get()->result();
    }

    public function getById($id) {
        return $this->db->get_where('kaling', ['id' => $id])->row();
    }

    public function getByWilayahId($wilayah_id) {
        return $this->db->get_where('kaling', ['wilayah_id' => $wilayah_id])->row();
    }

    public function getUserId($id) {
        $this->db->select('kaling.*, users.username');
        $this->db->from('kaling');
        $this->db->join('users', 'users.id = kaling.user_id');
        $this->db->where('kaling.id', $id);
        return $this->db->get()->row();
    }
    
    public function insert($data) {
        $this->db->insert('kaling', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('kaling', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('kaling');
    }
}
