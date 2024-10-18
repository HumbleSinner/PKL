<?php

//menghubungkan ke database sistem
$sn2 = "DIAN";
$dn2 = "system";
$uid2 = "";
$pass2 = "";



try {
    $conn2 = new PDO("sqlsrv:Server=$sn2;Database=$dn2", $uid2, $pass2);
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Mengambil data dari form
    $sn1 = $_POST['server_name'];
    $dn1 = $_POST['database_name'];
    $uid1 = $_POST['username_ID'];
    $pass1 = $_POST['password_server'];

    // Menyiapkan query untuk menyimpan data
    $user_sql = "INSERT INTO connections (server_name, database_name, username_ID, password_server) VALUES (:server_name, :database_name, :username_ID, :password_server)";
    $data_user = $conn2->prepare($user_sql);
    $data_user->bindParam(':server_name', $sn1);
    $data_user->bindParam(':database_name', $dn1);
    $data_user->bindParam(':username_ID', $uid1);
    $data_user->bindParam(':password_server', $pass1);


    // Eksekusi query
    $data_user->execute();
    if ($data_user) {
        echo "<script>
            window.location.href = 'conn.php';
            </script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Menutup koneksi
$conn2 = null;

//mulai
// Untuk debugging, tampilkan data yang diterima

// echo "Received data: Server Name = $sn1, Database Name = $dn1, Username ID = $uid1, Password = $pass1";



// try {
//     $conn1 = new PDO( "sqlsrv:server=$sn1;Database= $dn1", $uid1, $pass1); 
//     $conn1->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  

// echo "<script>
// alert('Connected to SQL Server');
// window.location.href = '../mainfix/main.php';
// </script>"; 


// $showTables = "SELECT name FROM sys.tables";

// // Persiapkan dan eksekusi query
// $getTables = $conn1->prepare($showTables);
// $getTables->execute();

//  // Fetch semua hasil query
//  $tables = $getTables->fetchAll(PDO::FETCH_COLUMN);


//  if (isset($_GET['action']) && $_GET['action'] == 'get_rows' && isset($_GET['table'])) {
//     $tableName = $_GET['table'];
//     $showRows = "SELECT * FROM " . $tableName;
//     $getRows = $conn->prepare($showRows);
//     $getRows->execute();
//     $rows = $getRows->fetchAll(PDO::FETCH_ASSOC);
//     echo json_encode($rows);
//     exit();
// }


// catch( PDOException $e ) {  
//     echo "<script>
//             alert('Error connecting to SQL Server');
//             window.location.href = 'connect_sql.php';
//           </script>";

//  } 

//     // Query untuk mendapatkan nama tabel



// // if (isset ($_POST ['submit'])){
// //     $info = getData();
// //     try {  


// //         // session_start();
// //         // $_SESSION['conn1'] = serialize($conn1);

// //         }


















// // Simpan data ke log file untuk memastikan data diterima
// // file_put_contents('log.txt', "Server Name: $sn1, Database Name: $dn1, Username ID: $uid1, Password: $pass1\n", FILE_APPEND);

// // Uncomment dan sesuaikan kode berikut untuk melakukan koneksi ke SQL Server jika diperlukan
// /*
// try {
//     $conn1 = new PDO("sqlsrv:server=$sn1;Database=$dn1", $uid1, $pass1);
//     $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     // Lakukan query atau operasi database lainnya
//     echo 'Connected successfully';
// } catch (PDOException $e) {
//     echo 'Connection failed: ' . $e->getMessage();
// }
// */
// 
