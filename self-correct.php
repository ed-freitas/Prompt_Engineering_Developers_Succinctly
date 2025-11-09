 <?php

 function processUserInput($input)
 {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $age = $_POST["age"];
    // Assume $db is a global SQLite PDO connection
    global $db;

    $stmt = $db->prepare("INSERT INTO users (name, email, age) VALUES 
    (".$name.", ".$email.", ".$age.")");
    $stmt->execute();
    echo "User added successfully!";
}