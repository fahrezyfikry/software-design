<?php

require_once 'DummyDatabase.php';
require_once 'User.php';

class UserService {
    private $db; // Instance database (Singleton)
    private $currentUser = null; // User yang sedang login

    public function __construct() {
        $this->db = DummyDatabase::getInstance();
    }

    // Mendaftarkan user baru
    public function register($name, $email, $phone, $password) {
        // Buat object User
        $user = new User($name, $email, $phone, $password);
        $user->setId('USR' . rand(1000, 9999));
        
        // Simpan ke database
        $this->db->saveUser($user->toArray());
        echo "✓ User berhasil terdaftar: " . $name . " (" . $email . ")\n";
        return $user;
    }

    // Login user dengan email dan password
    public function login($email, $password) {
        $userData = $this->db->getUserByEmail($email);
        
        if ($userData) {
            $user = User::fromArray($userData);
            if ($user->verifyPassword($password)) {
                $this->currentUser = $user;
                echo "✓ Login berhasil: " . $user->getName() . "\n";
                return $user;
            }
        }
        
        echo "✗ Login gagal: Email atau password salah\n";
        return null;
    }

    // Logout user yang sedang login
    public function logout() {
        if ($this->currentUser) {
            echo "✓ User " . $this->currentUser->getName() . " telah logout\n";
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

        $this->currentUser->setName($name);
        $this->currentUser->setPhone($phone);
        echo "✓ Profile berhasil diupdate\n";
        return true;
    }
}

?>
