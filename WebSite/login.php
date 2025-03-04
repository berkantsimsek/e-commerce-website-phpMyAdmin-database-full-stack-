<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else{
      $message[] = 'hatalı şifre veya e-posta!';
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

   <!-- css  link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>ŞİMDİ GİRİŞ YAP</h3>
      <input type="email" name="email" required placeholder="email girin" class="box">
      <input type="password" name="password" required placeholder="şifreyi girin" class="box">
      <input type="submit" name="submit" class="btn" value="şimdi giriş yap">
      <p>HESABINIZ YOK MU?<a href="register.php">ŞİMDİ KAYDOL</a></p>
   </form>

</div>

</body>
</html>