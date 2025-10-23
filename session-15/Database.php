<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Simulasi koneksi database
        // Dalam implementasi nyata, gunakan PDO dengan kredensial yang benar
        try {
            $this->connection = new PDO("mysql:host=localhost;dbname=hotel", "root", "");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Koneksi database berhasil dibuat.\n";
        } catch (PDOException $e) {
            // Untuk demo, kita gunakan array sebagai pengganti database
            echo "Menggunakan simulasi database (array).\n";
            $this->connection = null;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Metode untuk simulasi penyimpanan data
    public function save($data) {
        echo "Data disimpan ke database: " . json_encode($data) . "\n";
        return true;
    }
}

?>
