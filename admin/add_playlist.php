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

            $image = $_FILES['image']['name'];
            $ext = $pathinfo($image, PATHINFO_EXTENSION);
            $rename = unique_id().'.'.$ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = '../uploaded_files/'.$rename;

            $add_playlist = $conn->prepare("INSERT INTO `playlist`(id, tutor_id, title, description,
                  thumb, status) VALUES(?,?,?,?,?,?)");
            $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);
            move_uploaded_file($image_tmp_name, $image_folder);

            $message[] = 'new playlist created';
            
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
      <section class="playlist-form">
            <h1 class="heading">create playlist</h1>

            <form action="" method="post" enctype="multipart/form-data">
                  <p>playlist status <span>*</span></p>
                  <select name="status" class="box">
                        <option value="" selected disabled>---select status---</option>
                        <option value="active">active</option>
                        <option value="deactive">deactive</option>
                  </select>
                  <p>playlist title <span>*</span></p>
                  <input type="text" name="title" maxlength="100" required placeholder="Enter playlist
                  title" class="box">
                  <p>playlist description <span>*</span></p>
                  <textarea name="description" class="box" placeholder="write description" maxlength="1000"
                  cols="30" rows="10"></textarea>
                  <p>playlist thumbnail <span>*</span></p>
                  <input type="file" name="image" accept="image/*" required class="box">
                  <input type="submit" name="submit" value="create playlist" class="btn">
            </form>
           
      </section>
      <?php include '../components/footer.php'; ?>
      <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>