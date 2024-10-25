<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
      }

      if (isset($_GET['get_id'])) {
            $get_id = $_GET['get_id'];
      }else{
            $get_id = '';
            header('location:playlist.php');      
      }      
     
      //delete playlist

      if (isset($_POST['delete'])) {
            $delete_id = $_POST['playlist_id'];

            $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
                  $delete_playlist_thumb->execute([$delete_id]);
                  $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
                  unlink('../uploaded_files/'.$fetch_thumb['thumb']);

                  $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
                  $delete_bookmark->execute([$delete_id]);
                  $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
                  $delete_playlist->execute([$delete_id]);
                  header('location:playlists.php');
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
      <title>add playlists</title>
      <!-- boxicon link -->
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>      

</head>
<body>
      <?php include '../components/admin_header.php'; ?>
      <section class="view-playlist">
            <h1 class="heading">playlist detail</h1>

            <?php
                  $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ?");
                  $select_playlist->execute([$get_id, $tutor_id]);
                  if ( $select_playlist->rowCount() > 0 ) {
                        while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
                              $playlist_id = $fetch_playlist['id'];
                              $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                              $count_videos->execute([$playlist_id]);
                              $total_videos = $count_videos->rowCount();                       
            
            ?>
            <div class="row">
                  <div class="thumb">
                        <span><?= $total_videos; ?> </span>
                        <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
                  </div>
                  <div class="details">
                        <h3 class="title"><?= $fetch_playlist['title']; ?> </h3>
                        <div class="date"><i class="bx bxs-calendar-alt"></i><span><?= $fetch_playlist['date']; ?> </span></div>
                  </div>
                  <div class="description">
                        <?= $fetch_playlist['description']; ?> 
                  </div>
                  <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?> ">
                        <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">update playlist</a>
                        <input type="submit" name="delete" value="delete playlist" class="btn" onclick="return confirm('delete this playlist');">
                  </form>
            </div>
            <?php
                        }
                  }else {
                        echo '<p class="empty"> no playlist added yet! </p>';
                  }
            ?>
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>