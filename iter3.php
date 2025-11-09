<?php
function getAllDocuments(PDO $pdo): array {
    $logFile = __DIR__ . '/error_log.txt'; // Log file path

    try {
        $stmt = $pdo->prepare("SELECT id, name, type, created_at, updated_at FROM documents");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $documents = [];
        foreach ($rows as $row) {
            $documents[] = [
                'id'         => (int)$row['id'],
                'name'       => $row['name'],
                'type'       => $row['type'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }

        return $documents;
    } catch (PDOException $e) {
        // Log error with timestamp
        $errorMessage = sprintf("[%s] Database error: %s\n", date('Y-m-d H:i:s'), $e->getMessage());
        file_put_contents($logFile, $errorMessage, FILE_APPEND);

        // Optionally log stack trace for debugging
        // file_put_contents($logFile, $e->getTraceAsString() . "\n", FILE_APPEND);

        return []; // Return empty array on failure
    }
}

$dsn = "mysql:host=localhost;dbname=my_database;charset=utf8mb4";
$user = "db_user";
$pass = "db_password";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $documents = getAllDocuments($pdo);

    if (empty($documents)) {
        echo "No documents found or an error occurred.";
    } else {
        foreach ($documents as $doc) {
            echo "{$doc['id']}: {$doc['name']} ({$doc['type']})<br>";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
