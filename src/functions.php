<?php

function generateVerificationCode($length = 6) {
    return str_pad(random_int(0, 999999), $length, '0', STR_PAD_LEFT);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "Your verification code is: $code\n\n";
    $message .= "If you didn't request this, you can ignore the email.\n";
    $message .= "To unsubscribe from future emails, click the link below:\n";
    $message .= "http://localhost:8000/unsubscribe.php";

    $headers = "From: no-reply@example.com\r\n";

    // For testing on localhost, you can log emails to a file instead of actually sending
    file_put_contents('email_log.txt', "[" . date('Y-m-d H:i:s') . "] Email sent to $email with code $code\n", FILE_APPEND);
    // Uncomment this to send actual emails if mail() is configured:
    // mail($email, $subject, $message, $headers);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $line = $email . '|VERIFIED';

    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    foreach ($emails as $e) {
        if (str_starts_with($e, $email . '|')) {
            return; // already registered
        }
    }

    file_put_contents($file, $line . PHP_EOL, FILE_APPEND);  // ✅ This is perfect
}


function getRegisteredEmails() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return [];
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emails = [];

    foreach ($lines as $line) {
        [$email, $status] = explode('|', $line);
        if ($status === 'VERIFIED') {
            $emails[] = $email;
        }
    }

    return $emails;
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';

    if (!file_exists($file)) return;

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updated = [];

    foreach ($lines as $line) {
        if (str_starts_with($line, $email . '|')) {
            // Skip adding this line to effectively unsubscribe
            continue;
        }
        $updated[] = $line;
    }

    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
}
