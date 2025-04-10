<?php
class UserModel extends CI_Model {
    public function get_by_username($username) {
        return $this->db->get_where('users', ['username' => $username])->row();
    }

    public function insert_admin($data) {
        return $this->db->insert('admin', $data);
    }

    public function insert_kaling($data) {
        return $this->db->insert('kaling', $data);
    }

    public function insert_pj($data) {
        return $this->db->insert('pj', $data);
    }
}
