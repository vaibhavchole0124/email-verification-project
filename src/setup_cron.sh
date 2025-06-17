#!/bin/bash

# Path to PHP and your project
PHP_PATH="/opt/homebrew/bin/php"
PROJECT_PATH="/Users/vaibhavgovindchole/email-verification-project/src"
LOG_FILE="$PROJECT_PATH/cron.log"

# Add CRON job
(
crontab -l 2>/dev/null
echo "*/5 * * * * $PHP_PATH $PROJECT_PATH/cron.php >> $LOG_FILE 2>&1"

) | crontab -

echo "✅ Cron job scheduled to run every 12 hours."
