<?php

// Factory Pattern untuk Room
abstract class Room {
    protected $roomNumber;
    protected $price;
    
    abstract public function getType();
    
    public function setRoomNumber($number) {
        $this->roomNumber = $number;
    }
    
    public function getRoomNumber() {
        return $this->roomNumber;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getDetails() {
        return "Kamar " . $this->getType() . " #" . $this->roomNumber . " - Rp" . number_format($this->price, 0, ',', '.');
    }
}

class StandardRoom extends Room {
    public function __construct() {
        $this->price = 500000;
    }
    
    public function getType() { 
        return "Standard"; 
    }
}

class DeluxeRoom extends Room {
    public function __construct() {
        $this->price = 1000000;
    }
    
    public function getType() { 
        return "Deluxe"; 
    }
}

class SuiteRoom extends Room {
    public function __construct() {
        $this->price = 2000000;
    }
    
    public function getType() { 
        return "Suite"; 
    }
}

class RoomFactory {
    public static function createRoom($type) {
        switch(strtolower($type)) {
            case "standard": 
                return new StandardRoom();
            case "deluxe": 
                return new DeluxeRoom();
            case "suite": 
                return new SuiteRoom();
            default: 
                throw new Exception("Tipe kamar tidak dikenal: " . $type);
        }
    }
    
    public static function getAvailableTypes() {
        return ["standard", "deluxe", "suite"];
    }
}

?>
