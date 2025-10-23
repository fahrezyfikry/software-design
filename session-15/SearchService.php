<?php

require_once 'DummyDatabase.php';
require_once 'RoomService.php';

class SearchService {
    private $db; // Instance database (Singleton)
    private $roomService; // Service untuk akses data kamar

    public function __construct() {
        $this->db = DummyDatabase::getInstance();
        $this->roomService = new RoomService();
    }

    // Mencari kamar berdasarkan tipe
    public function searchByType($type) {
        echo "\n--- Mencari kamar tipe: " . $type . " ---\n";
        $rooms = $this->db->getAvailableRooms($type);
        
        if (empty($rooms)) {
            echo "Tidak ada kamar tersedia untuk tipe: " . $type . "\n";
            return [];
        }

        foreach ($rooms as $room) {
            echo "• Kamar #" . $room['number'] . " - Status: " . $room['status'] . "\n";
        }
        
        return $rooms;
    }

    // Mencari kamar berdasarkan range harga
    public function searchByPriceRange($minPrice, $maxPrice) {
        echo "\n--- Mencari kamar dengan harga Rp" . number_format($minPrice, 0, ',', '.') . 
             " - Rp" . number_format($maxPrice, 0, ',', '.') . " ---\n";
        
        $types = RoomFactory::getAvailableTypes();
        $results = [];

        foreach ($types as $type) {
            $room = RoomFactory::createRoom($type);
            if ($room->getPrice() >= $minPrice && $room->getPrice() <= $maxPrice) {
                $available = $this->db->getAvailableRooms($type);
                if (!empty($available)) {
                    $results[] = [
                        'type' => $type,
                        'price' => $room->getPrice(),
                        'available' => count($available)
                    ];
                    echo "• " . $room->getType() . " - Rp" . 
                         number_format($room->getPrice(), 0, ',', '.') . 
                         " (" . count($available) . " tersedia)\n";
                }
            }
        }

        if (empty($results)) {
            echo "Tidak ada kamar tersedia dalam range harga tersebut\n";
        }

        return $results;
    }

    // Mencari kamar yang tersedia pada tanggal tertentu
    public function searchByAvailability($checkIn, $checkOut) {
        echo "\n--- Mencari kamar tersedia untuk periode ---\n";
        echo "Check-in: " . $checkIn . "\n";
        echo "Check-out: " . $checkOut . "\n";
        
        // Dummy implementation - assume all available rooms are free
        $rooms = $this->db->getAvailableRooms();
        echo "Ditemukan " . count($rooms) . " kamar tersedia\n";
        
        return $rooms;
    }

    // Pencarian cepat berdasarkan keyword
    public function quickSearch($keyword) {
        echo "\n--- Pencarian: '" . $keyword . "' ---\n";
        
        $keyword = strtolower($keyword);
        $types = RoomFactory::getAvailableTypes();
        $found = false;

        foreach ($types as $type) {
            if (strpos(strtolower($type), $keyword) !== false) {
                $rooms = $this->searchByType($type);
                $found = true;
            }
        }

        if (!$found) {
            echo "Tidak ditemukan hasil untuk: " . $keyword . "\n";
        }
    }
}

?>
