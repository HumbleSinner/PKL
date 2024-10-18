<?php 

//database sistem
$sn2 = "DIAN";
$dn2 = "system";
$uid2 = ""; 
$pass2 = ""; 

$conn2 = new PDO("sqlsrv:Server=$sn2;Database=$dn2", $uid2, $pass2);
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
// Ambil data koneksi terbaru dari tabel connections
$ambil_data = "SELECT TOP 1 server_name, database_name, username_ID, password_server FROM connections ORDER BY id DESC";
$data_user = $conn2->query($ambil_data);
$connectionData = $data_user->fetch(PDO::FETCH_ASSOC);


// Menggunakan data koneksi yang diambil untuk membuat koneksi ke SQL Server lain
$sn1 = $connectionData['server_name'];
$dn1 = $connectionData['database_name'];
$uid1 = $connectionData['username_ID'];
$pass1 = $connectionData['password_server'];


try {
//koneksi ke sql server user 
$conn1 = new PDO("sqlsrv:server=$sn1;Database=$dn1", $uid1, $pass1);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "<script>
        alert('Connected to SQL Server');
        window.location.href = '../mainfix/main.php';
        </script>"; }
        
catch (PDOException $e) {
            $query="DELETE FROM connections";
            $hapus=$conn2->query($query);
            echo "<script>
            alert('Gagal Tersambung');
            window.location.href = '../loginfix/connect_sql.php';
            </script>"; 
        }
// Menutup koneksi kedua
$conn1 = null;
?>