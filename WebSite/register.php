<?php
include 'config.php';

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'Kullanıcı zaten mevcut!';
    } else {
        mysqli_query($conn, "INSERT INTO `user_form`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
        $message[] = 'Başarıyla kayıt olundu!';
        
        // Çerez ayarladım
        setcookie('username', $name, time() + 60, "/"); // Çerez 60 sn boyunca geçerli
        setcookie('email', $email, time() + 60, "/");

        header('location:login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKI DÜNYASI</title>

    <!--css link--->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Çerez Bildirimi Stilleri bu css dosyasında yaptım-->
    <link rel="stylesheet" href="css/cookies.css">

    
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
 <!---çerezleri kullandım -->

 <div class="cookie-notice">
    <p>Bu web sitesi, size daha iyi bir deneyim sunmak için çerezler kullanmaktadır. Çerezleri kabul ediyor musunuz?</p>
    <button onclick="acceptCookies()">Kabul Et</button>
</div> 

<script>
    function acceptCookies() {
        document.querySelector('.cookie-notice').style.display = 'none';
        document.cookie = "cookiesAccepted=true; max-age=" + (60 * 60 * 24 * 30) + "; path=/"; //30 gün boyunca geçerli bir çerez .
    }
</script>

<div class="form-container">
    <form action="" method="post">
        <h3>KAYIT OL</h3>
        <input type="text" name="name" require placeholder="kullanıcı adını girin" class="box">
        <input type="email" name="email" require placeholder="e-posta girin" class="box">
        <input type="password" name="password" require placeholder="şifreyi girin" class="box">
        <input type="password" name="cpassword" require placeholder="şifreyi onayla" class="box">
        <input type="submit" name="submit" class="btn" value="şimdi kaydol">
        <p>ZATEN BİR HESABINIZ VAR MI?<a href="login.php">ŞİMDİ GİRİŞ YAP</a></p>
    </form>  
</div>  




    
</body>
</html>