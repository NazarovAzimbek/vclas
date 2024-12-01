<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'Xabar allaqachon yuborilgan!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'Xabar muvaffaqiyatli yuborildi!';
   }

}

?>

<!DOCTYPE html>
<html lang="uz">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bog'lanish</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>Biz bilan bog'laning</h3>
         <input type="text" placeholder="Ismingizni kiriting" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="Email manzilingizni kiriting" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="Telefon raqamingizni kiriting" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="Xabaringizni kiriting" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="Xabar yuborish" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>Telefon raqam</h3>
         <a href="tel:1234567890">+998 91 634 10 56</a>
         <a href="tel:1112223333">+998 91 774 66 56</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>Email manzil</h3>
         <a href="mailto:azimbeknazarov77@gmail.com">azimbeknazarov77@gmail.com</a>
         <a href="mailto:azimbeknazarov88@gmail.com">azimbeknazarov88@gmail.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Uy manzili</h3>
         <a href="#">Toshkent shahar, Yunusobod tumani , kv. 14 , dom 22, kv. 36</a>
      </div>

   </div>

</section>

<?php include 'components/footer.php'; ?>  

<script src="js/script.js"></script>
   
</body>
</html>
