<?php
/**
 * Contact Form Handler
 * Validates, sanitizes, guards against bot spam via honeypot,
 * and sends an email notification to me@thomassapp.com.
 */

header('Content-Type: application/json; charset=UTF-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

// 1. Anti-Spam Honeypot check
// The 'website' input field is hidden from real users. Bots typically auto-fill it.
if (!empty($_POST['website'])) {
    // Silently return success to fool bots
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent successfully.'
    ]);
    exit;
}

// 2. Extract and sanitize inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : 'Portfolio Contact Message';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 3. Validation
$errors = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errors[] = 'Please provide a valid name.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid email address.';
}

if (empty($message) || mb_strlen($message) < 5) {
    $errors[] = 'Please provide a message of at least 5 characters.';
}

// Check for header injection attempts in name or subject
if (preg_match("/[\r\n]/", $name) || preg_match("/[\r\n]/", $subject)) {
    $errors[] = 'Invalid input detected.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// 4. Send Email Notification
$to = 'me@thomassapp.com';
$email_subject = "[ThomasSapp.com] " . ($subject ?: "New Contact from " . $name);

$email_body = "You have received a new message from your portfolio website:\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Date: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP: " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown') . "\n\n";
$email_body .= "Message:\n" . str_replace("\r\n", "\n", $message) . "\n\n";

$headers = [
    'From' => 'no-reply@thomassapp.com',
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion(),
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8'
];

$header_string = '';
foreach ($headers as $k => $v) {
    $header_string .= "$k: $v\r\n";
}

$sent = @mail($to, $email_subject, $email_body, $header_string);

// Return JSON response
if ($sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent successfully. I will get back to you shortly.'
    ]);
} else {
    // If mail() failed on server (e.g. local dev without sendmail config)
    // Log to cache/messages.log if writable
    $log_dir = __DIR__ . '/cache';
    if (is_dir($log_dir) && is_writable($log_dir)) {
        file_put_contents($log_dir . '/messages.log', "[" . date('c') . "] From: $name <$email>\nSubject: $subject\n$message\n\n", FILE_APPEND);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message was received and logged. I will get back to you shortly.'
    ]);
}
