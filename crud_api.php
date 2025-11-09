<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getDbConnection(): PDO {
    try {
        $dbFile = __DIR__ . '/documents.sqlite';
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}

function getAllDocuments(): array {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query("SELECT * FROM documents ORDER BY expiration_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getDocumentById(int $id): ?array {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM documents WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);
        return $doc ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

function addDocument(string $name, string $type, string $expirationDate, ?string $notes = null): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("INSERT INTO documents (name, type, expiration_date, notes) VALUES (:name, :type, :expiration_date, :notes)");
        return $stmt->execute([
            ':name' => $name,
            ':type' => $type,
            ':expiration_date' => $expirationDate,
            ':notes' => $notes
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateDocument(int $id, string $name, string $type, string $expirationDate, ?string $notes = null): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("UPDATE documents SET name = :name, type = :type, expiration_date = :expiration_date, notes = :notes WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':type' => $type,
            ':expiration_date' => $expirationDate,
            ':notes' => $notes
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function deleteDocument(int $id): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        return false;
    }
}

// -----------------------------
// REST-like request handling
// -----------------------------
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $doc = getDocumentById((int)$_GET['id']);
        if ($doc) {
            echo json_encode($doc);
        } else {
            echo json_encode(['success' => false, 'error' => 'Document not found']);
        }
    } else {
        echo json_encode(getAllDocuments());
    }
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['action'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
        exit;
    }

    switch ($input['action']) {
        case 'add':
            $ok = addDocument($input['name'], $input['type'], $input['expiration_date'], $input['notes'] ?? null);
            echo json_encode(['success' => $ok]);
            break;

        case 'update':
            if (!isset($input['id'])) {
                echo json_encode(['success' => false, 'error' => 'Missing document ID']);
                break;
            }
            $ok = updateDocument((int)$input['id'], $input['name'], $input['type'], $input['expiration_date'], $input['notes'] ?? null);
            echo json_encode(['success' => $ok]);
            break;

        case 'delete':
            if (!isset($input['id'])) {
                echo json_encode(['success' => false, 'error' => 'Missing document ID']);
                break;
            }
            $ok = deleteDocument((int)$input['id']);
            echo json_encode(['success' => $ok]);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
    exit;
}

// Default case
echo json_encode(['success' => false, 'error' => 'Unsupported request method']);
