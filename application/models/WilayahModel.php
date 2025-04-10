<?php
class WilayahModel extends CI_Model {
    public function get_all() {
        return $this->db->get('wilayah')->result();
    }
}
