<?php
// require_once __DIR__ . '/../../config/config.php';
// require_once BASE_PATH . '/config/db.php'; // This should return $conn = new mysqli(...);

// // Example input (you would usually get this from a form)
// $first_name = 'Salma';
// $last_name = 'Akter';
// $username = 'salma123';
// $email = 'salma123@gmail.com';
// $password = 'password!123';

// // 1️⃣ Hash the password securely
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// // 2️⃣ Prepare and execute the insert statement
// $sql = "INSERT INTO admins (first_name, last_name, username, email, password) 
//         VALUES (?, ?, ?, ?, ?)";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sssss", $first_name, $last_name, $username, $email, $hashedPassword);

// if ($stmt->execute()) {
//     echo "✅ Admin inserted successfully!";
// } else {
//     echo "❌ Error: " . $stmt->error;
// }


echo password_hash('password!123', PASSWORD_BCRYPT);

?>
