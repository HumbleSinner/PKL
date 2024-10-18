<?php
include('../loginfix/functions.php');
session_start();

try {
    $tables = [];
    $stmt = $conn1->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tables[] = $row['TABLE_NAME'];
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    die();
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
                        <p> Table Name : </p>
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
        </div>

        <main class="table" id="customers_table">
            <section class="table__header">
                <h1>Preview Data Table</h1>
                <div class="correction">
                <button type="submit" class="corr-btn" name="corr-btn" id="corr-btn">
                    <a href="correction.php"> CORRECTION</a>
                </button>
                </div>
                <div class="add-column">
                <button type="submit" class="add-btn"><a href="add.php">ADD COLUMN</a></button>
                 </div>

                 <div class="Finish">
                    <button type="submit" class="finish-btn">
                        <a href="finish.php">FINISH</a>
                    </button>
                 </div>
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
                            <th>Asset Code</th>
                            <!-- <th>revision_number</th>
                            <th>document_line</th> -->
                            <th>Economy Life</th>
                            <th>Asset Life</th>
                            <th>Asset Condition</th>
                            <th>Acquisition Date</th>
                            <th>Acquisition Value</th>
                            <th>Depreciation Value</th>
                            <th>Book Value</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($x = 0; $x <= 10; $x++) {
                            echo "<tr>";
                            echo "<td >" . ($x + 1) . "</td>";
                            echo " <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>  
                    </tr>";
                        }
                        ?>


                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="style.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-dropdown li').on('click', function() {
                $('#table-dropdown li').removeClass('selected');
                $(this).addClass('selected');
                $('.select .selected').text($(this).text().trim()); 
                $('#selected_table').val($(this).text().trim());
            });

            $('#result-button').on('click', function() {
                var selectedTable = $('#selected_table').val().trim();
                console.log('Table selected: ' + selectedTable); 

                if (selectedTable) {
                    $.ajax({
                        url: 'result.php',
                        type: 'GET',
                        data: {
                            action: 'get_table',
                            table: selectedTable
                        },
                        success: function(response) {
                            alert('Table format is being checked');
                            window.location.href = 'result.php?table=' + selectedTable; 
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' ' + error); 
                        }
                    });
                } else {
                    alert("Please select a table first.");
                }
            });
        });
    </script>

</body>

</html>