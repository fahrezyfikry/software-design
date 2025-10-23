<?php

require_once 'Observer.php';
require_once 'Database.php';
require_once 'DummyDatabase.php';
require_once 'NotificationService.php';
require_once 'Booking.php';

class BookingService {
    private $observers = []; // Daftar observer untuk pattern Observer
    private $bookingCounter = 1000; // Counter untuk ID booking
    private $notificationService; // Service untuk notifikasi

    public function __construct() {
        $this->notificationService = new NotificationService();
    }

    // Menambahkan observer ke daftar
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
        echo "Observer " . get_class($observer) . " berhasil didaftarkan.\n";
    }

    // Menghapus observer dari daftar
    public function detach(Observer $observer) {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    // Notifikasi semua observer (Observer Pattern)
    private function notify($booking) {
        foreach($this->observers as $observer){
            $observer->update($booking);
        }
    }

    // Membuat booking baru
    public function bookRoom($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut) {
        try {
            // Buat room menggunakan Factory Pattern
            $room = RoomFactory::createRoom($roomType);
            $room->setRoomNumber(rand(100, 999));
            
            // Hitung durasi menginap
            $checkInDate = new DateTime($checkIn);
            $checkOutDate = new DateTime($checkOut);
            $nights = $checkInDate->diff($checkOutDate)->days;
            
            if ($nights <= 0) {
                throw new Exception("Tanggal check-out harus setelah check-in");
            }
            
            // Buat object Booking
            $booking = new Booking(
                $customerName,
                $customerEmail,
                $customerPhone,
                $room->getType(),
                $room->getRoomNumber(),
                $checkIn,
                $checkOut,
                $nights,
                $room->getPrice()
            );
            
            // Set ID dan konfirmasi
            $booking->setId('BK' . ($this->bookingCounter++));
            $booking->confirm();
            
            // Simpan ke database menggunakan Singleton
            $db = DummyDatabase::getInstance();
            $db->save($booking->toArray());
            
            // Display info
            echo "\n=== BOOKING BERHASIL ===\n";
            echo "Booking ID: " . $booking->getId() . "\n";
            echo "Customer: " . $customerName . "\n";
            echo "Kamar: " . $room->getDetails() . "\n";
            echo "Check-in: " . $checkIn . "\n";
            echo "Check-out: " . $checkOut . "\n";
            echo "Durasi: " . $nights . " malam\n";
            echo "Total: Rp" . number_format($booking->getTotalPrice(), 0, ',', '.') . "\n";
            echo "========================\n";
            
            // Notifikasi semua observer (Observer Pattern)
            $this->notify($booking->toArray());
            
            // Send notification via NotificationService
            $this->notificationService->sendBookingConfirmation($booking->toArray(), ['email', 'sms']);
            
            return $booking;
            
        } catch (Exception $e) {
            echo "\n[ERROR] Booking gagal: " . $e->getMessage() . "\n";
            return null;
        }
    }

    // Mencari dan menampilkan kamar yang tersedia
    public function searchAvailableRooms() {
        echo "\n=== TIPE KAMAR TERSEDIA ===\n";
        $types = RoomFactory::getAvailableTypes();
        foreach ($types as $type) {
            $room = RoomFactory::createRoom($type);
            echo "- " . $room->getType() . ": Rp" . number_format($room->getPrice(), 0, ',', '.') . " per malam\n";
        }
        echo "===========================\n";
        return $types;
    }

    // Membatalkan booking
    public function cancelBooking($bookingId) {
        $db = DummyDatabase::getInstance();
        $booking = $db->getBookingById($bookingId);
        
        if (!$booking) {
            echo "✗ Booking tidak ditemukan: " . $bookingId . "\n";
            return false;
        }

        echo "✓ Booking #" . $bookingId . " berhasil dibatalkan\n";
        $this->notificationService->sendCancellationNotice($booking);
        return true;
    }

    // Melakukan pembayaran untuk booking
    public function makePayment($bookingId, $amount) {
        $db = DummyDatabase::getInstance();
        $booking = $db->getBookingById($bookingId);
        
        if (!$booking) {
            echo "✗ Booking tidak ditemukan: " . $bookingId . "\n";
            return false;
        }

        if ($amount < $booking['total_price']) {
            echo "✗ Pembayaran kurang. Diperlukan: Rp" . 
                 number_format($booking['total_price'], 0, ',', '.') . "\n";
            return false;
        }

        echo "✓ Pembayaran berhasil untuk booking #" . $bookingId . "\n";
        echo "  Jumlah: Rp" . number_format($amount, 0, ',', '.') . "\n";
        
        $this->notificationService->sendEmail(
            $booking['customer_email'],
            "Payment Received",
            "Pembayaran Anda sebesar Rp" . number_format($amount, 0, ',', '.') . " telah diterima."
        );
        
        return true;
    }
}

?>
