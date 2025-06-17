<?php
require 'functions.php';

// Optional: Log output to cron.log file
$logFile = __DIR__ . '/cron.log';
file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Cron started\n", FILE_APPEND);

sendGitHubUpdatesToSubscribers();

file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Emails sent successfully.\n", FILE_APPEND);
