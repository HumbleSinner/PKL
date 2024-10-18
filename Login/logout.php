<?php

include ('../loginfix/functions.php');
try{
    $connections = "DELETE FROM connections";
    $remove_cnt = $conn2->prepare($connections);
    $remove_cnt ->execute();

    $RSLT = "DELETE FROM RSLT";
    $remove_rslt = $conn2->prepare($RSLT);
    $remove_rslt ->execute();

    $status = "ALTER TABLE RSLT DROP COLUMN status";
    $remove_sts = $conn2->prepare ($status);
    $remove_sts -> execute();

    $tableName = "DELETE FROM tableName";
    $remove_tn = $conn2->prepare($tableName);
    $remove_tn ->execute();

    
}catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

session_start();
session_unset(); // Hapus semua data sesi
session_destroy(); // Hapus sesi itu sendiri
header("Location: index.php"); // Arahkan ke halaman login
exit();


?>
