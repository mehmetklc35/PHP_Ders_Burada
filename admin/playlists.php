<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
      }

      if (isset($_POST['delete'])) {
            $delete_id = $_POST['playlist_id'];
            
            $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
            $verify_playlist->execute([$delete_id, $tutor_id]);

            if ($verify_playlist->rowCount() > 0) {

                  $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
                  $delete_playlist_thumb->execute([$delete_id]);
                  $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
                  unlink('../uploaded_files/'.$fetch_thumb['thumb']);

                  $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
                  $delete_bookmark->execute([$delete_id]);
                  $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
                  $delete_playlist->execute([$delete_id]);
                  $message[] = 'playlist deleted';
            }else {
                  $message[] = 'playlist already deleted';
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
      <title>added playlists</title>
      <!-- boxicon link -->
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>      

</head>
<body>
      <?php include '../components/admin_header.php'; ?>
      <section class="playlists">
            <h1 class="heading">added playlists</h1>

            <div class="box-container">
                  <div class="add">
                        <a href="add_playlist.php"> <i class="bx bx-plus"></i> </a>
                  </div>
                  <?php
                        $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date DESC");
                        $select_playlist->execute([$tutor_id]);
                        if ($select_playlist->rowCount() > 0) {
                              while ($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
                                    $playlist_id = $fetch_playlist['id'];
                                    $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                                    $count_videos->execute([$playlist_id]);
                                    $total_videos = $count_videos->rowCount();
                  ?>              
                  <div class="box">
                        <div class="flex">
                              <div>
                                    <i class="bx bx-dots-vertical-rounded" style="<?= $fetch_playlist['status'] == 'active' ? 'color:limegreen' : 'color:red'; ?>"></i> 
                                    <span style="<?= $fetch_playlist['status'] == 'active' ? 'color:limegreen' : 'color:red'; ?>">
                                    <?= $fetch_playlist['status']; ?>
                                    </span>
                              </div>
                              <i class="bx bx-calender"></i> 
                              <span><?= $fetch_playlist['date']; ?></span>
                        </div>
                        <div class="thumb">
                              <span><?= $total_videos; ?></span>
                              <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
                        </div>
                        <h3 class="title"><?= htmlspecialchars($fetch_playlist['title']); ?></h3>
                        <p class="description"><?= htmlspecialchars($fetch_playlist['description']); ?></p>
                        <form action="" method="post" class="flex-btn">
                              <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
                              <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">update</a>
                              <input type="submit" name="delete" value="delete" class="btn" onclick="return confirm('delete this playlist');">
                              <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">view playlist</a>
                        </form>
                        </div>

                  <?php
                              }
                        }else {
                              echo '<p class="empty">no playlist added yet! </p>';
                        }
                  ?>
            </div>
            

            
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>