<?php
include 'config.php';  // Veritabanı bağlantısı yaptım
session_start();       // Oturum başlatma yaptım
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <link rel="stylesheet" href="css/admin_login.css">  <!-- CSS dosyasını dahil etme -->
</head>
<body>

    <div class="login-container">
        <h2>Admin Giriş</h2>

        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit" name="login">Giriş Yap</button>
        </form>

        <?php
        if(isset($_POST['login'])) {  
            $admin_email = $_POST['email'];  
            $admin_password = $_POST['password']; 

            // Veritabanında email ve şifreyi kontrol ettim
            $check_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_email' AND password = '$admin_password'") or die('Sorgu başarısız');
            if(mysqli_num_rows($check_admin) > 0){
                $_SESSION['admin_id'] = $admin_email;  // Oturumu başlattım
                header('location:admin.php');  // Başarılı girişte yönlendirme yaptım
            } else {
                echo '<div class="error-message">Yanlış email ya da şifre.</div>';  
            }
        }
        ?>

    </div>

</body>
</html>


