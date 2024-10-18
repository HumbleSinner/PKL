<?php

include('../loginfix/functions.php');
session_start();


if (isset($_GET['table'])) {
    $selected_table = $_GET['table'];

   
    try {
        
        $selectUT = "SELECT * FROM $selected_table";
        $stmt1 = $conn1->prepare($selectUT);
        $stmt1->execute();
        $dataUT = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        $sql2 = "INSERT INTO RSLT (company_code, branch_code, subasset_code, subasset_name,asset_group_lvl1, asset_code, revision_number, document_line, economy_life, asset_life, asset_condition, acquisition_date, acquisition_value, depreciation_value, book_value, location) 
                 VALUES (:company_code, :branch_code, :subasset_code, :subasset_name,:asset_group_lvl1, :asset_code, :revision_number, :document_line, :economy_life, :asset_life, :asset_condition, :acquisition_date, :acquisition_value, :depreciation_value, :book_value, :location)";

        $stmt2 = $conn2->prepare($sql2);
        $allKeysMissing = true; 

        foreach ($dataUT as $row) {
            if (isset($row['company_code'], $row['branch_code'], $row['subasset_code'], $row['subasset_name'],  $row['asset_group_lvl1'], $row['asset_code'], $row['revision_number'], $row['document_line'], $row['economy_life'], $row['asset_life'], $row['asset_condition'], $row['acquisition_date'], $row['acquisition_value'], $row['depreciation_value'], $row['book_value'], $row['location'])) {

                $allKeysMissing = false; 

                $check_sql = "SELECT COUNT(*) FROM RSLT WHERE company_code = :company_code AND branch_code = :branch_code AND subasset_code = :subasset_code AND asset_code = :asset_code AND revision_number = :revision_number AND document_line = :document_line";
                $check_stmt = $conn2->prepare($check_sql);
                $check_stmt->execute([
                    ':company_code' => $row['company_code'],
                    ':branch_code' => $row['branch_code'],
                    ':subasset_code' => $row['subasset_code'],
                    ':asset_code' => $row['asset_code'],
                    ':revision_number' => $row['revision_number'],
                    ':document_line' => $row['document_line']
                ]);

                if ($check_stmt->fetchColumn() == 0) {
                    
                    $insertTn = "INSERT INTO tableName (namaTabel) VALUES (:namaTabel)";
                    $dataTn = $conn2->prepare($insertTn);
                    $dataTn->bindParam(':namaTabel', $selected_table);
                    $dataTn->execute();

             
                    $stmt2->execute([
                        ':company_code' => $row['company_code'],
                        ':branch_code' => $row['branch_code'],
                        ':subasset_code' => $row['subasset_code'],
                        ':subasset_name' => $row['subasset_name'],
                        ':asset_group_lvl1' => $row['asset_group_lvl1'],
                        ':asset_code' => $row['asset_code'],
                        ':revision_number' => $row['revision_number'],
                        ':document_line' => $row['document_line'],
                        ':economy_life' => $row['economy_life'],
                        ':asset_life' => $row['asset_life'],
                        ':asset_condition' => $row['asset_condition'],
                        ':acquisition_date' => $row['acquisition_date'],
                        ':acquisition_value' => $row['acquisition_value'],
                        ':depreciation_value' => $row['depreciation_value'],
                        ':book_value' => $row['book_value'],
                        ':location' => $row['location']
                    ]);
                }
            }
        }

      
        if ($allKeysMissing) {
            echo "<script>
                    alert('Tabel does not match the format');
                    window.location.href = 'main.php';
                  </script>";
        }
    } catch (PDOException $e) {
        echo "Database haha: " . $e->getMessage();
    }
} else {
    echo "Table not specified.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="logo.png" alt="#">
            <br>
            <a>ASSCA</a>
        </div>
        <div class="header-user" id="username">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <form method="post" action="../Login/logout.php">
                <button type="submit" class="logout-button">
                    <img src="logout.png" alt="#">
                    Logout
                </button>
            </form>
        </div>
    </div>
    <div class="main">
        <div class="nav-column">
            <div class="color-nav">
            <h1 class="header-left">Customize Table</h1>
            <form id="table-form" action="" method="GET">
                <div class="dropdown">
                    <p> nama_tabel : </p>
                    <div class="select">
                        <span class="selected">tabel default</span>
                        <div class="caret"></div>
                    </div>

                    <input type="hidden" name="selected_table" id="selected_table">
                    <ul class="menu" id="table-dropdown">
                        <?php foreach ($tables as $table) : ?>
                            <li><?php echo htmlspecialchars($table); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="result">
                    <button type="button" class="result-button" name="result-button" id="result-button">
                        <a>RESULT</a>
                    </button>
                </div>
            </form>
            </div>
            <form action="label.php" >
        <div class="add-column">
            <button type="button"><a href="label.php">ANALYSIS</a></button>  
        </div>
    </form>
        </div>

        <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Preview Data Table</h1>
            <div class="input-group">
            
                    <form method="GET" action="" class="input-src">
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <input type="hidden" name="table" value="<?php echo htmlspecialchars($selected_table); ?>">
                        <button type="submit"></button>

                    </form>
                    <img src="../images/search.png" alt="">
                </div>
            
        </section>
        <section class="table__body">
            <table>
                <thead>
                <tr>
                                <th>No.</th>
                                <!-- <th>company_code</th>
                                <th>branch_code</th> -->
                                <th>Subasset Code</th>
                                <th>Asset Name</th>
                                <!-- <th>asset_group_lvl1</th> -->
                                <!-- <th>asset_code</th> -->
                                <!-- <th>revision_number</th>
                                <th>document_line</th> -->
                                <th>Economy Life</th>
                                <th>Asset Life</th>
                                <th>Assey Condition</th>
                                <th>Acquisition Date</th>
                                <th>Acquisition Value</th>
                                <th>Depreciation Value</th>
                                <th>Book Value</th>
                                <th>Location</th>
                            </tr>
                </thead>
                <tbody>
                <?php
                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            foreach ($dataUT as $index => $row) {
                      
                                if (
                                    empty($search) ||
                                    // stripos($row['company_code'], $search) !== false ||
                                    // stripos($row['branch_code'], $search) !== false ||
                                    stripos($row['subasset_code'], $search) !== false ||
                                    stripos($row['subasset_name'], $search) !== false ||
                                    // stripos($row['asset_group_lvl1'], $search) !== false ||
                                    // stripos($row['asset_code'], $search) !== false ||
                                    stripos($row['revision_number'], $search) !== false ||
                                    stripos($row['document_line'], $search) !== false ||
                                    stripos($row['economy_life'], $search) !== false ||
                                    stripos($row['asset_life'], $search) !== false ||
                                    stripos($row['asset_condition'], $search) !== false ||
                                    stripos($row['acquisition_date'], $search) !== false ||
                                    stripos($row['acquisition_value'], $search) !== false ||
                                    stripos($row['depreciation_value'], $search) !== false ||
                                    stripos($row['book_value'], $search) !== false ||
                                    stripos($row['location'], $search) !== false
                                ) {

                                    echo "<tr>";
                                    
                                    echo "<td>" . ($index + 1) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['company_code']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['branch_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subasset_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subasset_name']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['asset_group_lvl1']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['asset_code']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['revision_number']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['document_line']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['economy_life']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['asset_life']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['asset_condition']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['acquisition_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['acquisition_value']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['depreciation_value']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['book_value']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                    
                    
                </tbody>
            </table>
        </section>
    </main>
   
    
        
    </div>
    <script src="style.js"></script>
    

</body>

</html>