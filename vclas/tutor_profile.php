<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_EMAIL);
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE email = ?');
   $select_tutor->execute([$tutor_email]);

   if ($select_tutor->rowCount() > 0) {
      $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      $tutor_id = $fetch_tutor['id'];

      $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
      $count_playlists->execute([$tutor_id]);
      $total_playlists = $count_playlists->rowCount();

      $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
      $count_contents->execute([$tutor_id]);
      $total_contents = $count_contents->rowCount();

      $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
      $count_likes->execute([$tutor_id]);
      $total_likes = $count_likes->rowCount();

      $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
      $count_comments->execute([$tutor_id]);
      $total_comments = $count_comments->rowCount();
   } else {
      header('location:teachers.php');
      exit;
   }

}else{
   header('location:teachers.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>O'qituvchi profili</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="tutor-profile">

   <h1 class="heading">Profil ma'lumotlari</h1>

   <div class="details">
      <div class="tutor">
         <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="">
         <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
         <span><?= htmlspecialchars($fetch_tutor['profession']); ?></span>
      </div>
      <div class="flex">
         <p>Umumiy pleylistlar: <span><?= $total_playlists; ?></span></p>
         <p>Umumiy videolar: <span><?= $total_contents; ?></span></p>
         <p>Umumiy yoqtirishlar: <span><?= $total_likes; ?></span></p>
         <p>Umumiy izohlar: <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<section class="courses">

   <h1 class="heading">So'nggi darsliklar</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = ?");
         $select_courses->execute([$tutor_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               ?>
               <div class="box">
                  <div class="tutor">
                     <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="">
                     <div>
                        <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
                        <span><?= htmlspecialchars($fetch_course['date']); ?></span>
                     </div>
                  </div>
                  <img src="uploaded_files/<?= htmlspecialchars($fetch_course['thumb']); ?>" class="thumb" alt="">
                  <h3 class="title"><?= htmlspecialchars($fetch_course['title']); ?></h3>
                  <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">Darslikni ko'rish</a>
               </div>
            <?php
            }
         } else {
            echo '<p class="empty">Hozirda darsliklar qo\'shilmagan!</p>';
         }
      ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>    

<script src="js/script.js"></script>

</body>
</html>
