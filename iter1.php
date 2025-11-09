<?php
function getAllDocuments($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM documents");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// Database connection
$dsn = "mysql:host=localhost;dbname=my_database;charset=utf8mb4";
$user = "db_user";
$pass = "db_password";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $documents = getAllDocuments($pdo);
    
    foreach ($documents as $doc) {
        echo $doc['title'] . "<br>";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
