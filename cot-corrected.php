<?php
/**
 * Returns the number of days until $expirationDate.
 * Positive -> days in the future
 * 0        -> today
 * Negative -> days in the past
 *
 * @param string $expirationDate Date in 'YYYY-MM-DD' (or any format accepted by DateTime)
 * @return int
 */
function daysUntilExpiration(string $expirationDate): int
{
    // Use DateTimeImmutable to avoid accidental mutation (optional but good practice)
    $today = new DateTimeImmutable('today'); // normalized to local timezone midnight
    try {
        $expiry = new DateTimeImmutable($expirationDate);
    } catch (Exception $e) {
        // If parse fails, log or rethrow — here we return 0 to indicate "invalid" in a simple way.
        error_log("Invalid expiration date provided: {$expirationDate}");
        return 0;
    }

    // Normalize expiry to midnight too (in case time part was provided)
    $expiry = $expiry->setTime(0, 0, 0);

    $interval = $today->diff($expiry);

    $days = (int)$interval->days;

    // If invert == 1, expiry is in the past, so return negative days
    return $interval->invert ? -$days : $days;
}

// Example test (assuming "today" is 2025-10-07 for these expectations):
echo daysUntilExpiration('2025-10-10') . PHP_EOL; // => 3
echo daysUntilExpiration('2025-10-07') . PHP_EOL; // => 0
echo daysUntilExpiration('2025-10-06') . PHP_EOL; // => -1
