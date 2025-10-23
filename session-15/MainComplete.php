<?php

require_once 'Database.php';
require_once 'DummyDatabase.php';
require_once 'Room.php';
require_once 'Observer.php';
require_once 'User.php';
require_once 'Booking.php';
require_once 'UserService.php';
require_once 'RoomService.php';
require_once 'SearchService.php';
require_once 'BookingService.php';
require_once 'NotificationService.php';

echo "========================================\n";
echo "  SISTEM RESERVASI HOTEL - COMPLETE DEMO\n";
echo "========================================\n\n";

// Initialize Services
echo "--- Inisialisasi Services ---\n";
$userService = new UserService();
$roomService = new RoomService();
$searchService = new SearchService();
$bookingService = new BookingService();
$notificationService = new NotificationService();
echo "✓ Semua services berhasil diinisialisasi\n\n";

// Demo 1: User Registration & Login
echo "\n========== DEMO 1: USER SERVICE ==========\n";
$user1 = $userService->register("Budi Santoso", "budi@email.com", "+62812345678", "password123");
$user2 = $userService->register("Ani Wijaya", "ani@email.com", "+62898765432", "password456");

echo "\n--- Login Test ---\n";
$loggedUser = $userService->login("budi@email.com", "password123");
if ($loggedUser) {
    echo "Current user: " . $loggedUser->getName() . "\n";
}

// Demo 2: Room Service
echo "\n\n========== DEMO 2: ROOM SERVICE ==========\n";
$roomService->displayRoomCatalog();

// Demo 3: Search Service
echo "\n\n========== DEMO 3: SEARCH SERVICE ==========\n";
$searchService->quickSearch("deluxe");
$searchService->searchByPriceRange(500000, 1500000);
$searchService->searchByAvailability("2025-11-01", "2025-11-03");

// Demo 4: Booking Service dengan Observer Pattern
echo "\n\n========== DEMO 4: BOOKING SERVICE ==========\n";
$emailNotif = new EmailNotificationService();
$smsNotif = new SMSNotificationService();
$adminNotif = new AdminNotificationService();

$bookingService->attach($emailNotif);
$bookingService->attach($smsNotif);
$bookingService->attach($adminNotif);

echo "\n--- Creating Bookings ---\n";
$booking1 = $bookingService->bookRoom(
    "Budi Santoso",
    "budi@email.com",
    "+62812345678",
    "standard",
    "2025-11-01",
    "2025-11-03"
);

$booking2 = $bookingService->bookRoom(
    "Ani Wijaya",
    "ani@email.com",
    "+62898765432",
    "suite",
    "2025-11-10",
    "2025-11-15"
);

// Demo 5: Payment & Cancellation
echo "\n\n========== DEMO 5: PAYMENT & CANCELLATION ==========\n";
if ($booking1) {
    echo "\n--- Payment Process ---\n";
    $bookingService->makePayment($booking1['id'], $booking1['total_price']);
}

if ($booking2) {
    echo "\n--- Cancellation Process ---\n";
    $bookingService->cancelBooking($booking2['id']);
}

// Demo 6: Notification Service
echo "\n\n========== DEMO 6: NOTIFICATION SERVICE ==========\n";
$notificationService->sendEmail(
    "test@hotel.com",
    "Welcome to Our Hotel",
    "Thank you for choosing our hotel!"
);
$notificationService->sendWhatsApp(
    "+62812345678",
    "Your room is ready for check-in!"
);

// Statistics
echo "\n\n========== STATISTICS ==========\n";
$db = DummyDatabase::getInstance();
$stats = $db->getStatistics();
echo "Total Users: " . $stats['total_users'] . "\n";
echo "Total Bookings: " . $stats['total_bookings'] . "\n";
echo "Total Rooms: " . $stats['total_rooms'] . "\n";
echo "Available Rooms: " . $stats['available_rooms'] . "\n";

$db->showAllData();

echo "\n========================================\n";
echo "  DEMO COMPLETE - ALL SERVICES WORKING!\n";
echo "========================================\n";
echo "\nServices Implemented:\n";
echo "✓ User Service - Registration & Login\n";
echo "✓ Room Service - Room Management\n";
echo "✓ Search Service - Room Search\n";
echo "✓ Booking Service - Booking & Payment\n";
echo "✓ Notification Service - Multi-channel\n";
echo "\nDesign Patterns:\n";
echo "✓ Singleton Pattern - DummyDatabase\n";
echo "✓ Observer Pattern - Notifications\n";
echo "✓ Factory Pattern - Room Creation\n";
echo "\n";

?>
