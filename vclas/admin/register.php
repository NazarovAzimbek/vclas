<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM tutors WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'Email allaqachon band!';
   }else{
      if($pass != $cpass){
         $message[] = 'Parol tasdiqlanmagan!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO tutors(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Yangi o‘qituvchi ro‘yxatdan o‘tkazildi! Iltimos, tizimga kiring';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="uz">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ro‘yxatdan o‘tish</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Yangi o‘qituvchi ro‘yxatdan o‘tkazish</h3>
      <div class="flex">
         <div class="col">
            <p>Ismingiz <span>*</span></p>
            <input type="text" name="name" placeholder="Ismingizni kiriting" maxlength="50" required class="box">
            <p>Kasbingiz <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- Kasbingizni tanlang</option>
               <option value="developer">Dasturchi</option>
               <option value="designer">Dizayner</option>
               <option value="musician">Musiqachi</option>
               <option value="biologist">Biolog</option>
               <option value="teacher">O‘qituvchi</option>
               <option value="engineer">Muhandis</option>
               <option value="lawyer">Yurist</option>
               <option value="accountant">Buxgalter</option>
               <option value="doctor">Shifokor</option>
               <option value="journalist">Jurnalist</option>
               <option value="photographer">Fotograf</option>
            </select>
            <p>Email manzilingiz <span>*</span></p>
            <input type="email" name="email" placeholder="Email manzilingizni kiriting" maxlength="20" required class="box">
         </div>
         <div class="col">
            <p>Parolingiz <span>*</span></p>
            <input type="password" name="pass" placeholder="Parolingizni kiriting" maxlength="20" required class="box">
            <p>Parolni tasdiqlang <span>*</span></p>
            <input type="password" name="cpass" placeholder="Parolni tasdiqlang" maxlength="20" required class="box">
            <p>Rasmni tanlang <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">Agar hisobingiz mavjud bo'lsa, <a href="login.php">Kirish</a></p>
      <input type="submit" name="submit" value="Ro‘yxatdan o‘tish" class="btn">
   </form>

</section>

<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>
