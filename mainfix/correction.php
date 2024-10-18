<?php
session_start();
include('../loginfix/functions.php');

$selectAny = "SELECT * FROM RSLT";
$stmt2 = $conn2->prepare($selectAny);
$stmt2->execute();
$dataAny = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Array opsi
$options = ['Disposal', 'Replace', 'Sell', 'Keep'];
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
                <button type="submit" class="corr-btn" name="corr-btn" id="corr-btn" disabled>
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
                <h1>CORRECTION STATUS</h1>
                <div class="input-group">
                    <form method="GET" action="" class="input-src">
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <input type="hidden" name="table" value="<?php echo htmlspecialchars($selected_table); ?>">
                        <button type="submit"></button>

                    </form>
                    <img src="../images/search.png" alt="">


            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>status</th>
                            <th>correction</th>
                            <th>company_code</th>
                            <th>branch_code</th>
                            <th>subasset_code</th>
                            <th>subasset_name</th>
                            <th>asset_group_lvl1</th>
                            <th>asset_code</th>
                            <th>revision_number</th>
                            <th>document_line</th>
                            <th>economy_life</th>
                            <th>asset_life</th>
                            <th>asset_condition</th>
                            <th>acquisition_date</th>
                            <th>acquisition_value</th>
                            <th>depreciation_value</th>
                            <th>book_value</th>
                            <th>location</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        foreach ($dataAny as $index => $row) {
                            
                            if (
                                empty($search) ||
                                stripos($row['asset_group_lvl1'], $search) !== false ||
                                stripos($row['company_code'], $search) !== false ||
                                stripos($row['branch_code'], $search) !== false ||
                                stripos($row['subasset_code'], $search) !== false ||
                                stripos($row['subasset_name'], $search) !== false ||
                                stripos($row['asset_code'], $search) !== false ||
                                stripos($row['revision_number'], $search) !== false ||
                                stripos($row['document_line'], $search) !== false ||
                                stripos($row['economy_life'], $search) !== false ||
                                stripos($row['asset_life'], $search) !== false ||
                                stripos($row['asset_condition'], $search) !== false ||
                                stripos($row['acquisition_date'], $search) !== false ||
                                stripos($row['acquisition_value'], $search) !== false ||
                                stripos($row['depreciation_value'], $search) !== false ||
                                stripos($row['book_value'], $search) !== false ||
                                stripos($row['location'], $search) !== false ||
                                stripos($row['status'], $search) !== false
                            ) {
                                echo "<tr>";
                                echo "<td>" . ($index + 1) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>";
                                echo "<form method='post' action='update.php'>";
                                
                                echo "<select name='options' id='options'>";
                                echo "<option value='-- Choose --'disabled >-- Choose --</option>";
                               
                                foreach ($options as $option) {
                                   
                                    if ($row['status'] == $option) {
                                        echo "<option value='$option' selected disabled>$option</option>";
                                    } else {
                                        echo "<option value='$option'>$option</option>";
                                    }
                                }
                                echo "</select>";
                                echo "<input type='hidden' name='asset_group_lvl1' value='" . htmlspecialchars($row['asset_group_lvl1']) . "'>";
                                echo "<input type='hidden' name='company_code' value='" . htmlspecialchars($row['company_code']) . "'>";
                                echo "<input type='hidden' name='branch_code' value='" . htmlspecialchars($row['branch_code']) . "'>";
                                echo "<input type='hidden' name='asset_code' value='" . htmlspecialchars($row['asset_code']) . "'>";
                                echo "<input type='hidden' name='revision_number' value='" . htmlspecialchars($row['revision_number']) . "'>";
                                echo "<input type='hidden' name='document_line' value='" . htmlspecialchars($row['document_line']) . "'>";
                                echo "<input type='hidden' name='subasset_code' value='" . htmlspecialchars($row['subasset_code']) . "'>";
                                echo "<input type='hidden' name='book_value' value='" . htmlspecialchars($row['book_value']) . "'>";
                                echo "<input type='hidden' name='location' value='" . htmlspecialchars($row['location']) . "'>";
                                echo "<input type='hidden' name='economy_life' value='" . htmlspecialchars($row['economy_life']) . "'>";
                                echo "<input type='hidden' name='subasset_name' value='" . htmlspecialchars($row['subasset_name']) . "'>";
                                echo "<input type='hidden' name='acquisition_value' value='" . htmlspecialchars($row['acquisition_value']) . "'>";
                                echo "<input type='hidden' name='acquisition_date' value='" . htmlspecialchars($row['acquisition_date']) . "'>";
                                echo "<input type='hidden' name='depreciation_value' value='" . htmlspecialchars($row['depreciation_value']) . "'>";
                                echo "<input type='hidden' name='asset_condition' value='" . htmlspecialchars($row['asset_condition']) . "'>";
                                echo "<input type='hidden' name='asset_life' value='" . htmlspecialchars($row['asset_life']) . "'>";
                                echo "<input type='submit' name='update_status' value='Update'>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row['company_code']) . "</td>";

                                echo "<td>" . htmlspecialchars($row['branch_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subasset_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['subasset_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['asset_group_lvl1']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['asset_code']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['revision_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['document_line']) . "</td>";
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
    <script>
        $('#corr-btn').on('click', function() {
            window.location.href = 'correction.php';
        });
    </script>


</body>

</html>