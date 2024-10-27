<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
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
      <title>added playlists</title>
      <!-- boxicon link -->
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>      

</head>
<body>
      <?php include '../components/admin_header.php'; ?>
      <section class="contents">
            <h1 class="heading">your contents</h1>

            <div class="box-container">
                  <div class="add">
                        <a href="add_content.php"> <i class="bx bx-plus"></i> </a>
                  </div> 
                  <?php
                        $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? ORDER BY date DESC");
                        $select_videos->execute([$tutor_id]);

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
                              echo '<p class="empty">no video adden in playlist yet!</p>';
                        }                 
                  
                  ?>                      
            </div>
            

            
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>