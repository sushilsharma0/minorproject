<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$message = '';

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $security_answer = $_POST['security_answer'];
   $security_answer = filter_var($security_answer, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      if ($row['security_answer'] === $security_answer) {
         $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass->execute([$new_pass, $row['id']]);

         $message = 'Password changed successfully!';
      } else {
         $message = 'Security question answer does not match.';
      }
   } else {
      $message = 'Email address not found or does not match your account.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Change Password</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>


<header class="header">

   <section class="flex">
      <div style="display:flex; align-items:center; gap:1rem;">
         <img style="height:7rem;" src="components/logo.png" alt="">
         <a href="home.php" class="logo">codeRASI</a>
      </div>

      <form action="search_course.php" method="post" class="search-form">
         <input type="text" name="search_course" placeholder="search courses..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_course_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
      <h3><?= $fetch_profile['name']; ?></h3>
      <span>student</span>
      <a href="profile.php" class="btn">view profile</a>
      <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">logout</a>
      <?php
         } else {
      ?>
      <h3>please login or register</h3>
      <div class="flex-btn">
         <a href="login.php" class="option-btn">login</a>
         <a href="register.php" class="option-btn">register</a>
      </div>
      <?php
         }
      ?>
   </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>student</span>
         <a href="profile.php" class="btn">view profile</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about us</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <!-- <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a> -->
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>


    
         </nav>

</div>

<!-- side bar section ends -->

<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Change Password</h3>
      <?php
         if(isset($message)) {
            echo '<p class="message">' . $message . '</p>';
         }
      ?>
      <p>Enter your email and new password to change your password</p>
      <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
      <p>Security Question Answer <span>*</span></p>
<select name="security_answer" required class="box">
   <option value="" disabled selected>Select your security question</option>
   <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
   <option value="What was the name of your first pet?">What was the name of your first pet?</option>
   <option value="What city were you born in?">What city were you born in?</option>
   <!-- Add more security questions here -->
</select>
<p>Security Question Answer <span>*</span></p>
      <input type="text" name="security_answer" placeholder="Enter your security question answer" maxlength="100" required class="box" autocomplete="off">
      <p>New Password <span>*</span></p>
      <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" required class="box">
      <input type="submit" name="submit" value="Change Password" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>








