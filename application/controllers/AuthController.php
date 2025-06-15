<?php
class AuthController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('UserModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->model('KalingModel');
        $this->load->model('AdminModel');
    }

    // Function untuk menampilkan halaman login
    public function login()
    {
        $data['title'] = "Login | SIPANDU NUANSA UTAMA";
        $this->load->view('auth/login_views', $data);
    }

    // Function untuk memproses login
    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->UserModel->get_by_username($username);

        if ($user) {

            if (!$this->_is_hashed($user->password)) {
                $hashed_password = password_hash($user->password, PASSWORD_BCRYPT);
                $this->UserModel->update_password($user->id, $hashed_password);
                $user->password = $hashed_password;
            }

            if (password_verify($password, $user->password)) {
                $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_userdata('role', $user->role);

                switch ($user->role) {
                    case 'Admin':
                        $admin = $this->AdminModel->getByUserId($user->id);
                        if ($admin) {
                            $this->session->set_userdata('nama', $admin->nama);
                        }
                        break;
            
                    case 'Kepala Lingkungan':
                        $kaling = $this->KalingModel->getByUserId($user->id);
                        if ($kaling) {
                            $this->session->set_userdata('nama', $kaling->nama);
                            $this->session->set_userdata('kaling_id', $kaling->id);
                        }
                        break;
            
                    case 'Penanggung Jawab':
                        $pj = $this->PenanggungJawabModel->getByUserId($user->id);
                        if ($pj) {
                            $this->session->set_userdata('nama', $pj->nama_pj);
                            $this->session->set_userdata('pj_id', $pj->id);
                        }
                        break;
                }

                $this->session->set_flashdata('success_login', 'Selamat datang di Dashboard SIPANDU, ' . $user->username . '!');

                if ($user->role === 'Admin') {
                    redirect('dashboard');
                } elseif ($user->role === 'Kepala Lingkungan') {
                    redirect('dashboard');
                } elseif ($user->role === 'Penanggung Jawab') {
                    redirect('dashboard/penghuni/viewpj');
                } else {
                    $this->session->set_flashdata('error', 'Akses Ditolak');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('error', 'Login gagal! Username atau password salah.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Login gagal! Username atau password salah.');
            redirect('auth');
        }
    }

    // Function untuk mengecek apakah password sudah di-hash
    private function _is_hashed($password)
    {
        return strlen($password) == 60 && preg_match('/^\$2[ayb]\$.{56}$/', $password);
    }

    // Function untuk logout
    public function logout()
    {
        $role = $this->session->userdata('role');

        switch ($role) {
            case 'Admin':
                $this->session->unset_userdata(['admin_logged_in', 'admin_id']);
                break;

            case 'Kepala Lingkungan':
                $this->session->unset_userdata(['kaling_logged_in', 'kaling_id']);
                break;

            case 'Penanggung Jawab':
                $this->session->unset_userdata(['pj_logged_in', 'pj_id']);
                break;
        }

        $this->session->unset_userdata('role');
        $this->session->set_flashdata('success', 'Anda berhasil logout.');
        redirect('auth');
    }
}
