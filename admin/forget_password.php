<?php
include '../components/connect.php';

$message = ''; // Initialize a message variable

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $newPass = sha1($_POST['new_pass']);
   $newPass = filter_var($newPass, FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? LIMIT 1");
   $select_tutor->execute([$email]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);

   if($select_tutor->rowCount() > 0){
      // Update the tutor's password
      $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
      $update_pass->execute([$newPass, $row['id']]);

      $message = 'Password changed successfully.';
   } else {
      $message = 'No account found with that email.';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<!-- Include your header/navigation here -->

<section class="form-container">
   <form action="" method="post" class="login">
      <h3>Change Password</h3>
      <?php if(!empty($message)) { ?>
         <p><?php echo $message; ?></p>
      <?php } ?>
      <p>Enter your email and new password to change your password</p>
      <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
      <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" required class="box">
      <input type="submit" name="submit" value="Change Password" class="btn">
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