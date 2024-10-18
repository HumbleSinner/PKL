<?php
// add.php

include('../loginfix/functions.php');

try {
   
    $ambilTn = "SELECT TOP 1 * FROM tableName";
    $tableNameStmt = $conn2->prepare($ambilTn);
    $tableNameStmt->execute();


    $field = $tableNameStmt->fetch(PDO::FETCH_ASSOC);

    if ($field) {
        $tableName = $field['namaTabel']; 

       
        $checkColumn = "SELECT COLUMN_NAME 
                        FROM INFORMATION_SCHEMA.COLUMNS 
                        WHERE TABLE_NAME = :tableName AND COLUMN_NAME = 'status'";
        $checkStmt = $conn1->prepare($checkColumn);
        $checkStmt->bindParam(':tableName', $tableName);
        $checkStmt->execute();

        if ($checkStmt->rowCount() == 0) {
   
            $tambahField = "ALTER TABLE $tableName ADD status VARCHAR(20)";
            $conn1->exec($tambahField);
        }

      
        $select_rslt = "SELECT company_code, branch_code, subasset_code, asset_code, revision_number, document_line, status FROM RSLT";
        $data_rslt = $conn2->prepare($select_rslt);
        $data_rslt->execute();

        $rows = $data_rslt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            
            $company_code = $row['company_code'];
            $branch_code = $row['branch_code'];
            $subasset_code = $row['subasset_code'];
            $asset_code = $row['asset_code'];
            $revision_number = $row['revision_number'];
            $document_line = $row['document_line'];
            $status = $row['status'];

            
            $update_rslt = "
                UPDATE $tableName
                SET status = :status
                WHERE company_code = :company_code
                AND branch_code = :branch_code
                AND subasset_code = :subasset_code
                AND asset_code = :asset_code
                AND revision_number = :revision_number
                AND document_line = :document_line
            ";

           
            $updateStmt = $conn1->prepare($update_rslt);
            $updateStmt->bindParam(':status', $status);
            $updateStmt->bindParam(':company_code', $company_code);
            $updateStmt->bindParam(':branch_code', $branch_code);
            $updateStmt->bindParam(':subasset_code', $subasset_code);
            $updateStmt->bindParam(':asset_code', $asset_code);
            $updateStmt->bindParam(':revision_number', $revision_number);
            $updateStmt->bindParam(':document_line', $document_line);
            $updateStmt->execute();
        }

        
        echo "<script>
        alert('Data updated successfully');
        window.location.href = 'label.php'</script>";
    } else {
        throw new Exception("Tidak ada nama tabel yang ditemukan.");
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
