<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
      }

      $select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? ");
      $select_contents->execute([$tutor_id]);
      $total_contents = $select_contents->rowCount();

      $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ");
      $select_playlists->execute([$tutor_id]);
      $total_playlists = $select_playlists->rowCount();

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ? ");
      $select_likes->execute([$tutor_id]);
      $total_likes = $select_likes->rowCount();

      
      $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ? ");
      $select_comments->execute([$tutor_id]);
      $total_comments = $select_comments->rowCount();
?>
<style type="text/css">
      <?php include '../css/admin_style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>dashboard</title>
      <!-- boxicon link -->
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>      

</head>
<body>
      <?php include '../components/admin_header.php'; ?>
      <section class="dashboard">
            <h1 class="heading">dashboard</h1>
            <div class="box-container">
                  <div class="box">
                        <h3>welcome!!!</h3>
                        <p><?= $fetch_profile['name']; ?></p>
                        <a href="profile.php" class="btn"> view profile </a>
                  </div>
                  <div class="box">
                        <h3><?= $total_contents; ?></h3>
                        <p>total contents</p>
                        <a href="add_content.php" class="btn"> add new content </a>
                  </div>
                  <div class="box">
                        <h3><?= $total_playlists; ?></h3>
                        <p>total playlists</p>
                        <a href="add_playlist.php" class="btn"> add new playlists </a>
                  </div>
                  <div class="box">
                        <h3><?= $total_likes; ?></h3>
                        <p>total likes</p>
                        <a href="contents.php" class="btn"> view contents </a>
                  </div>
                  <div class="box">
                        <h3><?= $total_comments; ?></h3>
                        <p>total comments</p>
                        <a href="comments.php" class="btn"> view comments </a>
                  </div>
                  <div class="box">
                        <h3>quick start</h3>
                        <div class="flex-btn">
                              <a href="login.php" class="btn" style="width: 200px;">login now</a>
                              <a href="register.php" class="btn" style="width: 200px;">register now</a>
                        </div>
                  </div>
            </div>
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>