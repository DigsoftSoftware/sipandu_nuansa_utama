<?php
class PenanggungJawabModel extends CI_Model {

    public function getAll() {
        $this->db->select('pj.*, u.username');
        $this->db->from('penanggung_jawab pj');
        $this->db->join('users u', 'pj.user_id = u.id');
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('pj.*, u.username');
        $this->db->from('penanggung_jawab pj');
        $this->db->join('users u', 'pj.user_id = u.id');
        $this->db->where('pj.id', $id);
        return $this->db->get()->row();
    }

    public function getByUserId($user_id) {
        return $this->db->get_where('penanggung_jawab', ['user_id' => $user_id])->row();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('penanggung_jawab', $data);
    }

    public function delete($id) {
        $pj = $this->getById($id);
        if ($pj) {
            $this->db->delete('users', ['id' => $pj->user_id]); 
        }
    }

    public function create($data) {
        return $this->db->insert('penanggung_jawab', $data);
    }
}
