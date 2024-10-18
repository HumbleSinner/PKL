<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/index.php"); // Arahkan ke halaman login jika belum login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="left-side">
        <div class="logo-samping">
            <img src="logo disatukan.png" />
            <h1><u>ASSCA</u></h1>
        </div>
    </div>
    <div class="right-slide">
        <div class="header-user" id="username">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <form method="post" action="../Login/logout.php">
                <button type="submit" class="logout-button">
                    <img src="logout.png" alt="#">
                    Logout
                </button>
            </form>
        </div>
        <div class="container" id="server_connect">
            <form id="myForm" method="post" action="data_user.php">
                <div class="title-connect">
                    <h1>Connect SQL Server</h1>
                </div>
                <div class="server_group">
                    <label for="server_name">Server Name :</label><br>
                    <input type="text" name="server_name" id="server_name" placeholder="your server name" required />
                </div>
                <div class="server_group">
                    <label for="database_name">Database Name :</label><br>
                    <input type="text" name="database_name" id="database_name" placeholder="your database name" required />
                </div>
                <div class="server_group">
                    <label for="username_ID">Username ID (UID) :</label><br>
                    <input type="text" name="username_ID" id="username_ID" placeholder="input username" />
                </div>
                <div class="server_group">
                    <label for="password_server">Password :</label><br>
                    <input type="password" name="password_server" id="password_server" placeholder="input password" />
                </div>
                <div class="checkbox-container">
                    <p>
                        <input type="checkbox" name="trust" id="trust" value="trust" required>
                        <label for="trust">Trust server certificate</label>
                    </p>
                </div>
                <div class="button-container">
                    <button type="submit" class="connect-button" name="submit" id="submit">
                        CONNECT
                        <img src="db.png" alt="Icon">
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>