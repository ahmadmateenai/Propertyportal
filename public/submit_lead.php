<?php
/**
 * Property Portal — Lead Form Handler
 * File: submit_lead.php
 *
 * Place this file in the SAME folder as index.html
 * Handles form submission, validation, and database saving.
 */

// ── DATABASE CONFIG ──────────────────────────────────────
// Update these to match your environment
define('DB_HOST', 'localhost');
define('DB_NAME', 'property_portal');
define('DB_USER', 'root');
define('DB_PASS', '');       // XAMPP default is blank
// ────────────────────────────────────────────────────────

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

// ── SPAM PROTECTION: Honeypot ────────────────────────────
// Hidden field — bots fill it, real users don't
if (!empty($_POST['website'])) {
    echo json_encode(['status' => 'success', 'message' => 'Submitted.']);
    exit;
}

// ── SANITIZE INPUT ───────────────────────────────────────
function clean($val) {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$name          = clean($_POST['name']          ?? '');
$phone         = clean($_POST['phone']         ?? '');
$email         = clean($_POST['email']         ?? '');
$city          = clean($_POST['city']          ?? '');
$property_type = clean($_POST['property_type'] ?? '');
$purpose       = clean($_POST['purpose']       ?? '');
$message       = clean($_POST['message']       ?? '');
$budget_raw    = trim($_POST['budget']         ?? '');
$budget        = ($budget_raw !== '' && is_numeric($budget_raw)) ? (int)$budget_raw : null;
$ip_address    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// ── SERVER-SIDE VALIDATION ───────────────────────────────
$errors = [];

if (strlen($name) < 2 || strlen($name) > 100)
    $errors[] = 'Full name must be 2–100 characters.';

if (!preg_match('/^[+\d\s\-()\\.]{7,20}$/', $phone))
    $errors[] = 'Please enter a valid phone number.';

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'Please enter a valid email address.';

if (strlen($city) < 2 || strlen($city) > 100)
    $errors[] = 'City must be 2–100 characters.';

if (!in_array($property_type, ['House', 'Apartment', 'Plot', 'Commercial']))
    $errors[] = 'Please select a valid property type.';

if (!in_array($purpose, ['Buy', 'Rent', 'Sell']))
    $errors[] = 'Please select a valid purpose.';

if ($budget !== null && ($budget < 0 || $budget > 9999999999))
    $errors[] = 'Please enter a valid budget amount.';

if (strlen($message) > 1000)
    $errors[] = 'Message cannot exceed 1000 characters.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
    exit;
}

// ── SAVE TO DATABASE ─────────────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

    $stmt = $pdo->prepare("
        INSERT INTO property_leads
            (name, phone, email, city, property_type, purpose, budget, message, ip_address, created_at)
        VALUES
            (:name, :phone, :email, :city, :property_type, :purpose, :budget, :message, :ip, NOW())
    ");

    $stmt->execute([
        ':name'          => $name,
        ':phone'         => $phone,
        ':email'         => $email,
        ':city'          => $city,
        ':property_type' => $property_type,
        ':purpose'       => $purpose,
        ':budget'        => $budget,
        ':message'       => $message,
        ':ip'            => $ip_address,
    ]);

    echo json_encode([
        'status'  => 'success',
        'message' => 'Inquiry submitted successfully.'
    ]);

} catch (PDOException $e) {
    // Log real error on server, show generic message to user
    error_log('[PropertyPortal] DB Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Database error. Please check DB config in submit_lead.php'
    ]);
}