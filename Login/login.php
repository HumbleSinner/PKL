<?php
session_start();
include 'connect.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn2->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['firstName'] . ' ' . $user['lastName']; // Simpan nama lengkap di sesi
        header("Location: ../loginfix/connect_sql.php"); // Ganti dengan halaman yang sesuai
        exit();
    } else {
        echo "<script>
        alert('Email or password incorrect');
        window.location.href = 'index.php';
    </script>";
    
}
}
?>
