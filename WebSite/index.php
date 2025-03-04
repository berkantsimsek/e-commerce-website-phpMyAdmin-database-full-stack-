
<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};



if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Ürünün stok bilgisini alıyoruz
   $select_product_stock = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
   $fetch_product_stock = mysqli_fetch_assoc($select_product_stock);
   $stock = $fetch_product_stock['stock'];

   // Stok durumu kontrolünü yapıyoruz
   if($product_quantity > $stock) {
      $message[] = 'Yeterli stok bulunmuyor!';
   } else {
      // Sepette bu ürün var mı diye kontrol ediyoruz
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if(mysqli_num_rows($select_cart) > 0){
         $message[] = 'Ürün zaten sepette!';
      } else {
         // Sepete ürün ekliyoruz
         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');

         // Stok miktarını güncelliyoruz
         $new_stock = $stock - $product_quantity;
         mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('query failed');

         $message[] = 'Ürün sepete eklendi!';
      }
   }
}




if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];

   // Sepetteki ürünü seçiyoruz
   $select_cart_item = mysqli_query($conn, "SELECT name, quantity FROM `cart` WHERE id = '$update_id'") or die('query failed');
   $fetch_cart_item = mysqli_fetch_assoc($select_cart_item);
   $product_name = $fetch_cart_item['name'];
   $current_quantity = $fetch_cart_item['quantity'];

   // Ürünün stok bilgisini alıyoruz
   $select_product_stock = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
   $fetch_product_stock = mysqli_fetch_assoc($select_product_stock);
   $stock = $fetch_product_stock['stock'];

   // Yeni miktar ile stok karşılaştırılıyoruz 
   if($update_quantity > $stock + $current_quantity) {
      $message[] = 'Yeterli stok bulunmuyor!';
   } else {
      // Sepet miktarını güncelliyoruz
      mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');

      // Stok miktarını güncelliyoruz (Mevcut stoktan sepetteki ürün miktarını çıkarıyoruz)
      $new_stock = $stock + $current_quantity - $update_quantity;
      mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('query failed');

      $message[] = 'Sepet miktarı güncellendi!';
   }
}


if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];

   // Sepetteki ürünü seçiyoruz
   $select_cart_item = mysqli_query($conn, "SELECT name, quantity FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   $fetch_cart_item = mysqli_fetch_assoc($select_cart_item);
   $product_name = $fetch_cart_item['name'];
   $product_quantity = $fetch_cart_item['quantity'];

   // Ürünün stok bilgisini alıyoruz
   $select_product_stock = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
   $fetch_product_stock = mysqli_fetch_assoc($select_product_stock);
   $stock = $fetch_product_stock['stock'];

   // Stok miktarını güncelliyoruz (silinen ürün miktarını geri ekliyoruz)
   $new_stock = $stock + $product_quantity;
   mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('query failed');

   // Sepetten ürünü siliyoruz
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}

  
if(isset($_GET['delete_all'])){
   // Sepetteki tüm ürünleri alıyoruz
   $select_cart_items = mysqli_query($conn, "SELECT name, quantity FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

   // Her ürün için stok miktarını güncelliyoruz
   while($fetch_cart_item = mysqli_fetch_assoc($select_cart_items)){
      $product_name = $fetch_cart_item['name'];
      $product_quantity = $fetch_cart_item['quantity'];

      // Ürünün stok bilgisini alıyoruz
      $select_product_stock = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
      $fetch_product_stock = mysqli_fetch_assoc($select_product_stock);
      $stock = $fetch_product_stock['stock'];

      // Silinen ürünlerin stok miktarını geri ekliyoruz
      $new_stock = $stock + $product_quantity;
      mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('query failed');
   }

   // Sepetteki tüm ürünleri siliyoruz
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>TAKI DÜNYASİ</title>

   <!-- css  link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- Çerez Bildirimi Stilleri -->
   <link rel="stylesheet" href="css/cookies.css">

</head>
<body>
<!--çerez kısmı burada---->
<?php
 if (isset($_COOKIE['username'])) {

 echo "<div class='welcome-message'>Hoşgeldiniz, " . htmlspecialchars($_COOKIE['username']) . "!</div>";
}
?>

   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">

<div class="user-profile">

   <?php
      $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_user) > 0){
         $fetch_user = mysqli_fetch_assoc($select_user);
      };
   ?>

   <p> KULLANICI ADI : <span><?php echo $fetch_user['name']; ?></span> </p>
   <p> EMAİL : <span><?php echo $fetch_user['email']; ?></span> </p>
   <div class="flex">
      <a href="login.php" class="btn">GİRİŞ YAP</a>
      <a href="register.php" class="option-btn">KAYIT OL</a>
   <!--bu kısım -->
      <a href="homepage.html?logout=<?php echo $user_id; ?>" onclick="return confirm('Çıkış yapmak istediğinizden emin misiniz?');" class="delete-btn">ÇIKIŞ</a>
   </div>

</div>

<div class="products">

   <h1 class="heading">DOĞAL TAŞ TAKILARIMIZ</h1>

   <div class="box-container">

   <?php
      $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
      if(mysqli_num_rows($select_product) > 0){
         while($fetch_product = mysqli_fetch_assoc($select_product)){
   ?>
      <form method="post" class="box" action="">
         <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_product['name']; ?></div>
         <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
         <input type="submit" value="Sepete ekle" name="add_to_cart" class="btn">
      </form>
   <?php
      };
   };
   ?>

   </div>

</div>

<div class="shopping-cart">

   <h1 class="heading">alışveriş Sepeti</h1>

   <table>
   <thead>
      <th>RESİM</th>
      <th>ADI</th>
      <th>FİYATI</th>
      <th>ADET</th>
      <th>TOPLAM ÖDEME</th>
      <th>STOK DURUMU</th>
      <th>SİLME</th>
   </thead>
   <tbody>
   <?php
      $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $grand_total = 0;
      if(mysqli_num_rows($cart_query) > 0){
         while($fetch_cart = mysqli_fetch_assoc($cart_query)){
            $product_name = $fetch_cart['name'];
            $product_quantity = $fetch_cart['quantity'];

            // Ürünün stok bilgisini alıyoruz
            $select_product_stock = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
            $fetch_product_stock = mysqli_fetch_assoc($select_product_stock);
            $stock = $fetch_product_stock['stock'];
   ?>
      <tr>
         <td><img src="images/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
         <td><?php echo $fetch_cart['name']; ?></td>
         <td>$<?php echo $fetch_cart['price']; ?>/-</td>
         <td>
            <form action="" method="post">
               <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
               <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
               <input type="submit" name="update_cart" value="Adet Güncelle" class="option-btn">
            </form>
         </td>
         <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</td>
         <td><?php echo $stock > 0 ? 'Stokta mevcut: ' . $stock : 'Stok yok'; ?></td>
         <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Sepetten ürünü kaldırmak istiyor musunuz?');">sil</a></td>
      </tr>
   <?php
         $grand_total += $sub_total;
         }
      }else{
         echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">HİÇBİR ÜRÜN EKLENMEDİ!</td></tr>';
      }
   ?>
   <tr class="table-bottom">
      <td colspan="4">GENEL TOPLAM ÖDEME :</td>
      <td>$<?php echo $grand_total; ?>/-</td>
      <td><a href="index.php?delete_all" onclick="return confirm('SEPETTEKİ HER ŞEY SİLİNSİN Mİ?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">hepsini sil</a></td>
   </tr>
</tbody>
</table>


   <div class="cart-btn">  
      <a href="#" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">ÇIKIŞA DEVAM ET</a>
   </div>

</div>

</div>

</body>
</html>