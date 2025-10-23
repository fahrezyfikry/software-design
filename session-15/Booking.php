<?php

// Class untuk entitas Booking
class Booking {
    private $id;
    private $customerName;
    private $customerEmail;
    private $customerPhone;
    private $roomType;
    private $roomNumber;
    private $checkIn;
    private $checkOut;
    private $nights;
    private $pricePerNight;
    private $totalPrice;
    private $status;
    private $createdAt;

    public function __construct($customerName, $customerEmail, $customerPhone, $roomType, $roomNumber, $checkIn, $checkOut, $nights, $pricePerNight) {
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->customerPhone = $customerPhone;
        $this->roomType = $roomType;
        $this->roomNumber = $roomNumber;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->nights = $nights;
        $this->pricePerNight = $pricePerNight;
        $this->totalPrice = $pricePerNight * $nights;
        $this->status = 'pending';
        $this->createdAt = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getCustomerName() { return $this->customerName; }
    public function getCustomerEmail() { return $this->customerEmail; }
    public function getCustomerPhone() { return $this->customerPhone; }
    public function getRoomType() { return $this->roomType; }
    public function getRoomNumber() { return $this->roomNumber; }
    public function getCheckIn() { return $this->checkIn; }
    public function getCheckOut() { return $this->checkOut; }
    public function getNights() { return $this->nights; }
    public function getPricePerNight() { return $this->pricePerNight; }
    public function getTotalPrice() { return $this->totalPrice; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setStatus($status) { $this->status = $status; }

    // Konfirmasi booking
    public function confirm() {
        $this->status = 'confirmed';
    }

    // Batalkan booking
    public function cancel() {
        $this->status = 'cancelled';
    }

    // Bayar booking
    public function markAsPaid() {
        $this->status = 'paid';
    }

    // Convert ke array (untuk database)
    public function toArray() {
        return [
            'id' => $this->id,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'room_type' => $this->roomType,
            'room_number' => $this->roomNumber,
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'nights' => $this->nights,
            'price_per_night' => $this->pricePerNight,
            'total_price' => $this->totalPrice,
            'status' => $this->status,
            'created_at' => $this->createdAt
        ];
    }

    // Create dari array (dari database)
    public static function fromArray($data) {
        $booking = new self(
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['room_type'],
            $data['room_number'],
            $data['check_in'],
            $data['check_out'],
            $data['nights'],
            $data['price_per_night']
        );
        $booking->setId($data['id']);
        $booking->setStatus($data['status']);
        return $booking;
    }

    // Display info
    public function displayInfo() {
        echo "\n=== BOOKING INFO ===\n";
        echo "Booking ID: " . $this->id . "\n";
        echo "Customer: " . $this->customerName . "\n";
        echo "Email: " . $this->customerEmail . "\n";
        echo "Phone: " . $this->customerPhone . "\n";
        echo "Kamar: " . $this->roomType . " #" . $this->roomNumber . "\n";
        echo "Check-in: " . $this->checkIn . "\n";
        echo "Check-out: " . $this->checkOut . "\n";
        echo "Durasi: " . $this->nights . " malam\n";
        echo "Harga/malam: Rp" . number_format($this->pricePerNight, 0, ',', '.') . "\n";
        echo "Total: Rp" . number_format($this->totalPrice, 0, ',', '.') . "\n";
        echo "Status: " . $this->status . "\n";
        echo "====================\n";
    }
}

?>
