<?php

require_once 'DummyDatabase.php';
require_once 'Room.php';

class RoomService {
    private $db; // Instance database (Singleton)

    public function __construct() {
        $this->db = DummyDatabase::getInstance();
    }

    // Mendapatkan semua kamar
    public function getAllRooms() {
        return $this->db->getAllRooms();
    }

    // Mendapatkan kamar yang tersedia (bisa filter berdasarkan tipe)
    public function getAvailableRooms($type = null) {
        $rooms = $this->db->getAvailableRooms($type);
        echo "Ditemukan " . count($rooms) . " kamar tersedia";
        if ($type) echo " dengan tipe: " . $type;
        echo "\n";
        return $rooms;
    }

    // Mendapatkan detail kamar berdasarkan nomor
    public function getRoomDetails($roomNumber) {
        return $this->db->getRoomByNumber($roomNumber);
    }

    // Update harga kamar berdasarkan tipe
    public function updateRoomPrice($type, $newPrice) {
        echo "✓ Harga kamar tipe " . $type . " diupdate menjadi Rp" . 
             number_format($newPrice, 0, ',', '.') . "\n";
        return true;
    }

    // Mendapatkan semua tipe kamar yang tersedia
    public function getRoomTypes() {
        return RoomFactory::getAvailableTypes();
    }

    // Menampilkan katalog semua kamar dengan harga dan ketersediaan
    public function displayRoomCatalog() {
        echo "\n=== KATALOG KAMAR HOTEL ===\n";
        $types = RoomFactory::getAvailableTypes();
        
        foreach ($types as $type) {
            $room = RoomFactory::createRoom($type);
            $available = count($this->getAvailableRooms($type));
            echo "• " . $room->getType() . "\n";
            echo "  Harga: Rp" . number_format($room->getPrice(), 0, ',', '.') . " /malam\n";
            echo "  Tersedia: " . $available . " kamar\n\n";
        }
        echo "===========================\n";
    }
}

?>
