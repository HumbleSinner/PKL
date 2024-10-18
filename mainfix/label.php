<?php

session_start();
include('../loginfix/functions.php');


// Hubungkan ke database SQL Server
try {
    $sn1 = $connectionData['server_name'];
    $dn1 = $connectionData['database_name'];
    $uid1 = $connectionData['username_ID'];
    $pass1 = $connectionData['password_server'];
    $conn1 = new PDO("sqlsrv:server=$sn1;Database=$dn1", $uid1, $pass1);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $command = escapeshellcmd('python ./userinput.py');
    exec($command, $output);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
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
                        <button type="button" class="result-button" name="result-button" id="result-button" disabled>
                            <a>RESULT</a>
                        </button>
                    </div>
                </form>
            </div>
            <div class="add-column">
                <button type="submit" class="add-btn"><a href="add.php">ADD COLUMN</a></button>
            </div>

            <div class="correction">
                <button type="submit" class="corr-btn" name="corr-btn" id="corr-btn">
                    <a href="correction.php"> CORRECTION</a>
                </button>
            </div>

            <div class="Finish">
                <button type="submit" class="finish-btn">
                    <a href="finish.php">FINISH</a>
                </button>
            </div>


        </div>

        <main class="table" id="customers_table">
            <section class="table__header">
                <h1>ANALYSIS RESULT</h1>
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
                            <th>Status</th>
                           
                            <!-- <th>company_code</th>
                            <th>branch_code</th> -->
                            <th>Subasset Code</th>
                            <th>Asset Name</th>
                            <!-- <th>asset_group_lvl1</th> -->
                            <th>Asset Code</th>
                            <!-- <th>revision_number</th>
                            <th>document_line</th> -->
                            <th>Economy Life</th>
                            <th>Asset Life </th>
                            <th>Asset Condition</th>
                            <th>Acquisition Date</th>
                            <th>Acquisition value</th>
                            <th>Depreciation Value</th>
                            <th>Book Value</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($output) {
                            $selectAny = "SELECT*FROM RSLT";
                            $stmt2 = $conn2->prepare($selectAny);
                            $stmt2->execute();
                            $dataAny = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            foreach ($dataAny as $index => $row) {
                                // Pengecekan apakah data cocok dengan pencarian
                                if (
                                    empty($search) ||
                                    stripos($row['status'], $search) !== false ||
                                    // stripos($row['company_code'], $search) !== false ||
                                    // stripos($row['branch_code'], $search) !== false ||
                                    stripos($row['subasset_code'], $search) !== false ||
                                    stripos($row['subasset_name'], $search) !== false ||
                                    // stripos($row['asset_group_lvl1'], $search) !== false ||
                                    stripos($row['asset_code'], $search) !== false ||
                                    // stripos($row['revision_number'], $search) !== false ||
                                    // stripos($row['document_line'], $search) !== false ||
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
            
                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['company_code']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['branch_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subasset_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subasset_name']) . "</td>";
                                    // echo "<td>" . htmlspecialchars($row['asset_group_lvl1']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['asset_code']) . "</td>";
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
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>



    </div>
    <script src="style.js"></script>
    <script>
        $('#corr-btn').on('click', function() {
            window.location.href = 'correction.php';
        });
    </script>


</body>

</html>