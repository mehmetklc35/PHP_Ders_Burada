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

      if (isset($_POST['delete_video'])) {
            $delete_id = $_POST['video_id'];
        
            // Video detaylarını tek bir sorguyla al
            $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
            $verify_video->execute([$delete_id]);
        
            if ($verify_video->rowCount() > 0) {
                $fetch_video = $verify_video->fetch(PDO::FETCH_ASSOC);
                
                // Thumbnail ve video dosyalarını sil
                unlink('../uploaded_files/' . $fetch_video['thumb']);
                unlink('../uploaded_files/' . $fetch_video['video']);
        
                // Likes ve yorumları sil
                $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
                $delete_likes->execute([$delete_id]);
        
                $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
                $delete_comments->execute([$delete_id]);
        
                // Video kaydını sil
                $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
                $delete_content->execute([$delete_id]);
        
                $message[] = 'Video başarıyla silindi.';
            } else {
                $message[] = 'Video bulunamadı veya zaten silinmiş.';
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
      <section class="contents">
            <h1 class="heading">playlist videos</h1>

            <div class="box-container">                  
                  <?php
                        $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
                        $select_videos->execute([$tutor_id, $playlist_id]);

                        if ($select_videos->rowCount() > 0) {
                              while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)) {
                                    $video_id = $fetch_videos['id'];                              
                  ?> 
                  <div class="box">
                        <div class="flex">
                              <div> <i class="bx bx-dots-vertical-rounded" style="<?php if($fetch_videos
                                    ['status'] == 'active'){echo 'color:limegreen';}else{echo 'color:red';} ?>"></i
                              > <span style="<?php if($fetch_videos['status']=='active'){echo 'color:limegreen'
                                    ;}else{echo 'color:red';} ?>"><?= $fetch_videos['status']; ?></span> </div>
                              <div><i class="bx bxs-calendar-alt"></i> <span><?= $fetch_videos['date']; ?></span> </div>
                        </div>
                        <img src="../uploaded_files/<?= $fetch_videos['thumb']; ?>" class="thumb">
                        <h3 class="title"><?= $fetch_videos['title']; ?></h3>
                        <form action="" method="post" class="flex-btn">
                              <input type="hidden" name="viedo_id" value="<?= $video_id; ?>">
                              <a href="update_content.php?2et_id<?= $video_id; ?>" class="btn">update</a>
                              <input type="submit" name="delete" value="delete video" class="btn" onclick="return confirm('delete this video');">
                              <a href="view_content.php?get_id<?= $video_id; ?>" class="btn">view content</a>
                        </form>
                  </div>
                  <?php 
                              }
                        }else {
                              echo '
                                    <div class="empty">
                                          <p style="margin-bottom: 1.5rem;">no video added yet!</p>
                                          <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a>
                                    </div> 
                              ';
                        }                
                  ?>
                                      
            </div>
            

            
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>