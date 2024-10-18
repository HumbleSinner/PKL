<?php

include ("../loginfix/functions.php");

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

    echo "<script>window.location.href = '../loginfix/connect_sql.php'</script>";
}
 catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>