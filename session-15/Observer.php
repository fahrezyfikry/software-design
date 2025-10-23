<?php

// Observer Pattern untuk Notifikasi
interface Observer {
    public function update($booking);
}

class EmailNotificationService implements Observer {
    public function update($booking) {
        echo "\n[EMAIL] Notifikasi dikirim ke: " . $booking['customer_email'] . "\n";
        echo "  - Booking ID: " . $booking['id'] . "\n";
        echo "  - Kamar: " . $booking['room_type'] . " #" . $booking['room_number'] . "\n";
        echo "  - Total: Rp" . number_format($booking['total_price'], 0, ',', '.') . "\n";
    }
}

class SMSNotificationService implements Observer {
    public function update($booking) {
        echo "\n[SMS] Notifikasi dikirim ke: " . $booking['customer_phone'] . "\n";
        echo "  - Booking ID: " . $booking['id'] . "\n";
        echo "  - Konfirmasi reservasi Anda telah berhasil!\n";
    }
}

class AdminNotificationService implements Observer {
    public function update($booking) {
        echo "\n[ADMIN] Notifikasi untuk admin:\n";
        echo "  - Booking baru masuk dengan ID: " . $booking['id'] . "\n";
        echo "  - Customer: " . $booking['customer_name'] . "\n";
    }
}

?>
