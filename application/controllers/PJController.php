<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PJController extends MY_Controller
{
  
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenanggungJawabModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->library('image_lib'); 
        $this->load->helper(['url', 'form']);
        $this->load->database(); 
    }

    // Fungsi untuk generate UUID
    private function generate_uuid() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    // Fungsi untuk mengupload file
    private function _upload_file($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === 4) {
            return null;
        }

        $config['upload_path'] = FCPATH . 'uploads/pj/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = '2048'; // max upload 2MB, nanti dikompres lagi
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        // Generate nama file unik (hash/uuid)
        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $new_name = 'foto_kk_' . md5(uniqid(rand(), true)) . '.' . strtolower($ext);
        $config['file_name'] = $new_name;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            return null;
        }

        $upload_data = $this->upload->data();

        // Kompres gambar jika jpg/jpeg/png
        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
            $this->load->library('image_lib');
            $img_config['image_library'] = 'gd2';
            $img_config['source_image'] = $upload_data['full_path'];
            $img_config['quality'] = '70%'; 
            $img_config['overwrite'] = TRUE;

            // Resize jika lebih dari 512KB
            if (filesize($upload_data['full_path']) > 512 * 1024) {
                $img_config['maintain_ratio'] = TRUE;
            }

            $this->image_lib->initialize($img_config);
            $this->image_lib->resize();
            $this->image_lib->clear();

            // Jika masih lebih dari 512KB, turunkan kualitas lagi
            clearstatcache();
            if (filesize($upload_data['full_path']) > 512 * 1024) {
                $img_config['quality'] = '50%';
                $this->image_lib->initialize($img_config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            }
        }

        return $upload_data['file_name'];
    }

    // Fungsi untuk memeriksa kelekapan data di Penanggung Jawab
    public function verifikasi_pj()
    {
        $data['title'] = "Lengkapi Data Penanggung Jawab | SIPANDU Nuansa Utama";
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            return;
        }
        $this->load->view('dashboard/index_pj', $data);
    }

    public function index()
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            redirect('dashboard/pj/validation');
            return;
        }
        $is_complete = $this->PenanggungJawabModel->is_profile_complete($pj_id);
        if (!$is_complete) {
            redirect('dashboard/penghuni/viewpj');
            return;
        }
        $pj = $this->PenanggungJawabModel->getById($pj_id);
        if ($pj) {
            redirect('dashboard/pj/edit/' . $pj->uuid);
            return;
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/pj/validation');
            return;
        }
    }

    // Fungsi untuk mengedit data Penanggung Jawab di session Penanggung Jawab
    public function edit_pj()
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            redirect('dashboard');
            return;
        }

        $pj = $this->PenanggungJawabModel->getById($pj_id);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/pj/editdata');
            return;
        }
        $data['pj'] = $pj;
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['anggota_keluarga'] = $this->PenanggungJawabModel->getAnggotaKeluarga($pj_id);
        $data['title'] = "Profil Data | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_edit', $data);
        return;
    }

    public function update_pj($uuid)
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            redirect('dashboard');
            return;
        }

        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required');
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[LAKI - LAKI,PEREMPUAN]');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat_maps', 'Alamat Maps', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('status_rumah', 'Status Rumah', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/pj/editdata');
            return;
        }

        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/editdata');
            return;
        }

        if ($this->input->post('username')) {
            $this->db->where('id', $pj->user_id);
            $this->db->update('users', ['username' => $this->input->post('username')]);
        }

        if ($this->input->post('password')) {
            $this->db->where('id', $pj->user_id);
            $this->db->update('users', ['password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)]);
        }

        $foto_kk = $pj->foto_kk;
        $foto_kk_baru = $this->_upload_file('foto_kk');
        if ($foto_kk_baru) {
            $foto_kk = $foto_kk_baru;
        }

        $this->PenanggungJawabModel->update($pj->id, [
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $anggota_keluarga = json_decode($this->input->post('anggota_keluarga'), true);
        if ($anggota_keluarga !== null) {
            // Hapus semua anggota lama, walaupun array kosong
            $this->db->where('penanggung_jawab_id', $pj->id)->delete('anggota_keluarga');
            if (is_array($anggota_keluarga) && count($anggota_keluarga) > 0) {
                foreach ($anggota_keluarga as $anggota) {
                    $this->db->insert('anggota_keluarga', [
                        'uuid' => $this->generate_uuid(),
                        'penanggung_jawab_id' => $pj->id,
                        'nik_anggota' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => $anggota['tempat_lahir'],
                        'tanggal_lahir' => $anggota['tanggal_lahir'],
                        'jenis_kelamin' => $anggota['jenis_kelamin'],
                        'hubungan' => $anggota['hubungan'],
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        // Cek kelengkapan data setelah update
        if ($this->PenanggungJawabModel->is_profile_complete($pj->id)) {
            $this->session->set_flashdata('success', 'Data berhasil diupdate.');
            redirect('dashboard/penghuni/viewpj');
            return;
        } else {
            $this->session->set_flashdata('success', 'Data berhasil diupdate, silakan lengkapi data jika masih ada yang kurang.');
            redirect('dashboard/pj/editdata');
            return;
        }
    }

    // Fungsi untuk tampilan data Penanggung Jawab di Admin dan Kaling
    public function view()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['title'] = "Data Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['pj'] = $this->PenanggungJawabModel->getAllPJ();
        $this->load->view('pj/pj_list_admin', $data);
    }

    // Fungsi untuk tambah data Penanggung Jawab di Admin dan Kaling
    public function create()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('pj/pj_create_admin', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required|is_unique[penanggung_jawab.no_kk]');
        $this->form_validation->set_rules('nik', 'NIK', 'required|is_unique[penanggung_jawab.nik]');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[LAKI - LAKI,PEREMPUAN]');
        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[penanggung_jawab.email]');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat_maps', 'Alamat Maps', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('status_rumah', 'Status Rumah', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
            $data['wilayah'] = $this->WilayahModel->get_all();
            $data['error'] = validation_errors();
            $this->load->view('pj/pj_create', $data);
            return;
        }

        if (!isset($_FILES['foto_kk']) || $_FILES['foto_kk']['error'] === 4) {
            $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
            $data['wilayah'] = $this->WilayahModel->get_all();
            $data['error'] = 'Foto KK wajib diupload';
            $this->load->view('pj/pj_create', $data);
            return;
        }

        $this->db->trans_begin();
        
        $foto_kk = $this->_upload_file('foto_kk');
        if (!$foto_kk) {
            $this->db->trans_rollback();
            $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
            $data['wilayah'] = $this->WilayahModel->get_all();
            $data['error'] = $this->upload->display_errors();
            $this->load->view('pj/pj_create', $data);
            return;
        }

        $user_data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Penanggung Jawab',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();
        
        if (!$user_id) {
            $this->db->trans_rollback();
            $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
            $data['wilayah'] = $this->WilayahModel->get_all();
            $data['error'] = 'Gagal membuat akun pengguna.';
            $this->load->view('pj/pj_create', $data);
            return;
        }

        $pj_data = [
            'uuid' => $this->generate_uuid(),
            'user_id' => $user_id,
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('penanggung_jawab', $pj_data);
        $pj_id = $this->db->insert_id();
        
        if (!$pj_id) {
            $this->db->trans_rollback();
            $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
            $data['wilayah'] = $this->WilayahModel->get_all();
            $data['error'] = 'Gagal menambahkan data Penanggung Jawab.';
            $this->load->view('pj/pj_create', $data);
            return;
        }

        $anggota_keluarga = $this->input->post('anggota_keluarga');
        if ($anggota_keluarga !== null && $anggota_keluarga !== '') {
            $anggota_keluarga_arr = json_decode($anggota_keluarga, true);
            $this->db->where('penanggung_jawab_id', $pj_id)->delete('anggota_keluarga');
            if (is_array($anggota_keluarga_arr) && count($anggota_keluarga_arr) > 0) {
                $niks = [];
                foreach ($anggota_keluarga_arr as $anggota) {
                    if (in_array($anggota['nik'], $niks)) {
                        continue; 
                    }
                    $niks[] = $anggota['nik'];
                    $this->db->insert('anggota_keluarga', [
                        'uuid' => $this->generate_uuid(),
                        'penanggung_jawab_id' => $pj_id,
                        'nik_anggota' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => $anggota['tempat_lahir'],
                        'tanggal_lahir' => $anggota['tanggal_lahir'],
                        'jenis_kelamin' => $anggota['jenis_kelamin'],
                        'hubungan' => $anggota['hubungan'],
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil ditambahkan.');
        redirect('dashboard/pj/view');
    }

    // Fungsi untuk mengedit data Penanggung Jawab di Admin dan Kaling
    public function edit($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
            return;
        }
        
        $data['pj'] = $pj;
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['anggota_keluarga'] = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $data['title'] = "Edit Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_edit_admin', $data);
    }

    public function update($uuid)
    {
        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required');
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[LAKI - LAKI,PEREMPUAN]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('alamat_maps', 'Alamat Maps', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('status_rumah', 'Status Rumah', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/pj/edit/' . $uuid);
            return;
        }

        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
            return;
        }

        if ($this->input->post('username')) {
            $this->db->where('id', $pj->user_id);
            $this->db->update('users', ['username' => $this->input->post('username')]);
        }

        if ($this->input->post('password')) {
            $this->db->where('id', $pj->user_id);
            $this->db->update('users', ['password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)]);
        }

        $foto_kk = $pj->foto_kk;
        $foto_kk_baru = $this->_upload_file('foto_kk');
        if ($foto_kk_baru) {
            $foto_kk = $foto_kk_baru;
        }

        $this->PenanggungJawabModel->update($pj->id, [
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $anggota_keluarga = json_decode($this->input->post('anggota_keluarga'), true);
        if ($anggota_keluarga !== null) {
            $this->db->where('penanggung_jawab_id', $pj->id)->delete('anggota_keluarga');
            if (is_array($anggota_keluarga) && count($anggota_keluarga) > 0) {
                foreach ($anggota_keluarga as $anggota) {
                    $this->db->insert('anggota_keluarga', [
                        'uuid' => $this->generate_uuid(),
                        'penanggung_jawab_id' => $pj->id,
                        'nik_anggota' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => $anggota['tempat_lahir'],
                        'tanggal_lahir' => $anggota['tanggal_lahir'],
                        'jenis_kelamin' => $anggota['jenis_kelamin'],
                        'hubungan' => $anggota['hubungan'],
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil diupdate.');
        redirect('dashboard/pj/view');
    }

    // Fungsi untuk menghapus data Penanggung Jawab di Admin dan Kaling
    public function delete($uuid)
    {
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if ($pj) {
            $this->PenanggungJawabModel->delete($pj->id);
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        }
        redirect('dashboard/pj/view');
    }

    public function getPJLocation()
    {
        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Session not found'
            ]);
            return;
        }

        $pj = $this->db->select('p.*')
                       ->from('penanggung_jawab p')
                       ->join('users u', 'p.user_id = u.id')
                       ->where('p.user_id', $user_id)
                       ->get()
                       ->row();

        if (!$pj || !$pj->latitude || !$pj->longitude || !$pj->alamat_detail) {
            echo json_encode([
                'success' => false,
                'message' => 'Location data not found'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'latitude' => $pj->latitude,
                'longitude' => $pj->longitude,
                'alamat' => $pj->alamat_detail,
                'alamat_maps' => $pj->alamat_maps
            ]
        ]);
    }

    public function detail_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            show_404();
        }
        $anggota_keluarga = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $data['pj'] = $pj;
        $data['anggota_keluarga'] = $anggota_keluarga;
        $data['title'] = "Detail Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_details_admin', $data);
    }

}
