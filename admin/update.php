<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
      }

      if (isset($_POST['submit'])) {
            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1 ");
            $select_tutor->execute([$tutor_id]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            $prev_pass = $fetch_tutor['password'];
            $prev_image = $fetch_tutor['image'];

            $name = $_POST['name'];

            $profession = $_POST['profession'];
            
            $email = $_POST['email'];

            if (!empty($name)) {
                  $update_name = $conn->prepare("UPDATE `tutors` SET name = ? WHERE id = ?");
                  $update_name->execute([$name, $tutor_id]);
                  $message[] = 'username updated successfully';
            }
            if (!empty($profession)) {
                  $update_profession = $conn->prepare("UPDATE `tutors` SET profession = ? WHERE id = ?");
                  $update_profession->execute([$profession, $tutor_id]);
                  $message[] = 'user profession updated successfully';
            }
            if (!empty($email)) {
                  $select_email = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? AND email = ?");
                  $select_email->execute([$tutor_id, $email]);
                  if ($select_email->rowCount() > 0) {
                        $message[] = 'email already taken';
                  }else{
                        $update_email = $conn->prepare("UPDATE `tutors` SET email = ? WHERE id = ?");
                        $update_email->execute([$email, $tutor_id]);
                        $message[] = 'user email updated successfully';
                  }
            }

            //update profile image of tutor

            $image = $_FILES['image']['name'];
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $rename = unique_id().'.'.$ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_files/'.$rename;

            if (!empty($image)) {
                  if ($image_size > 2000000) {
                        $message[] = 'image size too large';
                  }else{
                        $update_image = $conn->prepare("UPDATE `tutors` SET ­­`image` = ? WHERE id = ?");
                        $update_image->execute([$rename, $tutor_id]);
                        move_uploaded_file($image_tmp_name, $image_folder);
                        if ($prev_image != '' AND $prev_image != $rename) {
                              unlink('../uploaded_files/'.$prev_image);
                        }
                        $message[] = 'image updated successfully';
                  }
            }

            //update password

            $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
            $old_pass = sha1($_POST['old_pass']);
            $new_pass = sha1($_POST['new_pass']);
            $cpass = sha1($_POST['cpass']);

            if ($old_pass != $empty_pass) {
                  if ($old_pass != $prev_pass) {
                        $message[] = 'old password not matched';
                  }elseif ($new_pass != $cpass) {
                        $message[] = 'confirm password not matched';
                  }else {
                        if ($new_pass != $empty_pass) {
                              $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
                              $update_pass->execute([$cpass, $tutor_id]);
                              $message[] = 'password updated successfully';
                        }else{
                              $message[] = 'please enter a new password';
                        }
                  }
            }
      }


?>
<style type="text/css">
      <?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>update profile</title>
      <!-- boxicon link -->
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>    
</head>
<body>
      <?php include '../components/admin_header.php'; ?>
      <div class="form-container" style="min-height:calc(100vh - 19rem); padding: 5rem 0;">
            <img src="../image/fun.jpg" class="form-img" style="left: -2%;">
            <form action="" method="post" enctype="multipart/form-data" class="register">
                  <h3>update profile</h3>
                  <div class="flex">
                        <div class="col">
                              <p>your name <span>*</span></p>
                              <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50"
                              required class="box">
                              <p> your profession <span>*</span></p>
                              <select name="profession" required class="box">
                                    <option value="" disabled selected><?= $fetch_profile['profession']; ?></option>
                                    <option value="developer">developer</option>
                                    <option value="designer">designer</option>
                                    <option value="musician">musician</option>
                                    <option value="biologist">biologist</option>
                                    <option value="teacher">teacher</option>
                                    <option value="engineer">engineer</option>
                                    <option value="lawyer">lawyer</option>
                                    <option value="accountant">accountant</option>
                                    <option value="doctor">doctor</option>
                                    <option value="journalist">journalist</option>
                                    <option value="photographer">photographer</option>
                                    <option value="software developer">software developer</option>                                    
                              </select>
                              <p>your email <span>*</span></p>
                              <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="30"
                              required class="box">
                        </div>
                        <div class="col">
                              <p>old password <span>*</span></p>
                              <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20"
                              required class="box">
                              <p>new password <span>*</span></p>
                              <input type="password" name="new_pass" placeholder="confirm your new password" maxlength="20"
                              required class="box">
                              <p>new password <span>*</span></p>
                              <input type="password" name="cpass" placeholder="confirm your password" maxlength="20"
                              required class="box">
                              
                        </div>                        
                  </div>
                  <p>update pic <span>*</span></p>
                  <input type="file" name="image" accept="image/*" required class="box">
                  <input type="submit" name="submit" class="btn" value="update profile">
            </form>
      </div>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
      
</body>
</html>