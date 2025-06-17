<?php
require 'functions.php';
session_start();

$message = '';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Handle email submission
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $code = generateVerificationCode();

        // Store code and email in session
        $_SESSION['verification_code'] = $code;
        $_SESSION['email'] = $email;

        // Send the email (you’ll implement this in functions.php)
        sendVerificationEmail($email, $code);

        $message = "Verification code sent to <strong>$email</strong>.";

        // For development only: show the code on the page
        $message .= "<br><strong>Dev Code:</strong> $code";
    }

    // Step 2: Handle code verification
    if (isset($_POST['verification_code'])) {
        $enteredCode = trim($_POST['verification_code']);

        if (isset($_SESSION['verification_code']) && $enteredCode === $_SESSION['verification_code']) {
            $email = $_SESSION['email'];
            registerEmail($email);

            $message = "✅ Email <strong>$email</strong> verified and registered successfully.";

            // Clear session
            unset($_SESSION['verification_code'], $_SESSION['email']);
        } else {
            $message = "❌ Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Register Your Email</h2>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>

    <form method="POST">
        <label>Verification Code:</label>
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>
