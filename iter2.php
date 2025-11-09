<?php
function getAllDocuments(PDO $pdo): array {
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
        error_log("Database error in getAllDocuments: " . $e->getMessage());
        return [];
    }
}

$dsn = "mysql:host=localhost;dbname=my_database;charset=utf8mb4";
$user = "db_user";
$pass = "db_password";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $documents = getAllDocuments($pdo);

    foreach ($documents as $doc) {
        echo "{$doc['id']}: {$doc['name']} ({$doc['type']})<br>";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
