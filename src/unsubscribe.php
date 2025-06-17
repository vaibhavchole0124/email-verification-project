<?php
session_start();
include 'functions.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Step 1: User submits email to unsubscribe
    if (isset($_POST["unsubscribe_email"])) {
        $email = trim($_POST["unsubscribe_email"]);
        $_SESSION["unsub_email"] = $email;
        $_SESSION["unsub_code"] = generateVerificationCode();

        // Display dev code on screen (for testing)
        $message = "Verification code sent to $email.<br>Dev Unsub Code: " . $_SESSION["unsub_code"];
    }

    // Step 2: User enters code to confirm
    if (isset($_POST["unsubscribe_code"])) {
        $codeEntered = trim($_POST["unsubscribe_code"]);
        if (isset($_SESSION["unsub_code"]) && $codeEntered === $_SESSION["unsub_code"]) {
            $email = $_SESSION["unsub_email"];

            // Remove email from file
            $emails = file("registered_emails.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $updated = array_filter($emails, function($e) use ($email) {
                return trim($e) !== $email;
            });

            file_put_contents("registered_emails.txt", implode(PHP_EOL, $updated));
            $message = "✅ Email unsubscribed successfully.";

            unset($_SESSION["unsub_email"], $_SESSION["unsub_code"]);
        } else {
            $message = "❌ Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>Unsubscribe from GitHub Updates</h2>

    <p style="color:green;"><?php echo $message; ?></p>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="unsubscribe_email" required>
        <button type="submit">Unsubscribe</button>
    </form>

    <form method="POST">
        <label>Verification Code:</label>
        <input type="text" name="unsubscribe_code" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
