<?php

// Class untuk entitas User
class User {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $password;
    private $createdAt;

    public function __construct($name, $email, $phone, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->createdAt = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getPassword() { return $this->password; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setPhone($phone) { $this->phone = $phone; }

    // Verifikasi password
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    // Update password
    public function updatePassword($newPassword) {
        $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    // Convert ke array (untuk database)
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'created_at' => $this->createdAt
        ];
    }

    // Create dari array (dari database)
    public static function fromArray($data) {
        $user = new self($data['name'], $data['email'], $data['phone'], '');
        $user->setId($data['id']);
        $user->password = $data['password']; // Set directly (sudah hashed)
        $user->createdAt = $data['created_at'];
        return $user;
    }

    // Display info
    public function displayInfo() {
        echo "\n=== USER INFO ===\n";
        echo "ID: " . $this->id . "\n";
        echo "Name: " . $this->name . "\n";
        echo "Email: " . $this->email . "\n";
        echo "Phone: " . $this->phone . "\n";
        echo "Member since: " . $this->createdAt . "\n";
        echo "=================\n";
    }
}

?>
