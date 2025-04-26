<?php
class AdminModel extends CI_Model {

    public function getAll() {
        $this->db->select('admin.id, admin.nama, users.username');
        $this->db->from('admin');
        $this->db->join('users', 'users.id = admin.user_id');
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('admin.id AS id, admin.nama, users.username, users.password, users.id AS user_id');
        $this->db->from('admin');
        $this->db->join('users', 'users.id = admin.user_id');
        $this->db->where('admin.id', $id);
        return $this->db->get()->row();
    }
      
    public function countAdmins() {
        return $this->db->count_all('admin');
    }
    
    public function countKaling() {
        return $this->db->count_all('kaling');
    }
    
    public function countWilayah() {
        return $this->db->count_all('wilayah');
    }
    
    public function countUsers() {
        return $this->db->count_all('penghuni');
    }

    
    public function insertUser($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function insertAdmin($data) {
        return $this->db->insert('admin', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('admin', $data);
    }

    public function delete($id) {
        $admin = $this->getById($id);
        $this->db->delete('admin', ['id' => $id]);
        $this->db->delete('users', ['id' => $admin->user_id]);
    }
}
