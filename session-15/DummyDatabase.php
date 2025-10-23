<?php

class DummyDatabase {
    private static $instance = null; // Instance Singleton
    private $data = []; // Data umum
    private $bookings = []; // Data booking
    private $rooms = []; // Data kamar
    private $users = []; // Data user

    private function __construct() {
        echo "Dummy Database berhasil diinisialisasi (menggunakan array).\n";
        $this->initializeData();
    }

    // Mendapatkan instance database (Singleton Pattern)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DummyDatabase();
        }
        return self::$instance;
    }

    // Inisialisasi data awal (kamar dummy)
    private function initializeData() {
        // Inisialisasi data kamar dummy
        $this->rooms = [
            ['number' => 101, 'type' => 'standard', 'status' => 'available'],
            ['number' => 102, 'type' => 'standard', 'status' => 'available'],
            ['number' => 201, 'type' => 'deluxe', 'status' => 'available'],
            ['number' => 202, 'type' => 'deluxe', 'status' => 'available'],
            ['number' => 301, 'type' => 'suite', 'status' => 'available'],
        ];
    }

    // Menyimpan data booking
    public function save($data) {
        // Simpan data booking ke array
        $this->bookings[] = $data;
        echo "✓ Data disimpan ke dummy database: Booking ID " . $data['id'] . "\n";
        return true;
    }

    // Mendapatkan semua data booking
    public function getAllBookings() {
        return $this->bookings;
    }

    // Mendapatkan booking berdasarkan ID
    public function getBookingById($id) {
        foreach ($this->bookings as $booking) {
            if ($booking['id'] === $id) {
                return $booking;
            }
        }
        return null;
    }

    // Mendapatkan semua kamar
    public function getAllRooms() {
        return $this->rooms;
    }

    // Mendapatkan kamar yang tersedia (optional: filter by type)
    public function getAvailableRooms($type = null) {
        if ($type === null) {
            return array_filter($this->rooms, function($room) {
                return $room['status'] === 'available';
            });
        }
        
        return array_filter($this->rooms, function($room) use ($type) {
            return $room['status'] === 'available' && $room['type'] === $type;
        });
    }

    // Mendapatkan kamar berdasarkan nomor
    public function getRoomByNumber($number) {
        foreach ($this->rooms as $room) {
            if ($room['number'] === $number) {
                return $room;
            }
        }
        return null;
    }

    // Update status kamar (available/occupied)
    public function updateRoomStatus($roomNumber, $status) {
        foreach ($this->rooms as &$room) {
            if ($room['number'] === $roomNumber) {
                $room['status'] = $status;
                echo "✓ Status kamar #" . $roomNumber . " diubah menjadi: " . $status . "\n";
                return true;
            }
        }
        return false;
    }

    // Menyimpan data user
    public function saveUser($user) {
        $this->users[] = $user;
        echo "✓ User data disimpan: " . $user['email'] . "\n";
        return true;
    }

    // Mendapatkan user berdasarkan email
    public function getUserByEmail($email) {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    // Mendapatkan semua user
    public function getAllUsers() {
        return $this->users;
    }

    // Mendapatkan statistik sistem
    public function getStatistics() {
        return [
            'total_bookings' => count($this->bookings),
            'total_rooms' => count($this->rooms),
            'available_rooms' => count($this->getAvailableRooms()),
            'occupied_rooms' => count(array_filter($this->rooms, function($room) {
                return $room['status'] === 'occupied';
            })),
            'total_users' => count($this->users)
        ];
    }

    // Menampilkan semua data untuk debugging
    public function showAllData() {
        echo "\n=== DUMMY DATABASE CONTENT ===\n";
        echo "Total Bookings: " . count($this->bookings) . "\n";
        echo "Total Rooms: " . count($this->rooms) . "\n";
        
        if (!empty($this->bookings)) {
            echo "\nBookings:\n";
            foreach ($this->bookings as $booking) {
                echo "  - " . $booking['id'] . ": " . $booking['customer_name'] . 
                     " (" . $booking['room_type'] . ")\n";
            }
        }
        
        echo "\nRooms:\n";
        foreach ($this->rooms as $room) {
            echo "  - #" . $room['number'] . " (" . $room['type'] . "): " . 
                 $room['status'] . "\n";
        }
        echo "==============================\n";
    }
}

?>
