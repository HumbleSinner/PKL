<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script> <!-- Pastikan JS dimuat setelah halaman -->
    <title>Login</title>
</head>
<body>
    <p>
        <img class="logo-awal" src="logo muara.png" alt="logo"/>
    </p>
    <div class="container" id="login">
        <h1 class="form-title">Login</h1>
        <form method="post" action="login.php"> <!-- Ganti action ke login.php -->
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">E-mail</label>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            
            <input type="submit" class="btn" value="Login" name="login"> <!-- Ganti nama button ke 'login' -->
            <div class="links">
                <p>Don't have an account yet? 
                    <a href="signUp.php">Sign Up</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
