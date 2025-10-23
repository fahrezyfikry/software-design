<?php

require_once 'Observer.php';

class NotificationService {
    
    // Mengirim notifikasi via Email
    public function sendEmail($to, $subject, $message) {
        echo "\n[EMAIL SENT]\n";
        echo "To: " . $to . "\n";
        echo "Subject: " . $subject . "\n";
        echo "Message: " . $message . "\n";
        return true;
    }

    // Mengirim notifikasi via SMS
    public function sendSMS($phone, $message) {
        echo "\n[SMS SENT]\n";
        echo "Phone: " . $phone . "\n";
        echo "Message: " . $message . "\n";
        return true;
    }

    // Mengirim notifikasi via WhatsApp
    public function sendWhatsApp($phone, $message) {
        echo "\n[WHATSAPP SENT]\n";
        echo "Phone: " . $phone . "\n";
        echo "Message: " . $message . "\n";
        return true;
    }

    // Mengirim konfirmasi booking melalui berbagai channel
    public function sendBookingConfirmation($booking, $channels = ['email', 'sms']) {
        $message = "Booking konfirmasi untuk ID: " . $booking['id'] . 
                   ", Kamar: " . $booking['room_type'] . 
                   ", Check-in: " . $booking['check_in'];

        if (in_array('email', $channels)) {
            $this->sendEmail(
                $booking['customer_email'],
                "Konfirmasi Booking #" . $booking['id'],
                $message
            );
        }

        if (in_array('sms', $channels)) {
            $this->sendSMS($booking['customer_phone'], $message);
        }

        if (in_array('whatsapp', $channels)) {
            $this->sendWhatsApp($booking['customer_phone'], $message);
        }
    }

    // Mengirim reminder pembayaran
    public function sendPaymentReminder($booking) {
        $message = "Reminder: Pembayaran untuk booking #" . $booking['id'] . 
                   " sebesar Rp" . number_format($booking['total_price'], 0, ',', '.') . 
                   " belum dilakukan.";
        
        $this->sendEmail($booking['customer_email'], "Payment Reminder", $message);
        $this->sendSMS($booking['customer_phone'], $message);
    }

    // Mengirim notifikasi pembatalan booking
    public function sendCancellationNotice($booking) {
        $message = "Booking #" . $booking['id'] . " telah dibatalkan. " .
                   "Jika ada pembayaran, refund akan diproses dalam 3-5 hari kerja.";
        
        $this->sendEmail($booking['customer_email'], "Booking Cancelled", $message);
        $this->sendSMS($booking['customer_phone'], $message);
    }
}

?>
