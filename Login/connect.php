<?php

$sn2 = "DIAN";
$dn2 = "system";
$uid2 = ""; 
$pass2 = ""; 

$conn2 = new PDO("sqlsrv:Server=$sn2;Database=$dn2", $uid2, $pass2);
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    ?>