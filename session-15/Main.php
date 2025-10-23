<?php

require_once 'Database.php';
require_once 'DummyDatabase.php';
require_once 'Room.php';
require_once 'Observer.php';
require_once 'BookingService.php';

echo "========================================\n";
echo "   SISTEM RESERVASI HOTEL INTERNASIONAL\n";
echo "========================================\n\n";

// Inisialisasi Database (Singleton Pattern)
echo "--- Inisialisasi Dummy Database ---\n";
$db1 = DummyDatabase::getInstance();
$db2 = DummyDatabase::getInstance();

// Membuktikan Singleton - kedua instance adalah sama
if ($db1 === $db2) {
    echo "✓ Singleton Pattern bekerja: Dummy Database instance adalah sama\n\n";
}

// Inisialisasi Booking Service dengan Observer Pattern
echo "--- Setup Notification Services ---\n";
$bookingService = new BookingService();

// Daftarkan berbagai observer
$emailNotif = new EmailNotificationService();
$smsNotif = new SMSNotificationService();
$adminNotif = new AdminNotificationService();

$bookingService->attach($emailNotif);
$bookingService->attach($smsNotif);
$bookingService->attach($adminNotif);

echo "\n";

// Tampilkan kamar yang tersedia
$bookingService->searchAvailableRooms();

echo "\n--- Simulasi Booking 1 ---\n";
$booking1 = $bookingService->bookRoom(
    "Budi Santoso",
    "budi.santoso@email.com",
    "+62812345678",
    "standard",
    "2025-11-01",
    "2025-11-03"
);

echo "\n\n--- Simulasi Booking 2 ---\n";
$booking2 = $bookingService->bookRoom(
    "Ani Wijaya",
    "ani.wijaya@email.com",
    "+62898765432",
    "deluxe",
    "2025-11-05",
    "2025-11-08"
);

echo "\n\n--- Simulasi Booking 3 ---\n";
$booking3 = $bookingService->bookRoom(
    "Charles Anderson",
    "charles.anderson@email.com",
    "+1234567890",
    "suite",
    "2025-11-10",
    "2025-11-15"
);

echo "\n\n--- Simulasi Booking Gagal (Tanggal Invalid) ---\n";
$bookingFailed = $bookingService->bookRoom(
    "John Doe",
    "john.doe@email.com",
    "+62811111111",
    "standard",
    "2025-11-20",
    "2025-11-20"
);

// Tampilkan statistik dan data
echo "\n\n--- Statistik Sistem ---\n";
$stats = $db1->getStatistics();
echo "Total Booking: " . $stats['total_bookings'] . "\n";
echo "Total Kamar: " . $stats['total_rooms'] . "\n";
echo "Kamar Tersedia: " . $stats['available_rooms'] . "\n";

// Tampilkan semua data yang tersimpan
$db1->showAllData();

echo "\n========================================\n";
echo "   SISTEM BERJALAN DENGAN SUKSES!\n";
echo "========================================\n";
echo "\nDesign Patterns yang Digunakan:\n";
echo "1. Singleton Pattern - DummyDatabase (array-based)\n";
echo "2. Observer Pattern - Notification system\n";
echo "3. Factory Pattern - Room creation\n";
echo "\n";
echo "Note: Class Database (PDO) masih tersedia jika\n";
echo "      ingin menggunakan database asli.\n";
echo "\n";

?>
