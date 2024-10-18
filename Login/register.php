<?php 

include 'connect.php';

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirmPassword) {
        echo "<script>alert ('Password do not match!');
            window.location.href = 'signUp.php'
            </script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash password

        // Query untuk memeriksa apakah email sudah ada
        $checkEmail = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $conn2->prepare($checkEmail);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            echo "<script>alert ('Email already exist!');
            window.location.href = 'signUp.php'
            </script>";
        } else {
            // Menggunakan prepared statement untuk menghindari SQL Injection
            $insertQuery = "INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)";
            $insert = $conn2->prepare($insertQuery);
            $insert->bindParam(':firstName', $firstName);
            $insert->bindParam(':lastName', $lastName);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':password', $hashedPassword);

            if ($insert->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Failed to insert data: " . implode(" - ", $insert->errorInfo());
            }
        }
    }
}


?>