<?php
include 'config.php';
session_start();

// Admin girişi kontrolü yaptım
if(!isset($_SESSION['admin_id'])){
   header('location:login.php');
};

// Ürün stok durumunu güncelledim
if(isset($_POST['update_stock'])){
   $product_id = $_POST['product_id'];
   $new_stock = $_POST['new_stock'];

   // Stok miktarını güncelledim
   $update_stock = mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE id = '$product_id'") or die('Query failed');
   $message[] = 'Stok durumu güncellendi!';
}

// Kullanıcıyı silme
if(isset($_GET['delete_user'])){
   $user_id = $_GET['delete_user'];

   // Kullanıcıyı user_form tablosundan sildim
   $delete_user_from_user_form = mysqli_query($conn, "DELETE FROM `user_form` WHERE id = '$user_id'") or die('Query failed');

   $message[] = 'Kullanıcı silindi!';
}

// Kullanıcının sepetini silme
if(isset($_GET['delete_cart'])){
   $user_id = $_GET['delete_cart'];

   // Sepetten kullanıcının tüm verilerini silme
   $delete_user_from_cart = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');

   $message[] = 'Kullanıcının kargosu silindi!';
}

// Kullanıcıları listelemeyi burada yaptım
$users_query = mysqli_query($conn, "SELECT * FROM `user_form`") or die('Query failed');

// Sepet verilerini görüntülemeyi burada yaptım
$cart_query = mysqli_query($conn, "SELECT * FROM `cart`") or die('Query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Paneli</title>
   <link rel="stylesheet" href="css/admin.css">
</head>
<body>
   <div class="container">
     
      <h1>Admin Paneli</h1>

    


      
      <h2>Ürün Stok Durumunu Güncelle</h2>
      <form action="" method="post">
         <select name="product_id" required>
            <option value="">Bir Ürün Seçin</option>
            <?php
               // Ürünleri çektim
               $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
               while($fetch_product = mysqli_fetch_assoc($select_products)){
            ?>
               <option value="<?php echo $fetch_product['id']; ?>"><?php echo $fetch_product['name']; ?></option>
            <?php
               }
            ?>
         </select>
         <input type="number" name="new_stock" required placeholder="Yeni Stok Miktarını Girin">
         <input type="submit" name="update_stock" value="Stok Durumunu Güncelle">
      </form>

      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>

      <!-- Kargo Durumunu Görüntüledim burada  -->
      <h2>Kargo Durumu</h2>
      <table>
         <thead>
            <tr>
               <th>Kullanıcı ID</th>
               <th>Ürün Adı</th>
               <th>Fiyat</th>
               <th>Adet</th>
               <th>Toplam Fiyat</th>
            </tr>
         </thead>
         <tbody>
         <?php
            if(mysqli_num_rows($cart_query) > 0){
               while($fetch_cart = mysqli_fetch_assoc($cart_query)){
         ?>
            <tr>
               <td><?php echo $fetch_cart['user_id']; ?></td>
               <td><?php echo $fetch_cart['name']; ?></td>
               <td><?php echo $fetch_cart['price']; ?>$</td>
               <td><?php echo $fetch_cart['quantity']; ?></td>
               <td><?php echo $fetch_cart['price'] * $fetch_cart['quantity']; ?>$</td>
            </tr>
         <?php
               }
            } else {
               echo '<tr><td colspan="5">Hiçbir ürün alınmamış.</td></tr>';
            }
         ?>
         </tbody>
      </table>

      <!-- Kullanıcıları Yönetimini burada gerçekleştirdim -->
      <h2>Kullanıcıları Yönet</h2>
      <table>
         <thead>
            <tr>
               <th>Kullanıcı ID</th>
               <th>Kullanıcı Adı</th>
               <th>Email</th>
               <th>Kargo Durumu</th>
               <th>Kullanıcıyı Sil</th>
            </tr>
         </thead>
         <tbody>
         <?php
            if(mysqli_num_rows($users_query) > 0){
               while($fetch_user = mysqli_fetch_assoc($users_query)){
         ?>
            <tr>
               <td><?php echo $fetch_user['id']; ?></td>
               <td><?php echo $fetch_user['name']; ?></td>
               <td><?php echo $fetch_user['email']; ?></td>
               <td>
                  <a href="?delete_cart=<?php echo $fetch_user['id']; ?>" class="delete-btn" onclick="return confirm('Kargosu tamamlandığına emin misiniz?')">Kargo Tamamlandı</a>
               </td>
               <td>
                  <a href="?delete_user=<?php echo $fetch_user['id']; ?>" class="delete-btn" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Kullanıcıyı Sil</a>
               </td>
            </tr>
         <?php
               }
            } else {
               echo '<tr><td colspan="5">Hiçbir kullanıcı bulunamadı.</td></tr>';
            }
         ?>
         </tbody>
      </table>
   </div>

   <form action="homepage.html" method="get">
      <button type="submit" class="buttonn">ÇIKIŞ YAP <i class="fa-solid fa-arrow-right"></i></button>
   </form>
</body>
</html>
