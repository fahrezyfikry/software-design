<?php

require_once 'DummyDatabase.php';

class UserService {
    private $db; // Instance database (Singleton)
    private $currentUser = null; // User yang sedang login

    public function __construct() {
        $this->db = DummyDatabase::getInstance();
    }

    // Mendaftarkan user baru
    public function register($name, $email, $phone, $password) {
        $user = [
            'id' => 'USR' . rand(1000, 9999),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->saveUser($user);
        echo "✓ User berhasil terdaftar: " . $name . " (" . $email . ")\n";
        return $user;
    }

    // Login user dengan email dan password
    public function login($email, $password) {
        $user = $this->db->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $this->currentUser = $user;
            echo "✓ Login berhasil: " . $user['name'] . "\n";
            return $user;
        }
        
        echo "✗ Login gagal: Email atau password salah\n";
        return null;
    }

    // Logout user yang sedang login
    public function logout() {
        if ($this->currentUser) {
            echo "✓ User " . $this->currentUser['name'] . " telah logout\n";
            $this->currentUser = null;
        }
    }

    // Mendapatkan data user yang sedang login
    public function getCurrentUser() {
        return $this->currentUser;
    }

    // Mengecek apakah user sudah login
    public function isLoggedIn() {
        return $this->currentUser !== null;
    }

    // Update profile user yang sedang login
    public function updateProfile($name, $phone) {
        if (!$this->isLoggedIn()) {
            echo "✗ Anda harus login terlebih dahulu\n";
            return false;
        }

        $this->currentUser['name'] = $name;
        $this->currentUser['phone'] = $phone;
        echo "✓ Profile berhasil diupdate\n";
        return true;
    }
}

?>
