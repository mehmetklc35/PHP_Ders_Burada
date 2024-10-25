<?php      
      include '../components/connect.php';

      if (isset($_COOKIE['tutor_id'])) {
            $tutor_id = $_COOKIE['tutor_id'];
      }else{
            $tutor_id = '';
            header('location: login.php');
      }
      if (isset($_POST['submit'])) {
            $id = unique_id();
            $title = $_POST['title'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $playlist =$_POST['playlist'];
        
            $image = $_FILES['image']['name'];
            // Burayı düzeltiyoruz: $pathinfo fonksiyonunu doğrudan çağırıyoruz
            $ext = pathinfo($image, PATHINFO_EXTENSION); 
            $rename = unique_id() . '.' . $ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name']; // Geçici dosya adını al
            $image_folder = '../uploaded_files/' . $rename; // Hedef dosya yolu

           // Video dosyasının adını ve uzantısını al
            $video_name = $_FILES['video']['name'];
            $video_ext = pathinfo($video_name, PATHINFO_EXTENSION); 

            // Videoyu benzersiz bir isimle yeniden adlandır
            $rename_video = unique_id() . '.' . $video_ext;

            // Geçici dosya yolunu al
            $video_tmp_name = $_FILES['video']['tmp_name']; 

            // Hedef dosya yolunu belirle
            $video_folder = '../uploaded_files/' . $rename_video; 
            
            if ($image_size > 2000000) {
                  $message[] = 'image size is too large';
            }else{
                  $add_playlist = $conn->prepare("INSERT INTO `content`(id,tutor_id, playlist_id, title, description, video, thumb, status)
                        VALUES(?,?,?,?,?,?,?,?)");
                  $add_playlist->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video,
                        $rename, $status]);
                  move_uploaded_file($image_tmp_name, $image_folder);
                  move_uploaded_file($video_tmp_name, $video_folder);
                  $message[] = 'new course uploaded';
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
      <section class="video-form">
            <h1 class="heading">upload content</h1>

            <form action="" method="post" enctype="multipart/form-data">
                  <p>playlist status <span>*</span></p>
                  <select name="status" class="box">
                        <option value="" selected disabled>---select status---</option>
                        <option value="active">active</option>
                        <option value="deactive">deactive</option>
                  </select>
                  <p>video title <span>*</span></p>
                  <input type="text" name="title" maxlength="150" required placeholder="Enter playlist
                  title" class="box">
                  <p>video description <span>*</span></p>
                  <textarea name="description" class="box" placeholder="write description" maxlength="1000"
                  cols="30" rows="10"></textarea>
                  <p>video playlist <span>*</span></p>
                  <select name="playlist" class="box" required>
                        <option value="" selected disabled>--select playlist--</option>
                        <?php
                              $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                              $select_playlists->execute([$tutor_id]);
                              if ($select_playlists->rowCount() > 0) {
                                    while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {

                                    
                        ?>
                        <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?>"></option>
                        <?php
                              } 
                        ?>
                        <?php
                              }else {
                                    echo '<p class="empty"> no playlist added yet! </p>';
                              } 
                        ?>

                  </select>

                  <p>select thumbnail <span>*</span></p>
                  <input type="file" name="image" accept="image/*" required class="box">
                  <p>select video <span>*</span></p>
                  <input type="file" name="video" accept="video/*" required class="box">
                  <input type="submit" name="submit" value="upload video" class="btn">
            </form>
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>