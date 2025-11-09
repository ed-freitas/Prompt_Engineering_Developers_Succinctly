<?php
function processUserInput(PDO $db): void
{
    // Validate and sanitize input
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age   = $_POST['age'] ?? null;

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !is_numeric($age)) {
        echo "Invalid input. Please check your data.";
        return;
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO users (name, email, age)
            VALUES (:name, :email, :age)
        ");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->execute();

        echo "User added successfully!";
    } catch (PDOException $e) {
        // Log error to file, not shown to user
        error_log("Database error in processUserInput: " . $e->getMessage());
        echo "An error occurred while adding the user.";
    }
}
