<?php
include('../loginfix/functions.php');

try {

    $selectAny = "SELECT * FROM RSLT";
    $stmt2 = $conn2->prepare($selectAny);
    $stmt2->execute();
    $dataAny = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Array opsi
    $options = ['Disposal', 'Replace', 'Sell', 'Keep']; // Sesuaikan dengan opsi yang diinginkan

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
        // Ambil data dari form
        $subasset_name = $_POST['subasset_name'];
        $selected_option = $_POST['options'];
        $asset_group_lvl1 = $_POST['asset_group_lvl1'];
        $company_code = $_POST['company_code'];
        $branch_code = $_POST['branch_code'];
        $asset_code = $_POST['asset_code'];
        $revision_number = $_POST['revision_number'];
        $document_line = $_POST['document_line'];
        $subasset_code = $_POST['subasset_code'];
        $asset_life = $_POST['asset_life'];
        $economy_life = $_POST['economy_life'];
        $asset_condition = $_POST['asset_condition'];
        $acquisition_date = $_POST['acquisition_date'];
        $acquisition_value = $_POST['acquisition_value'];
        $depreciation_value = $_POST['depreciation_value'];
        $book_value = $_POST['book_value'];
        $location = $_POST['location'];


        // Buat query untuk update
        $query = "UPDATE RSLT
                  SET status = :selected_option
                  WHERE company_code = :company_code AND branch_code = :branch_code AND asset_code = :asset_code AND revision_number = :revision_number AND document_line = :document_line AND subasset_code = :subasset_code";

        // Siapkan dan jalankan query
        $stmt = $conn2->prepare($query);
        $stmt->execute([
            ':selected_option' => $selected_option,
            ':company_code' => $company_code,
            ':branch_code' => $branch_code,
            ':asset_code' => $asset_code,
            ':revision_number' => $revision_number,
            ':document_line' => $document_line,
            ':subasset_code' => $subasset_code,
        ]);

        $selectCRTN = "SELECT*FROM RSLT";
        $stmtCRTN = $conn2->prepare($selectCRTN);
        $stmtCRTN->execute();
        $dataCRTN = $stmtCRTN->fetchAll(PDO::FETCH_ASSOC);



        // Query untuk memasukkan data ke tabel CRTN
        $query_isrt = "INSERT INTO CRTN (company_code, branch_code, subasset_code, subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, economy_life, asset_life, asset_condition, acquisition_date, acquisition_value, depreciation_value, book_value, location, status) 
        VALUES (:company_code, :branch_code, :subasset_code, :subasset_name, :asset_group_lvl1, :asset_code, :revision_number, :document_line, :economy_life, :asset_life, :asset_condition, :acquisition_date, :acquisition_value, :depreciation_value, :book_value, :location, :selected_option)";
        $insert_crtn = $conn2->prepare($query_isrt);

        //query untuk updat crtn 
        $query_updt = "UPDATE CRTN
                  SET status = :selected_option
                  WHERE company_code = :company_code AND branch_code = :branch_code AND asset_code = :asset_code AND revision_number = :revision_number AND document_line = :document_line AND subasset_code = :subasset_code";

        // Siapkan dan jalankan query
        $update_crtn = $conn2->prepare($query_updt);



        $check_sql = "SELECT COUNT(*) FROM CRTN WHERE company_code = :company_code AND branch_code = :branch_code AND subasset_code = :subasset_code AND asset_code = :asset_code AND revision_number = :revision_number AND document_line = :document_line";
        $check_stmt = $conn2->prepare($check_sql);
        $check_stmt->execute([
            ':company_code' => $company_code,
            ':branch_code' => $branch_code,
            ':subasset_code' => $subasset_code,
            ':asset_code' =>  $asset_code,
            ':revision_number' => $revision_number,
            ':document_line' => $document_line,
        ]);

        if ($check_stmt->fetchColumn() == 0) {
            $insert_crtn->execute([
                // Menggunakan data dari hasil query
                ':company_code' => $company_code,
                ':branch_code' => $branch_code,
                ':subasset_code' => $subasset_code,
                ':subasset_name' => $subasset_name,
                ':asset_group_lvl1' => $asset_group_lvl1,
                ':asset_code' => $asset_code,
                ':revision_number' => $revision_number,
                ':document_line' => $document_line,
                ':economy_life' => $economy_life,
                ':asset_life' => $asset_life,
                ':asset_condition' => $asset_condition,
                ':acquisition_date' => $acquisition_date,
                ':acquisition_value' => $acquisition_value,
                ':depreciation_value' => $depreciation_value,
                ':book_value' => $book_value,
                ':location' => $location,
                ':selected_option' => $selected_option,
            ]);
        } else {
            $update_crtn->execute([
                ':selected_option' => $selected_option,
                ':company_code' => $company_code,
                ':branch_code' => $branch_code,
                ':asset_code' => $asset_code,
                ':revision_number' => $revision_number,
                ':document_line' => $document_line,
                ':subasset_code' => $subasset_code,
            ]);
        }
    }
    echo "<script>
            alert('Data berhasil di update');
            window.location.href = 'correction.php';
            </script>";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
