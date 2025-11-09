 <?php
    function daysUntilExpiration(string $expirationDate): int
    {
        $today = new DateTime();
        $expiry = new DateTime($expirationDate);
        $interval = $today->diff($expiry);
        return $interval->days;
    }
 
    // Test cases
    echo daysUntilExpiration('2025-10-10'); // Should be 3
    echo daysUntilExpiration('2025-10-07'); // Should be 0
    echo daysUntilExpiration('2025-10-06'); // Should be -1 (or similar for past dates)
 ?>